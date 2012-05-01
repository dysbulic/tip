#!/usr/bin/python

import os
import re
import time
import curses
from stat import *
import xml.dom.ext.reader.Sax2

file = ""
width = 0

class filelist:
    def __init__(self, screen):
        self.screen = screen
        self.chdir(os.curdir)
        reader = xml.dom.ext.reader.Sax2.Reader()
        doc = reader.fromUri("file:config.xml")
        self.pattern = doc.getElementsByTagName("files")[0].getAttribute("pattern")

    def select(self, index):
        global width
        if index < 1 and self.curindex is not None and self.curindex != 1:
            index = 1
        elif(index > len(self.files)
             and self.curindex is not None
             and self.curindex != len(self.files)):
            index = len(self.files)
        elif index < 1 or index > len(self.files):
            return -1
        self.screen.erase()
        self.screen.border()
        maxx = self.screen.getmaxyx()[0] - 5
        maxy = self.screen.getmaxyx()[1]
        if index < self.topindex or index > self.topindex + maxx:
            self.topindex = index - maxx / 2
            if self.topindex < 1: self.topindex = 1
        width = len(str(len(self.files))) + 1
        format = "%%%dd: " % width
        for i, file in enumerate(self.files[self.topindex - 1:self.topindex + maxx]):
            fileindex = self.topindex + i
            colorpair = 0
            try:
                if S_ISDIR(os.stat(file).st_mode):
                    colorpair = 1
                elif re.search(self.pattern, file):
                    colorpair = 2
            except:
                pass
            color = curses.color_pair(colorpair)
            if fileindex == index:
                color = color | curses.A_REVERSE
            
            self.screen.addstr(i + 1, 1, format % fileindex)
            self.screen.addstr(i + 1, width + 3, file, color)
        self.screen.hline(maxx + 2, 1, '-', maxy - 2)
        #self.screen.addstr(maxx + 3, 1, str(dir(self.screen)), color | curses.A_BOLD)
        # self.screen.bkgdset(' ', curses.A_REVERSE)
        self.screen.refresh()
        self.curindex = index

    def move(self, delta):
        self.select(self.curindex + delta)

    def currentFile(self):
        return self.files[self.curindex - 1]

    def chdir(self, dir):
        os.chdir(dir)
        self.files = os.listdir(os.curdir)
        self.files.sort();
        self.files.insert(0, "..")
        self.topindex = 1
        self.select(1)

def listfiles(stdscr):
    global file
    curses.init_pair(1, curses.COLOR_YELLOW, curses.COLOR_BLACK)
    curses.init_pair(2, curses.COLOR_BLUE, curses.COLOR_BLACK)
    curses.init_pair(3, curses.COLOR_RED, curses.COLOR_BLACK)
    curses.curs_set(0)
    #stdscr.border(65, 66, 67, 68, 69, 70, 71, 72)
    #stdscr.border('|', '!', '-', '_', '+', '\\', '#', '/')
    stdscr.scrollok(1)
    stdscr.bkgdset(' ')
    #    stdscr.scroll()

    #stdscr.addstr(1, 1, str(dir(stdscr)), curses.color_pair(1))
    control = filelist(stdscr)
    #time.sleep(30.0)
    while True:
        char = stdscr.getch()
        if char == curses.KEY_UP: control.move(-1)
        elif char == curses.KEY_DOWN: control.move(1)
        elif char == curses.KEY_LEFT: control.chdir("..")
        elif char == curses.KEY_PPAGE: control.move(-(stdscr.getmaxyx()[0] - 5 + 1))
        elif char == curses.KEY_NPAGE: control.move(stdscr.getmaxyx()[0] - 5 + 1)
        elif char == ord('\n') or char == curses.KEY_RIGHT:
            file = control.currentFile()
            if S_ISDIR(os.stat(file).st_mode):
                control.chdir(file)
        elif char == ord('q'): break
        elif char == curses.KEY_HOME: stdscr.addstr(stdscr.getmaxyx()[0] - 2, 1, "home", curses.color_pair(1))
        else: stdscr.addstr(stdscr.getmaxyx()[0] - 2, 2, "Key Pressed: %s" % char, curses.color_pair(1))

try:
    curses.wrapper(listfiles)
    print file
except KeyboardInterrupt:
    print "Width: %d" % width
    pass
