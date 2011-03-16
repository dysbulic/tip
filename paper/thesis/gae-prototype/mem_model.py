#!/usr/bin/env python

class Node(object):
    def __init__(self, name):
        self.name = name

    def put(self):
        pass

class Spot(Node):
    def __init__(self, name):
        Node.__init__(self, name)
        self.children = []

    def addChild(self, node):
        self.children.append(node)
        node.parent = self

    def getChild(self, index):
        return self.children[index]

    def getChildren(self):
        return self.children

class String(Node):
    pass
