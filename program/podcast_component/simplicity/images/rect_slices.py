#!/usr/bin/python

# Generates and slices an image to css sliding windows

import os, sys

sourceFile = "round_rectangle.svg"
fullpath = os.path.abspath(os.path.dirname(sys.argv[0]))
#args = "-w 45 -h 45 -cssAlternate file://%s/%s" % (fullpath, cssFile)

command = "batik-rasterizer.sh -w 500 -h 3 -a 0,0,500,3 -d top.png %s" % (sourceFile)
print "Running: %s" % command
os.system(command)

command = "batik-rasterizer.sh -w 500 -h 3 -a 0,7,500,3 -d bottom.png %s" % (sourceFile)
print "Running: %s" % command
os.system(command)

command = "batik-rasterizer.sh -w 500 -h 1 -a 0,4,500,1 -d mid.png %s" % (sourceFile)
print "Running: %s" % command
os.system(command)
