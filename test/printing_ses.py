#!/usr/bin/python

def sesFunction(count):
    if count == 1: return ""
    else: return "s"

for i in range(1, 10, 2):
    print "%s little indian%s" % (i, sesFunction(i))

sesLambda = lambda count: (count == 1 and [""] or ["s"])[0]

for i in range(1, 10, 2):
    print "%s little indian%s" % (i, sesLambda(i))
