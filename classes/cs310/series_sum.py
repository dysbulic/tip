#!/usr/bin/env python

# Author: Will Holcomb <wholcomb@gmail.com>
# Date: 21 January 2008
#
# Testing Gauss's summation

import math, sys, random

if len(sys.argv) > 1:
    n = int(sys.argv[1])
else:
    n = random.Random().randint(10, 100)
    
print "%d: %d == %d" % (n, sum(xrange(1, n + 1)), (n + 1) * (float(n) / 2))
