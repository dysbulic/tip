#!/usr/bin/env python

from google.appengine.ext import db
from google.appengine.ext.db import polymodel

class Node(polymodel.PolyModel):
    name = db.StringProperty(required = True)

class Spot(Node):
    childKeys = db.ListProperty(db.Key)
    children = None

    def addChild(self, node):
        self.childKeys.append(node.key())
        self.children = None
        node.parent = self

    def getChild(self, index):
        if self.children is None:
            self.getChildren()
        return self.children[index]

    def getChildren(self):
        if self.children is None:
            self.children = Node.get(self.childKeys)
        return self.children

class String(Node):
    value = db.StringProperty()
