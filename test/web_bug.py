#!/usr/bin/env python

# Attempt to write a transparent gif

"""Creates a 1px x 1px transparent web bug image
Usage: web_bug.py <output file>"""

import os, sys
from PIL import Image

if len(sys.argv) <= 1:
    print __doc__;
    sys.exit(0)
if os.path.exists(sys.argv[1]):
    print "Error: '%s' already exists" % sys.argv[1]
    sys.exit(0)

image = Image.new("L", (1, 1), ("Red"))
image.save(sys.argv[1],  transparency = 0)
