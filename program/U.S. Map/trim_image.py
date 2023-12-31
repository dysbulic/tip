#!/usr/bin/env python

import sys, os
from PIL import Image

threshold = 249
pad = 20
max_space = 100

if len(sys.argv[1:]) is 0:
    print "Usage: [filename]*"
    print " Min Crop: %dpx / Threshold: %d" % (pad, threshold)

for filename in sys.argv[1:]:
    image = Image.open(filename)

    print "Processing: %s (%s) [%d x %d]" % (filename, image.mode, image.size[0], image.size[1])

    currentX = 0
    currentY = 0

    # y is monotonically increasing and x is not
    extents = [image.size[0], None, 0, 0]

    change = False
    for pixel in image.getdata():
        if (currentX > pad and currentX + pad < image.size[0] and
            currentY > pad and currentY + pad < image.size[1] and
            (currentX < extents[0] or currentX > extents[2] or currentY > extents[3])):
            if ((image.mode is "RGBA" and pixel[3] is not 0) or
                (image.mode is "L" and pixel < threshold) or
                (image.mode is "RGB" and (pixel[0] < threshold and
                                          pixel[1] < threshold and
                                          pixel[2] < threshold))):
                change = True

                if extents[0] > currentX: extents[0] = currentX
                if extents[1] is None: extents[1] = currentY
                if currentX > extents[2] and currentX - extents[2] < max_space:
                    # print "%s: From: <%d, %d> >> To: <%d, %d> (%d) = [%d]" % (image.mode, extents[2], extents[3], currentX, currentY, currentX - extents[2], pixel)
                    extents[2] = currentX
                if currentY - extents[3] < max_space:
                    # print "<%d,%d> (%d) : %d" % (currentX, currentY, currentY - extents[3], pixel)
                    extents[3] = currentY
        currentX += 1
        if currentX == image.size[0]:
            currentX = 0
            currentY += 1
    extents[2] += 1
    extents[3] += 1

    if not change:
        print "No cropping necessary"
    else:
        image = image.crop(extents)

    file, ext = os.path.splitext(filename)
    newfilename = file + ".trimmed" + ext
    print "(%d,%d) / (%d,%d): %s => %s" % (extents[0], extents[1], extents[2], extents[3], filename, newfilename)
    image.save(newfilename)
