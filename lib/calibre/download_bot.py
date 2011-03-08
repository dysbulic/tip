#! /usr/bin/env python
#
# Example program using ircbot.py.
#
# Joel Rosdahl <joel@rosdahl.net>

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
from irclib import nm_to_n, nm_to_h, irc_lower, ip_numstr_to_quad, ip_quad_to_numstr

import os
import re
import struct
import logging
import zipfile
from zipfile import ZipFile, ZIP_STORED, ZIP_DEFLATED
from contextlib import closing

logging.basicConfig()
log = logging.getLogger(__name__)
log.setLevel(logging.INFO)

class TestBot(SingleServerIRCBot):
    def __init__(self, channel, nickname, server, port=6667):
        SingleServerIRCBot.__init__(self, [(server, port)], nickname, nickname)
        self.channel = channel

    def on_nicknameinuse(self, c, e):
        newnick = c.get_nickname() + "_"
        log.info("Nick Collision: " + newnick)
        c.nick(newnick)

    def on_welcome(self, c, e):
        log.info("Joining: " + self.channel)
        c.join(self.channel)

    def on_privmsg(self, c, e):
        log.info("Privmsg: " + e.arguments()[0])
        self.do_command(e, e.arguments()[0])

    def on_pubmsg(self, c, e):
        log.info("Pubmsg: " + e.arguments()[0])
        a = e.arguments()[0].split(":", 1)
        if len(a) > 1 and irc_lower(a[0]) == irc_lower(self.connection.get_nickname()):
            self.do_command(e, a[1].strip())
        return

    def on_ctcp(self, connection, event):
        log.info("CTCP Arg[0]: " + event.arguments()[0])
        if event.arguments()[0] != "DCC" or len(event.arguments()) < 2:
            return
        args = re.match(r"^(?P<cmd>\w+)\s+\"(?P<filename>.*)\"\s+(?P<ip>\d+)\s+(?P<port>\d+)\s+(?P<unkn>\d+)\s*$",
                        event.arguments()[1])
        if args is None or args.group('cmd') != "SEND":
            return
        peeraddress = ip_numstr_to_quad(args.group('ip'))
        peerport = int(args.group('port'))
        dcc = self.dcc_connect(peeraddress, peerport, "raw")
        dcc.filename = os.path.basename(args.group('filename'))

        search = re.match("^SearchBot_results_for_(?P<name>.*).txt.zip", dcc.filename)
        if search is not None:
            dcc.is_search = True
            dcc.search_str = search.group('name')
            log.info("Search Result: " + dcc.search_str)

        if os.path.exists(dcc.filename):
            count = 1
            basename, extension = os.path.splitext(dcc.filename)
            while os.path.exists(dcc.filename):
                dcc.filename = "%s.%d%s" % (basename, count, extension)
                count = count + 1
        dcc.file = open(dcc.filename, "w")
        dcc.received_bytes = 0

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
            self.from_search(connection.filename)
                 
    #def on_dccmsg(self, c, e):
    #    c.privmsg("You said: " + e.arguments()[0])

    def on_dccchat(self, c, e):
        log.info("DCC Chat: " + e.arguments()[0])
        if len(e.arguments()) != 2:
            return
        args = e.arguments()[1].split()
        if len(args) == 4:
            try:
                address = ip_numstr_to_quad(args[2])
                port = int(args[3])
            except ValueError:
                return
            self.dcc_connect(address, port)

    def do_command(self, e, cmd):
        nick = nm_to_n(e.source())
        c = self.connection

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
            #self.search_for("The Lifecycle of Software Objects")
            self.search_for("Echo Jack McDevitt")
        elif cmd == "dcc":
            dcc = self.dcc_listen()
            c.ctcp("DCC", nick, "CHAT chat %s %d" % (
                ip_quad_to_numstr(dcc.localaddress),
                dcc.localport))
        else:
            c.notice(nick, "Not understood: " + cmd)

    def search_for(self, search_str):
        c = self.connection
        cmd = "@search %s" % search_str
        chname, chobj = self.channels.items()[0]
        log.info("Sending: '%s' to %s" % (cmd, chname))
        c.privmsg(chname, cmd)

    def from_search(self, filename):
        with closing(ZipFile(open(filename, 'rb'))) as inf:
            name = inf.namelist()[0]
            log.info("Zipped: " + name)
            lines = inf.read(name).split("\n")[4:] # Trim header
            results = []
            for line in lines:
                result = line.split("  ")[0]
                match = re.search(r"^!(\S+).*(\[[^\]]*htm[^\]]*\]|\([^\)]*htm[^\)]*\)|\.htm)", result)
                if match is not None:
                    results.append(result)
                    server = match.group(1)

        c = self.connection
        chname, chobj = self.channels.items()[0]
        log.info("Sending: '%s' to %s" % (results[0], chname))
        c.privmsg(chname, results[0])

def main():
    import sys

    if len(sys.argv) != 4:
        print "Usage: testbot <server[:port]> <channel> <nickname>"
        sys.exit(1)

    s = sys.argv[1].split(":", 1)
    server = s[0]
    if len(s) == 2:
        try:
            port = int(s[1])
        except ValueError:
            print "Error: Erroneous port."
            sys.exit(1)
    else:
        port = 6667
    channel = sys.argv[2]
    nickname = sys.argv[3]

    bot = TestBot(channel, nickname, server, port)
    bot.start()

if __name__ == "__main__":
    main()
