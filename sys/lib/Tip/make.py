#!/usr/bin/env python

import os, re, glob

class Function(string):
    val = ;

    def __new__(mcs, name, bases, dict):
        print "constructing"
        dict['foo'] = 'metacls was here'
        return type.__new__(mcs, name, bases, dict)

class FunctionStack:
    

    


def nest(pattern, name):
    for file in glob.glob(pattern):
        pass # stack.push("lang", ${lang#arg});

#for lang in .../lib/from/*; do
#    push(${xslt#.../lib/from/});
#done

while($transform = pop()) {
    $(find *phpml); do
    xsltproc "$file" "${file%ml}p"
