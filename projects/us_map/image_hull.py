#!/opt/local/bin/python

import sys, os
import math
import getopt
import polygon
from PIL import Image

# Author: Will Holcomb <wholcomb@gmail.com>
# Date: August 2006

class BadImageException(Exception):
    def __init__(self, value):
        self.value = value
    def __str__(self):
        return repr(self.value)

def imageHull(image):
    """Computes the convex hull for an image with an alpha channel."""
    if isinstance(image, basestring):
        image = Image.open(image)

    if image.mode is not "RGBA":
        raise BadImageException("Format Error: '%s' is %s; RGBA expected" % (image, image.mode))

    edgePoints = []
    current = [image.size[0], -1]
    for pixel in image.getdata():
        if current[0] == image.size[0]:
            edgeFound = False
            previousSolid = False
            current[0] = 0
            current[1] += 1

        if((pixel[3] is not 0 and not edgeFound) or # left side
           (pixel[3] is 0 and previousSolid)):      # right side
            edgeFound = True
            edgePoints.append(current[:])
        previousSolid = (pixel[3] is not 0)
        current[0] += 1
    upper, lower =  polygon.convexHulls(edgePoints)
    upper.extend(lower)
    return upper

def trimImage(filename, newFilename = None):
    image = Image.open(filename)
    extents = polygon.boundingRectangle(imageHull(image))

    if newFilename is None:
        file, ext = os.path.splitext(filename)
        newFilename = file + ".trimmed" + ext
    image.crop(extents).save(newFilename)
    return extents, newFilename

#for filename in sys.argv[1:]:
#    hull = imageHull(filename)
#    print "%s:" % filename
#    print hull
