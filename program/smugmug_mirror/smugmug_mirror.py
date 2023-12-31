#!/usr/bin/python

import sys, re, os
import urllib, urllib2
import xml.dom.minidom
from xml.dom.minidom import Node

if len(sys.argv) <= 1:
    print "Usage: <RSS URL>"
    sys.exit(1)

url = sys.argv[1]
opener = urllib2.build_opener()

try:
    page = opener.open(url).read()
except urllib2.URLError, details:
    print "Error (%s): %s" % (url, details)
    sys.exit(-1)

doc = xml.dom.minidom.parseString(page)

fileExtRegex = re.compile("^(.*)/([^/]*)-Th((?:-1)?)\.([^\.]*)$")

outdir = (doc.getElementsByTagName("category")[0].childNodes[0].data + " > " +
          doc.getElementsByTagName("title")[0].childNodes[0].data)
print "Outputting to %s" % (outdir)
os.mkdir(outdir)
os.chdir(outdir)

def getItem(node, index):
    title = node.getElementsByTagName("title")[0].childNodes[0].data.strip()
    title = "%03d - %s" % (index, title)
    image = node.getElementsByTagName("guid")[0].childNodes[0].data
    imageMatch = fileExtRegex.search(image)
    if imageMatch is None:
        print "Didn't Match: %s (%s)" % (image, title)
    else:
        image = opener.open("%s/%s-O%s.%s" % (imageMatch.group(1), imageMatch.group(2),
                                              imageMatch.group(3), imageMatch.group(4)))
        outfile = "%s.%s" % (title, imageMatch.group(4))
        index = 1
        while os.path.exists(outfile):
            outfile = "%s.%s.%s" % (title, index, imageMatch.group(4))
            index += 1
        print "Saving to %s.%s -> %s" % (imageMatch.group(2), imageMatch.group(4), outfile)
        imageFile = open(outfile, 'w')
        imageFile.write(image.read())
        image.close()
        imageFile.close()
        
items = doc.getElementsByTagName("item")
index = 0
while index < items.length:
    getItem(items.item(index), items.length - index)
    index += 1
