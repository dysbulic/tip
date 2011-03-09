#! /usr/bin/python
#
# Program to download award winners from irc channel

"""A simple example bot.

This is an example bot that uses the SingleServerIRCBot class from
ircbot.py.  The bot enters a channel and listens for commands in
private messages and channel traffic.  Commands in channel messages
are given by prefixing the text by the bot name followed by a colon.
It also responds to DCC CHAT invitations and echos data sent in such
sessions.

The known commands are:

    stats -- Prints some channel information.

    disconnect -- Disconnect the bot.  The bot will try to reconnect
                  after 60 seconds.

    die -- Let the bot cease to exist.

    dcc -- Let the bot invite you to a DCC CHAT connection.
"""

from ircbot import SingleServerIRCBot
from irclib import (nm_to_n, nm_to_h, irc_lower,
                    ip_numstr_to_quad, ip_quad_to_numstr,
                    DCCConnectionError)

import os, re, glob
import struct
import logging
import zipfile
from zipfile import ZipFile, ZIP_STORED, ZIP_DEFLATED
from contextlib import closing
import random
import time
import threading

logging.basicConfig()
log = logging.getLogger(__name__)
log.setLevel(logging.INFO)

# From: http://www.nullege.com/codes/show/src%40s%40k%40skink-HEAD%40skink%40lib%40cherrypy%40process%40plugins.py/363/threading._Timer/python
class PerpetualTimer(threading._Timer):
    """A subclass of threading._Timer whose run() method repeats."""
  
    def run(self):
        while True:
            self.finished.wait(self.interval)
            if self.finished.isSet():
                return
            self.function(*self.args, **self.kwargs)

class Search:
    def __init__(self, author = "", title = ""):
        self.author = author
        self.title = title
        self.results = []
        self.matches = None
        self.active_source = None
        self.rename = True # Rename to author - title
        
    @property
    def phrase(self):
        last_name = self.author.split()[-1] if len(self.author) > 0 else ""
        phrase = "%s%s%s" % (last_name, " " if len(last_name) > 0 else "", self.title)
        # Characters stripped by search bot
        phrase = phrase.replace("'", " ")
        phrase = re.sub("[,?]", "", phrase)
        phrase = re.sub(r"\s\s+", " ", phrase)
        phrase = phrase.strip()
        return phrase.encode("utf-8")

    @property
    def filetitle(self):
        filename = "%s - %s" % (self.author, self.title)
        filename = filename.replace("/", "_")
        return filename

class UnknownPhrase(Exception):
    pass

class SearchManager():
    def __init__(self):
        self.use_count = {}
        self.searches = {}

    def add(self, search):
        if self.searches.has_key(search.phrase):
            log.error("Duplicate Search: " + search.phrase)
        self.link(search.phrase, search)
        return search

    def get(self, phrase):
        if self.searches.has_key(phrase):
            return self.searches[phrase]
        else:
            raise UnknownPhrase(phrase)

    def link(self, key, search):
        self.searches[key] = search

    def get_source(self, search):
        """Return the result whose server is least used"""
        min_result = None
        for result in search.results:
            if not self.use_count.has_key(result.server):
                self.use_count[result.server] = 0
            ccnt = self.use_count[min_result.server] if min_result is not None else None # Current Count
            rcnt = self.use_count[result.server]     # Result Count

            # Find minimum, coin toss for ties
            if(not result.attempted
               and result.is_html
               and (min_result is None
                    or rcnt < ccnt
                    or (rcnt == ccnt and random.random() > .5))):
               min_result = result
        if min_result is None:
            log.info("No html entires found from %d result%s",
                     len(search.results), "s" if len(search.results) != 1 else "")
        if min_result is not None:
            self.use_count[min_result.server] = self.use_count[min_result.server] + 1
        return min_result

class DownloadSource():
    def __init__(self, server, filename, size):
        self.server = server
        self.filename = filename
        self.size = size
        self.attempted = False
        
    @property
    def req(self):
        """IRC request command"""
        return "!%s %s" % (self.server, self.filename)

    @property
    def is_html(self):
        return re.search("(\[[^\]]*htm[^\]]*\]|\([^\)]*htm[^\)]*\)|\.htm)", self.filename) is not None

searches = SearchManager()
search_queue = []

class DownloadBot(SingleServerIRCBot):
    def __init__(self, channel, nickname, server, port=6667):
        SingleServerIRCBot.__init__(self, [(server, port)], nickname, nickname)
        self.channel = channel

    def on_nicknameinuse(self, connection, event):
        newnick = connection.get_nickname() + "_"
        log.info("Nick Collision: " + newnick)
        connection.nick(newnick)

    def on_welcome(self, connection, event):
        log.info("Joining: " + self.channel)
        connection.join(self.channel)

    def on_privmsg(self, connection, event):
        log.info("Privmsg: " + event.arguments()[0])
        self.do_command(event, event.arguments()[0])

    def on_privnotice(self, connection, event):
        msg = event.arguments()[0]
        stripformat = re.compile("(\x16|\x0f|\x1f|\x02|\x03(\d{1,2}(,\d{1,2})?)?)", re.UNICODE)
        msg = stripformat.sub("", msg)
        
        accept = re.match(r"<<SearchBot>> Your search for \"(.*)\" has been accepted.", msg)
        if accept is not None:
            search_str = accept.group(1)
            try:
                log.info("Search accepted for '%s'" % search_str)
                searches.get(search_str)
            except UnknownPhrase:
                log.error("Search for '%s' not recorded" % search_str)
                search = Search(title=search_str)
                search.rename = False
                searches.add(search)
            return

        missing = re.match("<<SearchBot>> Sorry, your search for \"(.*)\" returned no matches.", msg)
        if missing is not None:
            search_str = missing.group(1)
            search = searches.get(search_str)
            search.matches = 0
            log.info("No results for '%s'" % search_str)
            dir = search.filetitle
            if not os.path.exists(dir):
                log.info("Creating Placeholder: " + dir)
                os.mkdir(dir)
            self.queue_next()
            return

        found = re.match("<<SearchBot>> Your search for \"(.*)\" returned (\d+) match", msg)
        if found is not None:
            search_str = found.group(1)
            count = int(found.group(2))
            searches.get(search_str).matches = count
            log.info("%d results for %s" % (count, search_str))
            return

        welcome = re.match("\[#ebooks\] Welcome to #ebooks", msg)
        if welcome is not None:
            self.start_queue()
            return

        log.info("PrivNotice: " + msg)
        
    def on_pubnotice(self, connection, event):
        log.info("PubNotice: " + event.arguments()[0])

    def on_pubmsg(self, connection, event):
        #log.info("Pubmsg: " + event.arguments()[0])
        args = event.arguments()[0].split(":", 1)
        if len(args) > 1 and irc_lower(args[0]) == irc_lower(self.connection.get_nickname()):
            self.do_command(event, args[1].strip())
        return

    def on_ctcpreply(self, connection, event):
        log.info("CTCP Reply: " + event.arguments()[0])

    def on_ctcp(self, connection, event):
        log.info("CTCP Arg[0]: " + event.arguments()[0])
        if event.arguments()[0] != "DCC" or len(event.arguments()) < 2:
            return
        arg = event.arguments()[1]
        args = re.match(r"^(?P<cmd>\w+)\s+\"?(?P<filename>[^\"].*[^\"])\"?"
                        + r"\s+(?P<ip>\d+)(\s+(?P<unkn1>\d+))?"
                        + r"\s+(?P<port>\d+)\s+(?P<unkn2>\d+)\s*$",
                        arg)
        if args is None or args.group('cmd').upper() != "SEND":
            log.info("CTCP Arg[1]: " + arg)
            return
        peeraddress = ip_numstr_to_quad(args.group('ip'))
        peerport = int(args.group('port'))
        try:
            dcc = self.dcc_connect(peeraddress, peerport, "raw")
            dcc.filename = os.path.basename(args.group('filename'))

            search = re.match("^SearchBot_results_for_(?P<name>.*).txt.zip", dcc.filename)
            dcc.is_search = search is not None
            if dcc.is_search:
                search_str = search.group('name')
                try:
                    dcc.search = searches.get(search_str)
                    log.info("Search Result: " + search_str)
                except UnknownPhrase:
                    dcc.search = searches.add(Search(title=search_str))
                    log.error("Unknown Search: " + search_str)
            else:
                try:
                    dcc.search = searches.get(dcc.filename)
                except UnknownPhrase:
                    # some servers replace spaces
                    dcc.filename = dcc.filename.replace("_", " ")
                    try:
                        dcc.search = searches.get(dcc.filename)
                    except UnknownPhrase:
                        log.error("Unknown File: " + dcc.filename)
                        dcc.search = searches.add(Search(title=dcc.filename))
                        dcc.search.rename = False
            dcc.search.filename = dcc.filename

            if os.path.exists(dcc.filename):
                count = 1
                basename, extension = os.path.splitext(dcc.filename)
                while os.path.exists(dcc.filename):
                    dcc.filename = "%s.%d%s" % (basename, count, extension)
                    count = count + 1
            dcc.file = open(dcc.filename, "w")
            dcc.received_bytes = 0
        except DCCConnectionError as err:
            log.error("Connect Error: " + str(err))
            log.info("CTCP Arg[1]: " + arg)

    def on_dccmsg(self, connection, event):
        data = event.arguments()[0]
        connection.file.write(data)
        connection.received_bytes = connection.received_bytes + len(data)
        connection.privmsg(struct.pack("!I", connection.received_bytes))

    def on_dcc_disconnect(self, connection, event):
        connection.file.close()
        log.info("Received file %s (%d bytes)." % (connection.filename, connection.received_bytes))
        connection.disconnect()
        if connection.is_search:
            self.process_search(connection.search)
        else:
            self.name_download(connection.search)
        self.queue_next()
                 
    def on_dccchat(self, connection, event):
        log.info("DCC Chat: " + event.arguments()[0])
        if len(event.arguments()) != 2:
            return
        args = event.arguments()[1].split()
        if len(args) == 4:
            try:
                address = ip_numstr_to_quad(args[2])
                port = int(args[3])
            except ValueError:
                return
            self.dcc_connect(address, port)

    def do_command(self, event, cmd):
        nick = nm_to_n(event.source())
        c = self.connection

        # cmd, arg = cmd.split(r" ", 1)
        splitcmd = cmd.split(r" ", 1)
        arg = None
        if len(splitcmd) > 1:
            cmd = splitcmd[0]
            arg = splitcmd[1]

        if cmd == "disconnect":
            self.disconnect()
        elif cmd == "die":
            self.die()
        elif cmd == "stats":
            for chname, chobj in self.channels.items():
                c.notice(nick, "--- Channel statistics ---")
                c.notice(nick, "Channel: " + chname)
                users = chobj.users()
                users.sort()
                c.notice(nick, "Users: " + ", ".join(users))
                opers = chobj.opers()
                opers.sort()
                c.notice(nick, "Opers: " + ", ".join(opers))
                voiced = chobj.voiced()
                voiced.sort()
                c.notice(nick, "Voiced: " + ", ".join(voiced))
        elif cmd == "get":
            if arg is None:
                c.notice(nick, "get expects an argument")
            else:
                self.search_for(arg)
        elif cmd == "dcc":
            dcc = self.dcc_listen()
            c.ctcp("DCC", nick, "CHAT chat %s %d" % (
                ip_quad_to_numstr(dcc.localaddress),
                dcc.localport))
        else:
            c.notice(nick, "Not understood: " + cmd)

    def search_for(self, search):
        searches.add(search)
        
        previous = "SearchBot_results_for_%s.txt.zip" % search.phrase
        if os.path.exists(previous):
            log.info("Using cached search: " + previous)
            search.filename = previous
            self.process_search(search)
        else:
            c = self.connection
            cmd = "@search %s" % search.phrase
            chname, chobj = self.channels.items()[0]
            log.info("Sending: '%s' to %s" % (cmd, chname))
            c.privmsg(chname, cmd)

    def process_search(self, search):
        with closing(ZipFile(open(search.filename, 'rb'))) as inf:
            name = inf.namelist()[0]
            log.info("Zipped: " + name)
            lines = inf.read(name).split("\n")[4:] # Trim header
            results = []
            for line in lines:
                parts = re.match(r"^!(?P<server>\S+)\s+(?P<file>.*?)  (.*)\s+(?P<size>\d+(.\d+)?)\s*(?P<unit>.?[Bb])", line)
                if parts is None:
                    log.error("Unknown Result Match: " + line)
                else:
                    src = DownloadSource(parts.group('server'),
                                                  parts.group('file'),
                                                  parts.group('size') + parts.group('unit'))
                    results.append(src)

        search.results = results

        source = searches.get_source(search)
        if source is None:
            log.error("No results for: " + search.phrase)
            self.queue_next()
        else:
            searches.link(source.filename, search)
            c = self.connection
            chname, chobj = self.channels.items()[0]
            log.info("Sending: '%s' to %s" % (source.req, chname))
            c.privmsg(chname, source.req)
            source.attempted = True

    def name_download(self, search):
        filename = "%s.html.rar" % (search.filetitle)
        if search.rename and filename != search.filename:
            log.info("Renaming %s to %s" % (search.filename, filename))
            os.rename(search.filename, filename)

    def start_queue(self):
        self.lock_check = PerpetualTimer(30.0, self.check_lock)
        self.lock_check.start()
        self.queue_next()

    def check_lock(self):
        """If it has been over a minute since the last queue entry, process one"""
        delta = time.time() - self.last_pop_time
        if delta > 60:
            log.info("Kicking queue after %d second wait" % delta)
            self.queue_next()

    def queue_next(self):
        self.last_pop_time = time.time()
        log.info("Popping Queue: " + str(len(search_queue)))
        if len(search_queue) > 0:
            self.search_for(search_queue.pop())
        else:
            log.info("Queue Empty")

import freebase

query = {
    "id" :   "/en/nebula_award",
    "type" : "/award/award",
    "category" : [{
      "name" : None,
      "nominees" : [{
        "award_nominee" : [{
          "name" : None,
        }],
        "nominated_for" : [{
          "name" : None,
        }],
        "limit" : 5000,
      }]
    }]
}

results = freebase.mqlread(query)

for category in results.category:
    for nominee in category.nominees:
        author = nominee.award_nominee[0].name
        title = nominee.nominated_for[0].name

        dbpath = glob.glob("%s*%s*" % (author, title))
        if len(dbpath) == 0:
            search_queue.append(Search(author, title))
        else:
            log.debug("Found Path: " + dbpath[0])

def main():
    import sys

    if len(sys.argv) != 4:
        log.info("Usage: testbot <server[:port]> <channel> <nickname>")
        sys.exit(1)

    s = sys.argv[1].split(":", 1)
    server = s[0]
    if len(s) == 2:
        try:
            port = int(s[1])
        except ValueError:
            log.error("Error: Erroneous port:" + s[1])
            sys.exit(1)
    else:
        port = 6667
    channel = sys.argv[2]
    nickname = sys.argv[3]

    bot = DownloadBot(channel, nickname, server, port)
    bot.start()

if __name__ == "__main__":
    main()

sys.exit(0)
