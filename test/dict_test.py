#!/usr/bin/python

numbers = {"one" : 1, "two" : 2, "three" : 3, "four" : 4, "five" : 5, "six" : 6,
           "seven" : 7, "eight" : 8, "nine" : 9, "ten" : 10}

for(key, value) in numbers.items():
    print "%s: %s" % (key, value)
