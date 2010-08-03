#!/opt/local/bin/python
#!/usr/bin/python

"""
Author: Will Holcomb <wholcomb@gmail.com>
Date: 2006/04/22

Test program to take a html document and isolate the input element values
"""

import sys
import re

if len(sys.argv) <= 1:
    print "Usage %s <filename.html>" % sys.argv[0]
    print " Takes a html form and prints out the input values"
    sys.exit(1)

INPUTRE = re.compile("<input [^>]*name=\"(?P<name>[^\"]*)\" [^>]*value=\"(?P<value>[^\"]*)\"", re.DOTALL)
INPUTRE = re.compile("(?P<tag><input (?:[^>]*(?:(?:type=\"(?P<type>.*?)\")|(?:name=\"(?P<name>.*?)\")|(?:value=\"(?P<value>.*?)\")))*[^>]*>)", re.DOTALL)
INPUTRE = re.compile("<input([^>]*)>", re.DOTALL)
VALUEPAIRRE = re.compile("(\\w+)(?:=(?:\"([^\"]*)\"|(\\S+)))?", re.DOTALL)
for filename in sys.argv[1:]:
    print "Processing: %s" % filename
    data = open(filename, "r").read()
    params = {}
    for input in INPUTRE.findall(data):
        properties = {}
        for pair in VALUEPAIRRE.findall(input):
            print pair
            properties[pair[0].lower()] = pair[1] + pair[2]
        if properties.has_key('type') and properties['type'].lower() == "checkbox" and not properties.has_key('checked'):
            pass
        elif properties.has_key('name') and properties.has_key('value'):
            params[properties['name']] = properties['value']
        elif properties.has_key('name'):
            params[properties['name']] = None
        elif properties.has_key('type') and (properties['type'] == "reset" or properties['type'] == "submit"):
            pass
        else:
            print "Couldn't interpret: '%s'" % input
    print params
