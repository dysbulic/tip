#!/usr/bin/env python

"""
Converts an image to a given opacity.
Usage: set_opacity.py <input image file> <opacity> <output file name>
Example: set_opacity.py map.png .5 map-o.png
"""

import Image, ImageEnhance
import sys

if len(sys.argv) <= 3:
    print __doc__
    sys.exit(-1)

img = Image.open(sys.argv[1])
if img.mode != 'RGBA':
   img = img.convert('RGBA')
alpha = img.split()[3]
alpha = ImageEnhance.Brightness(alpha).enhance(float(sys.argv[2]))
img.putalpha(alpha)
img.save(sys.argv[3])