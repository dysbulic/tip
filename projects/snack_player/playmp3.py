#!/usr/bin/python

import sys
import re
import os
import time
import tempfile
import popen2
from stat import *
#from xml.dom.ext.reader import Reader
import xml.dom.ext.reader.Sax2
#import getopt

#opts, kbdtype = getopt.getopt(sys.argv[1:],
#                              "d:h", ["help"])

reader = xml.dom.ext.reader.Sax2.Reader()

doc = reader.fromUri("file:config.xml")
pattern = doc.getElementsByTagName("files")[0].getAttribute("pattern")

#try:
#    while sys.stdin:
#        print sys.stdin.readline()
#except KeyboardInterrupt:
#    print "closed\n"

playlistfd, playlist = tempfile.mkstemp()
count = 0
for dir in sys.argv[1:]:
    if S_ISDIR(os.stat(dir).st_mode):
        for file in os.listdir(dir):
            if re.search(pattern, file):
                os.write(playlistfd, "%s/%s\n" % (dir, file))
                count = count + 1
                break
    else:
        print "\"%s\" is not a directory" % dir
os.fdopen(playlistfd).close()

if count > 0:
    print "Playing: " + playlist
    player = popen2.Popen4("mplayer -slave -playlist \"%s\" -really-quiet" % playlist)
    print "Gettings percent"
    player.tochild.write("get_percent_pos\n")
    print "Sleeping"
    time.sleep(5)
    print "Gettings percent"
    player.tochild.write("get_percent_pos\n")
    print "Quitting"
    player.tochild.write("quit\n")
    status = player.wait()
    print "Exiting with: %s" % status
    print player.fromchild.readlines()
