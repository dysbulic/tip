#!/usr/bin/python

import os
import re

from snack import *
from stat import *
from rhpl.translate import _, N_
import locale
locale.setlocale(locale.LC_ALL, "")

class FileWindow:
    def __call__(self, screen):
        g = GridFormHelp(screen, _("File Selection"), "num", 1, 2)

        l = Listbox(screen.height - 10, scroll = 1, returnExit = 1)
        g.add(l, 0, 0, growy = 1, growx = 1)

        bb = ButtonBar(screen,  [ #_("OK"),
            _("Cancel")])
        #g.add(bb, 0, 1, growx = 1)

        files = [os.pardir];
        for file in os.listdir(os.curdir):
            files.append(file)
        files.sort();

        count = 0
        for file in files:
            count = count + 1
            l.append(("%s: %s") % (count, file), file)
        
        rc = g.runOnce()
        button = bb.buttonPressed(rc)

        if button == "cancel":
            return -1

        return l.current()

screen = SnackScreen()
DONE = 0
while not DONE:
    newfile = FileWindow()(screen)
    if newfile == -1:
        DONE = 1
    elif S_ISDIR(os.stat(newfile).st_mode):
        os.chdir(newfile)
    else:
        DONE = 1
    
screen.finish()

if newfile != -1:
    print newfile
