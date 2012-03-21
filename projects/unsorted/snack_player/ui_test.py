#!/usr/bin/python

from snack import *
from rhpl.translate import _, N_
import locale
locale.setlocale(locale.LC_ALL, "")

class NumberWindow:
    def __call__(self, screen):
        bb = ButtonBar(screen, [_("OK"), _("Cancel")])
        t = TextboxReflowed(40, _("Select the appropriate number"))
        l = Listbox(5, scroll = 0, returnExit = 0)

        capitalize = Checkbox(_("Capitalize?"), isOn = 1)

        nums = [ "one", "two", "three", "four", "five" ]

        for index, num in enumerate(nums):
            l.append("%s" % (index + 1), num)

        l.setCurrent("three")

        g = GridFormHelp(screen, _("Number Selection"), "num", 1, 4)
        g.add(t, 0, 0)
        g.add(l, 0, 1, padding = (0, 1, 0, 1))
        g.add(capitalize, 0, 2, padding = (0, 0, 0, 1))
        g.add(bb, 0, 3, growx = 1)

        rc = g.runOnce()
        button = bb.buttonPressed(rc)

        if button == "cancel":
            return -1

        value = l.current()
        if capitalize.selected():
            value = string.capitalize(value);

        return value
                
#import signal
#import sys
#import getopt

#if __name__ == "__main__":
#    print "in main"
#    signal.signal (signal.SIGINT, signal.SIG_DFL)

#opts, kbdtype = getopt.getopt(sys.argv[1:],
#                              "d:h",
#                              ["noui", "text", "help"])

screen = SnackScreen()
value = NumberWindow()(screen)
screen.finish()
print value
