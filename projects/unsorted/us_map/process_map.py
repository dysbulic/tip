#!/usr/bin/env python

####
# 02/2006 Will Holcomb <wholcomb@gmail.com>
# 
# This library is free software; you can redistribute it and/or
# modify it under the terms of the GNU Lesser General Public
# License as published by the Free Software Foundation; either
# version 2.1 of the License, or (at your option) any later version.
# 
# This library is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
# Lesser General Public License for more details.
"""
Takes a svg map which allows for hiding everything but a certain state
and generates a HTML image map from it.
"""

import sys, os
from PIL import Image
import polygon
import image_hull

import xml.dom.minidom
from xml.dom.minidom import Node

if len(sys.argv) <= 1:
    print __doc__
    sys.exit(1)

mapSVG = sys.argv[1]
width = 750
mainID = "us-map"
imgClass = "map-image"
mapName = "map"
coverID = "map-cover"
linkTarget = "http://%s.mpp.org"
backID = "map-back"

file, ext = os.path.splitext(mapSVG)
HTMLFile = file + ".html"
if os.path.exists(HTMLFile):
    print "Error: HTML Map '%s' Exists" % HTMLFile
    sys.exit(-1)
outHTML = open(HTMLFile, "w")

CSSFile = file + ".css"
if os.path.exists(CSSFile):
    print "Error: HTML CSS '%s' Exists" % CSSFile
    sys.exit(-1)
outCSS = open(CSSFile, "w")

subdir = file + "_images"
clearCoverFile = "%s/web_bug.gif" % subdir

states = { 'RI' : 'Rhode Island',
           'AL' : 'Alabama',        'AK' : 'Alaska',
	   'AZ' : 'Arizona',        'AR' : 'Arkansas',
	   'CA' : 'California',     'CO' : 'Colorado',
	   'CT' : 'Connecticut',    'DE' : 'Delaware',
	   'FL' : 'Florida',        'GA' : 'Georgia',
	   'HI' : 'Hawaii',         'ID' : 'Idaho',
           'IL' : 'Illinois',       'IN' : 'Indiana',
	   'IA' : 'Iowa',	    'KS' : 'Kansas',
	   'KY' : 'Kentucky',       'LA' : 'Louisiana',
	   'ME' : 'Maine',          'MD' : 'Maryland',
	   'MA' : 'Massachusetts',  'MI' : 'Michigan',
	   'MN' : 'Minnesota',      'MS' : 'Mississippi',
	   'MO' : 'Missouri',       'MT' : 'Montana',
	   'NE' : 'Nebraska',       'NV' : 'Nevada',
	   'NH' : 'New Hampshire',  'NJ' : 'New Jersey',
	   'NM' : 'New Mexico',     'NY' : 'New York',
	   'NC' : 'North Carolina', 'ND' : 'North Dakota',
	   'OH' : 'Ohio', 	    'OK' : 'Oklahoma',
	   'OR' : 'Oregon', 	    'PA' : 'Pennsylvania',
           'SC' : 'South Carolina',
	   'SD' : 'South Dakota',   'TN' : 'Tennessee',
	   'TX' : 'Texas', 	    'UT' : 'Utah',
	   'VT' : 'Vermont',        'VA' : 'Virginia',
	   'WA' : 'Washington',     'DC' : 'District of Columbia',
	   'WV' : 'West Virginia',  'WI' : 'Wisconsin',
	   'WY' : 'Wyoming' }

doc = xml.dom.minidom.parse(mapSVG)
for node in doc.documentElement.childNodes:
    if node.nodeType == Node.ELEMENT_NODE:
        node.setIdAttribute('id')
head = doc.getElementById("States")

viewBox = doc.documentElement.getAttribute("viewBox").split()
height = width * float(viewBox[3]) / float(viewBox[2])
batikCommand = "bash -cl \"batik-rasterizer -onload -w %s -h %s -d '%%s' '%%s'\"" % (width, height)

hulls = {}
labels = {}
order = []
imagesHTML = ""

# To properly handle SVG rendering order for overlapping elements
for index in range(head.childNodes.length, 0, -1):
    child = head.childNodes[index - 1]
    if not child.nodeType == Node.ELEMENT_NODE:
        continue
    
    abbr = child.getAttribute("id")
    state = states[abbr]
    
    print "Preparing %s (%s):" % (state, abbr)
    # I can't figure out to pass in query parameters
    # url = "file:%s?show=%s" % (mapSVG, abbr)

    id = state.lower()
    id = id.replace('.', '')
    id = id.replace(',', '')
    id = id.replace(' ', '-')

    # First the unlabeled state is used to generate a bounding box that will be
    # used to trigger the hover
    
    outFile = "%s/%s.unlabeled.png" % (subdir, id)
    if not os.path.exists(outFile):
        link = "%s.svg" % abbr
        os.symlink(mapSVG, link)
        os.system(batikCommand % (outFile, link))
        os.unlink(link)
    hulls[abbr] = image_hull.imageHull(outFile)
    order.append(abbr)
    # os.unlink(outFile)

    # Next do the same with the label 

    outFile = "%s/%s.label.png" % (subdir, id)
    if not os.path.exists(outFile):
        link = "%s_label.svg" % abbr
        os.symlink(mapSVG, link)
        os.system(batikCommand % (outFile, link))
        os.unlink(link)
    labels[abbr] = image_hull.imageHull(outFile)

    # Finally render the actual image that will be used

    outFile = "%s/%s.png" % (subdir, id)
    if not os.path.exists(outFile):
        link = "%s_labeled.svg" % abbr
        os.symlink(mapSVG, link)
        os.system(batikCommand % (outFile, link))
        os.unlink(link)
    extents, newFilename = image_hull.trimImage(outFile)
    # os.unlink(outFile)

    id += "-img"
    outCSS.write('#%s { left: %dpx; top: %dpx; }\n' % (id, extents[0], extents[1]))

    linkDestination = linkTarget % abbr.lower()

    imagesHTML += '  <a href="%s">\n' % linkDestination
    imagesHTML += '    <img id="%s" class="%s" alt="%s"\n' % (id, imgClass, state)
    imagesHTML += '         src="%s" />\n' % (newFilename)
    imagesHTML += '  </a>\n'

outHTML.write('<html>\n<head>\n')
outHTML.write('  <title>HTML Map Generated from %s</title>\n' % mapSVG)
outHTML.write('  <link type="text/css" rel="stylesheet" href="%s" />\n' % CSSFile)
outHTML.write('  <!--[if lt IE 7]>\n')
outHTML.write('    <style type="text/css"> img { behavior: url("../ie_png_behavior.htc"); } </style>\n')
outHTML.write('  <![endif]-->\n')
outHTML.write('  <script type="text/javascript" src="../javascript_compatability/compatability.js"></script>\n')
outHTML.write('  <script type="text/javascript" src="hover_elements.js"></script>\n')
outHTML.write('</head>\n<body>\n')
outHTML.write('<map id="%s" name="%s">\n' % (mapName, mapName))

# The problem with the convex hulls is they are often overlapping
# I want to order their precedence so as to minimize the overall
#  percentage of elements that are covered by other elements
# To this end I will pick the element with the highest percentage
#  covered each iteration to give precedence to

#polygon.sortByPercentageIntersection(polygons)

#for abbr, hull in hulls.items():
for abbr in order:
    hull = hulls[abbr]
    label = labels[abbr]
    linkDestination = linkTarget % abbr.lower()

    state = states[abbr]
    id = state.lower()
    id = id.replace('.', '')
    id = id.replace(',', '')
    id = id.replace(' ', '-')

    outHTML.write('  <area id="%s" shape="poly" title="%s" alt="%s"\n' % (id, state, state))
    outHTML.write('        href="%s"\n' % linkDestination)
    outHTML.write('        coords="%s"\n' % (" ".join(["%d,%d" % tuple(point) for point in hull])))
    outHTML.write('        onmouseover="regionover(event)" onmouseout="regionout(event)"\n')
    outHTML.write('        onclick="regionclick(event)"/>\n')

    outHTML.write('  <area id="%s" shape="poly" title="%s" alt="%s"\n' % (id, state, state))
    outHTML.write('        href="%s"\n' % linkDestination)
    outHTML.write('        coords="%s"\n' % (" ".join(["%d,%d" % tuple(point) for point in label])))
    outHTML.write('        onmouseover="regionover(event)" onmouseout="regionout(event)"\n')
    outHTML.write('        onclick="regionclick(event)"/>\n')

outHTML.write('</map>\n')
outHTML.write('<h1 id="display" style="text-align: center; height: 1.5em;"></h1>\n')
outHTML.write('<script type="text/javascript">\n')
outHTML.write('  var nameDisplay = document.createTextNode("");\n')
outHTML.write('  document.getElementById("display").appendChild(nameDisplay);\n')
outHTML.write('</script>\n')
outHTML.write('<div id="%s">\n' % mainID)

if not os.path.exists(subdir):
    os.mkdir(subdir)
outFile = "%s/%s.png" % (subdir, file)
if not os.path.exists(outFile):
    os.system(batikCommand % (outFile, mapSVG))
image = Image.open(outFile)

outHTML.write('  <img id="%s" usemap="#%s" alt=""\n' % (backID, mapName))
outHTML.write('       src="%s" />\n' % outFile) 
outCSS.write('#%s, #%s, .%s { border: none; }\n' % (backID, coverID, imgClass))
outCSS.write('#%s { position: absolute; top: 0px; left: 0px; }\n' % backID)
outCSS.write("#%s { position: relative; width: %dpx; height: %dpx; margin: 0 auto; }\n"
             % (mainID, image.size[0], image.size[1]))
outCSS.write(".%s { position: absolute; display: none; }\n" % imgClass)

outHTML.write(imagesHTML)

image = Image.new("P", (1, 1), (0))
image.save(clearCoverFile, transparency = 0)

outHTML.write('  <img id="%s" usemap="#%s" alt=""\n' % (coverID, mapName))
outHTML.write('       src="%s" />\n' % clearCoverFile)
outCSS.write("#%s { position: absolute; top: 0; left: 0; width: 100%%; height: 100%%; border: none; }"
             % coverID)

outHTML.write('</div>\n')

outHTML.write('</body></html>')
outHTML.close()
outCSS.close()

