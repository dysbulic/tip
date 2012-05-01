#!/usr/bin/env python

"""
Author: Will Holcomb <wholcomb@gmail.com>
Date: 25 June 2007

Simple program to rename a set of files based on the timestamp
in the EXIF information.

This is better done with the jhead program.
"""

import sys, os, glob

if len(sys.argv) <= 1:
    print __doc__
    sys.exit(-1)

def rename(filename):
    extension = os.path.splitext(filename)[1]
    image = open(filename)
    exifInfo = EXIF.process_file(image)
    image.close()
    if exifInfo.has_key('EXIF DateTimeOriginal'):
        newName = exifInfo['EXIF DateTimeOriginal'].values.replace(' ', '_')
        if os.path.exists(newName + extension):
            count = 1
            while(os.path.exists("%s.%s%s" % (newName, count, extension))):
                  count += 1
            newName = "%s.%s" % (newName, count)
        newName += extension
        print "%s => %s" % (filename, newName)
        os.rename(filename, newName)
    else:
        print "Error: %s has no EXIF timestamp" % filename

try:
    import EXIF
    for file in sys.argv[1:]:
        if not os.path.isdir(file):
            rename(file)
        else:
            for file in glob.glob("%s/*.jpg" % file):
                rename(file)
except ImportError:
    print "Requires EXIF Library from: http://home.cfl.rr.com/genecash/digital_camera/"
