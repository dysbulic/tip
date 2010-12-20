#!/usr/bin/python

"""
A Fibonacci series using continuations
From: http://www.intertwingly.net/blog/2005/04/13/Continuations-for-Curmudgeons
"""
import sys

def fibonacci():
      yield 0
      i, j = 0, 1
      while True:
          yield j
          i, j = j, i+j

max = 10
if len(sys.argv) > 1:
    max = int(sys.argv[1])

for n in fibonacci():
      print n, # adds a space before the next print unless there's a sys.stdout.write
      if n > max: break
      sys.stdout.write(", ")
print

fib = fibonacci()
for i in range(1, max + 1):
    print "%2d: %s" % (i, fib.next())
