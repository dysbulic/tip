#!/usr/bin/python

import sys, subprocess, random

result = "test"

prog = """import sys
i = 0
while i < %d:
       sys.stdout.write(sys.stdin.readline());
       sys.stdout.flush();
       i = i + 1""" % random.Random().randint(3, 7)

print prog

proc = subprocess.Popen([sys.executable, "-c", prog],
                        stdin=subprocess.PIPE,
                        stderr=subprocess.PIPE,
                        stdout=subprocess.PIPE)
i = 0
while proc.poll() is None:
    i = i + 1
    string = result + " " + str(i)
    proc.stdin.write(string + "\n")
    proc.stdin.flush()
    print("Does \"%s\" = \"%s\" [%s]"
          % (string, proc.stdout.readline().strip(), str(proc.returncode)))
print "Exited with status: %d" % proc.poll()
