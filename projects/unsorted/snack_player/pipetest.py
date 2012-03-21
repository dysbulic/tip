#!/usr/bin/python

import os
import random
import subprocess

max = random.randint(0, 100)
print "Reduced range to %d: %d" % (max, reduce(lambda x,y: x + y, range(1, max)))

users = os.popen("head -n10 /etc/passwd")
print "Users:"
print users.readlines()
users.close()

testout = os.popen("cat > test.out", "w")
testout.write("This is a test\n")
testout.close()
print "Test Output:"
os.system("cat test.out")
