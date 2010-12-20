#!/usr/bin/env python

# For a string with elements, generate a list of the elements
# separated by spaces, for an empty string generate an empty list.

string = "This is, a test. with commas"
print [s.strip() for s in string.split(",")]

eString = ""
print [s.strip() for s in eString.split(",")]

print filter(lambda string: (string != u''), [s.strip() for s in eString.split(",")])
