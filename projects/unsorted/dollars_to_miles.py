#!/usr/bin/env python

"""
Author: Will Holcomb <will@technoanarchy.org>
Date: May 2009
License: Public Domain

Usage: dollars_to_miles.py <dollar amount>

Converts from dollars to miles. Formatted for large numbers.
"""

import sys, locale

billAmount = 1

if len(sys.argv) == 1:
    print __doc__
    print "Output as height of a stacks of $%s bills" % billAmount
    sys.exit(-1)


locale.setlocale(locale.LC_ALL, "")

def printAmount(amount, unit):
    print "%s%s tall stack of $%d bills" %  (locale.format("%.2f", amount, True), unit, billAmount)

amount = float(sys.argv[1])
print "$%s" % amount

amount = amount / billAmount
print "%s $%d bills" % (locale.format("%.0f", amount, True), billAmount)

# http://hypertextbook.com/facts/1999/DeneneWilliams.shtml -- $1 = 6.6294cm x 15.5956cm x 0.010922cm
amount = amount * .010922
printAmount(amount, "cm")

amount = amount / 2.54
printAmount(amount, "\"")

amount = amount / 12
printAmount(amount, "\'")

amount = amount / 5280
printAmount(amount, "mi")

