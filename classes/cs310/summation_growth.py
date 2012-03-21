#!/usr/bin/env python

import math, sys

# Compare the growth of n^2 versus sum(2^i * (n - i))

if len(sys.argv) > 1:
    n = int(sys.argv[1])
else:
    n = 100000

max = int(math.log(n, 2))
print "N = %d, max = %d" % (n, max)
sum = 0
for i in range(0, max):
    sum += pow(2, i) * (max - i)

print "n = %s; n^2 = %d; logn = %d; sum(2^i) = %d" % (n, pow(n, 2), max, sum)

max = int(n / 2)
for i in range(0, max):
    sum += 2 * i - 3

print "    n/2 = %d; sum(n/2) = %d" % (max, sum)
