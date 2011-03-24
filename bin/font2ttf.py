#!/usr/bin/env python

import fontforge
import os, sys

for arg in sys.argv[1:]:
    if arg.startswith('*'): # Skip unexpanded wildcards passed from command line
        continue
    font = fontforge.open(arg)
    font.generate(os.path.splitext(arg)[0] + '.ttf')
