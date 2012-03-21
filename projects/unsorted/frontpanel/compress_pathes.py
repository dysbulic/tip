#!/usr/bin/python

import sys, re
import xml.dom.minidom
from xml.dom.minidom import Node

if len(sys.argv) <= 1:
    print 'The schematic diagram program, kicad, exports svgs with thousands of paths of the form:'
    print '<path d="M7456 7540 L7456 7480" />'
    print '<path d="M7456 7480 L7483 7507" />'
    print '<path d="M7483 7507 L7510 7480" />'
    print '<path d="M7510 7480 L7510 7540" />'
    print 'Those could be represented by a single path:'
    print '<path d="M7456 7540 L7456 7480 L7483 7507 L7510 7480 L7510 7540" />'
    print 'The multitudinous little pathes are slowing down Firefox significantly, so this program'
    print 'compresses them into a single path.'
    sys.exit(1)

file = sys.argv[1]
doc = xml.dom.minidom.parse(file)

moveToRegex = re.compile("^ *M *([0-9]+) *,? *([0-9]+) *(.*)$")

def compressPathes(node):
    """Process a SVG document and merge certain sequential pathes that start and end at the same point"""
    lastPath = None
    index = 0
    # removing nodes causes the for loop to skip elements
    while index < node.childNodes.length:
        child = node.childNodes[index]
        index += 1
        if child.nodeType == Node.ELEMENT_NODE:
            if child.nodeName == "path":
                if lastPath is not None:
                    match = moveToRegex.search(child.getAttribute("d"))
                    if match is not None:
                        if lastPath.getAttribute("d").endswith("L%s %s" % (match.group(1), match.group(2))):
                            lastPath.setAttribute("d", lastPath.getAttribute("d") + " " + match.group(3))
                            node.removeChild(child)
                            child = None
                            index -= 1
                if child is not None: lastPath = child
            elif child.nodeName == "g":
                compressPathes(child)

def removeSequentialWhitespace(node):
    """Process a DOM tree and removes siblings that are only whitespace"""
    lastEmpty = False
    index = 0
    while index < node.childNodes.length:
        child = node.childNodes[index]
        index += 1
        if child.nodeType == Node.TEXT_NODE and child.data.strip() == "":
            if lastEmpty:
                node.removeChild(child)
                index -= 1
            lastEmpty = True
        else:
            lastEmpty = False
            if child.nodeType == Node.ELEMENT_NODE:
                removeSequentialWhitespace(child)
        
compressPathes(doc.documentElement)
removeSequentialWhitespace(doc.documentElement)
print doc.documentElement.toxml()
