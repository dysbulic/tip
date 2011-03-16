#!/usr/bin/env python
# -*- coding: utf-8 -*-

'''Test program to pull down a XML file, parse it to the data store and reconstruct it.'''

print 'Content-Type: text/plain'
print ''

import datetime
from google.appengine.ext import db
from google.appengine.ext.db import polymodel

class Node(polymodel.PolyModel):
    name = db.StringProperty(required = True)

    def __init__(self, name, parent = None, **kwargs):
        self.name = name
        kwargs['name'] = name
        kwargs['parent'] = parent
        polymodel.PolyModel.__init__(self, **kwargs)

class Spot(Node):
    # children = db.ListProperty(db.ReferenceProperty(Node)) # Not premitted
    children = db.ListProperty(db.Key)

    def __init__(self, name, parent = None):
        Node.__init__(self, name, parent)
        if hasattr(parent, 'children'):
            parent.children.append(self)

class String(Node):
    value = db.StringProperty

    def __init__(self, parent):
        Node.__init__(self, '__string__', parent)
        if hasattr(parent, 'children'):
            parent.children.append(self)

import re

tagExp = re.compile('^{(.*)}(.+)$')
def getTag(tag):
    tagMatch = tagExp.match(tag)
    if tagMatch is not None:
        tag = tagMatch.group(2)
    return tag

import lxml
try:
    from lxml import etree
except ImportError:
    try:
        import xml.etree.cElementTree as etree
    except ImportError:
        import xml.etree.ElementTree as etree


class ParseTarget(object):
    def __init__(self):
        self.root = Spot.all().filter('name =', '__').get()
        if self.root is None:
            print 'Creating spot'
            self.root = Spot('__')
        self.currentNode = self.root
        self.currentNode.put()

    def start(self, tag, attrib):
        print 'Creating: %s' % getTag(tag)
        node = Spot(getTag(tag), self.currentNode)
        self.currentNode = node
        self.currentNode.put()
        
    def end(self, tag):
        print 'Closing: %s' % getTag(tag)
        self.currentNode = self.currentNode.parent_

    def data(self, data):
        data = data.strip()
        if len(data) > 0:
            node = String(self.currentNode)
            node.value = data
            node.put()

    def close(self):
        pass

import urllib2

url = 'http://will.tip.dhappy.org/pcvs.org/mr/links.html'
response = urllib2.urlopen(url)
parser = etree.XMLParser(target = ParseTarget())
#results = etree.parse(response, parser)
