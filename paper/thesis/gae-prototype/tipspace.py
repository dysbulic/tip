#!/usr/bin/env python
# -*- coding: utf-8 -*-

'''Test program to pull down a XML file, parse it to the data store and reconstruct it.'''

print 'Content-Type: text/html'
print ''

import sys

gaehosted = len(sys.argv) == 1

if gaehosted:
    from gae_model import Node, Spot, String
else:
    from mem_model import Node, Spot, String

import re

# The tag name is potentially {xmlns}tag when passed to parser
tagExp = re.compile('^{(.*)}([^}]+)$')
def getTag(tag):
    tagMatch = tagExp.match(tag)
    if tagMatch is not None:
        tag = tagMatch.group(2)
    return tag

try:
    from lxml import etree
except ImportError:
    try:
        import xml.etree.cElementTree as etree
    except ImportError:
        import xml.etree.ElementTree as etree

class ParseTarget(object):
    root = None

    def __init__(self):
        self.root = Spot(name = '__')
        self.root.put()
        self.currentNode = self.root

    def start(self, tag, attrib):
        node = Spot(name = getTag(tag))
        node.put()
        self.currentNode.addChild(node)
        self.currentNode = node
        
    def end(self, tag):
        self.currentNode.put()
        self.currentNode = self.currentNode.parent
        if self.currentNode == self.root: # Closing document
            self.root.put()

    def data(self, data):
        data = data.strip()
        if len(data) > 0:
            node = String(name = '__string__')
            node.value = data
            node.put()
            self.currentNode.addChild(node)

    def close(self): # This isn't called
        self.root.put()
        return self.root

root = None

if gaehosted:
    root = Spot.all().filter('name =', '__').get()
else:
    import cPickle, os
    if os.path.exists('root.pickle'):
        pickleFile = open('root.pickle', 'r')
        root = cPickle.load(pickleFile)
        pickleFile.close()

if root is None:
    url = 'http://projects.will.madstones.com/blog/templating_basics/simple_html_tree.html'
    url = 'http://will.tip.dhappy.org/pcvs.org/mr/links.html'

    import urllib2
    parseTarget = ParseTarget()
    parser = etree.XMLParser(target = parseTarget)
    result = etree.parse(urllib2.urlopen(url), parser)
    root = parseTarget.root

if not gaehosted and not os.path.exists('root.pickle'):
    pickleFile = open('root.pickle', 'w')
    cPickle.dump(root, pickleFile)
    pickleFile.close()


nodelist = [ root.getChild(0) ]
pathToRoot = [] # getparent() is not supported
outtree = etree.Element('fauxroot')
while len(nodelist) > 0:
    currentNode = nodelist.pop()
    if currentNode is None: # Empty nodes in the tree mark returns
        outtree = pathToRoot.pop()
        continue
    if hasattr(currentNode, 'getChildren'):
        nodelist.append(None) # Place return marker
        pathToRoot.append(outtree)
        outtree = etree.SubElement(outtree, currentNode.name)

        children = currentNode.getChildren()
        children.reverse() # Put in the proper order for popping
        nodelist.extend(children)
    else:
        outtree.text = currentNode.value

outtree = outtree[0]
print etree.tostring(outtree)
