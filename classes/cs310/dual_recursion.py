#!/usr/bin/env python

# Author: Will Holcomb <wholcomb@gmail.com>
# Date: 21 January 2008
#
# Testing equivalances for recursive expansion and logarithmic
# approximation for CS-310 homework

import math, sys, random

def recurse(x, y, iteration = 1):
    value = 1
    if x > 1 and y > 1: value = recurse(float(x) / 2, y, iteration + 1) + recurse(x, float(y) / 2, iteration + 1)
    # print "%2d: [%2.2f,%2.2f]: %2d" % (iteration, x, y, value)
    return value

if len(sys.argv) > 1:
    count = int(sys.argv[1])
else:
    count = random.Random().randint(10, 100)

for i in xrange(1, count + 1):
    print "%2d: %2d %2d" % (i, recurse(i, 2), math.ceil(math.log(i, 2)) + 1)

