#!/usr/bin/python
# -*- coding: UTF-8 -*-

import subprocess
import random
import re
import os
import time
import select

calc = subprocess.Popen("dc", stdin=subprocess.PIPE,
                              stdout=subprocess.PIPE,
                              stderr=subprocess.STDOUT)
max = random.Random().randint(10, 100)
for value in range(1, max):
    calc.stdin.write("%d\n" % value)
    if value > 1:
        calc.stdin.write("*\n")
calc.stdin.write("p\n")

select.select([calc.stdout.fileno()], [], [])
time.sleep(.1)

string = os.read(calc.stdout.fileno(), 500)

print "String: ", string
dcproduct, repcount = re.subn("\\\|\\s", "", string)
dcproduct = int(dcproduct)
pyproduct = reduce(lambda x,y: x * y, range(1, max))
if dcproduct == pyproduct:
    print "Π(1,%d):" % (max - 1)
else:
    print "Products don't match: n = %d" % (max - 1)
print "  %d" % dcproduct
print "  %d" % pyproduct

calc.stdin.write("q\n")
status = calc.wait()
print "Exited with: %d" % status
