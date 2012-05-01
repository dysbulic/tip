#!/usr/bin/python
# -*- coding: UTF-8 -*-

import subprocess
import random
import select
import re
import fcntl
import os
import time
import sys

commands = {"?" : "list commands",
            "1" : "loadfile test1.mp3",
            "2" : "loadfile test2.mp3",
            ">" : "seek 5",
            "<" : "seek -5",
            "q" : "quit", 
            "p" : "pause",
            "m" : "mute",
            "%" : "get_percent_pos",
            "l" : "get_time_length",
            "t" : "loadlist test.m3u"}

line = None
player = None
while line != "q":
    if line == "?":
        for key, command in commands.iteritems():
            print "%3s: %s" % (key, command)
    elif commands.has_key(line):
        if player is None or player.poll() != None:
            print "Creating player process"
            player = subprocess.Popen(["mplayer", "-slave", "-quiet", "test1.mp3"],
                                      stdin=subprocess.PIPE,
                                      stdout=subprocess.PIPE,
                                      stderr=subprocess.STDOUT)
            fcntl.fcntl(player.stdout, fcntl.F_SETFL, os.O_NONBLOCK)
        player.stdin.write(commands[line] + "\n")
        player.stdin.flush();
        time.sleep(.1)
        #if len(select.select([player.stdout.fileno()], [], [], 5)[0]) > 0:
        #    string = os.read(player.stdout.fileno(), 500)
        string = None
        try:
            for string in player.stdout:
                if not string: break
        except StandardError:
            pass
        if not string:
            print "No output"
        else:
            print "Out: ", string.strip()
    elif player is not None and line == "":
        #if len(select.select([player.stdout.fileno()], [], [], 5)[0]) > 0:
        #    string = os.read(player.stdout.fileno(), 500)
        #    print "Out: ", string.strip()
        pass
    elif line is not None:
        print "No command: ", line
    line = sys.stdin.readline().strip()

if player is not None and player.poll() is None:
    player.stdin.write("quit\n")
    player.stdin.flush();
status = player.wait()
print "Exited with: %d" % status
