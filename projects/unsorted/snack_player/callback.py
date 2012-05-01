#!/usr/bin/python

def callback(message):
    print "Callback: " + message

def foo(cback):
    cback("called back")

foo(callback)
