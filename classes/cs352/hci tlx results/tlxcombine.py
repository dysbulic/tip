#!/usr/bin/env python

import sys, os

allKeys = []
allVals = []

if len(sys.argv) is 0:
    print "Needs filenames"
    sys.exit(-1)

def addVal(key, val):
    vals[key] = val
    try:
        allKeys.index(key)
    except ValueError:
        allKeys.append(key)

processedIds = {}
    
for filename in sys.argv[1:]:
    vals = {}
    addVal("id", filename.split("-")[0])
    if processedIds.has_key(vals["id"]): continue
    trialNumber = 1

    while os.path.isfile("%s-%s.xls" % (vals["id"], trialNumber)):
        inFile = open("%s-%s.xls" % (vals["id"], trialNumber), 'r')

        # First two lines are header
        inFile.next()
        inFile.next()
        
        for line in inFile:
            if len(line.lstrip()) is 0: break
            val = line.rstrip().split("\t")
            key = val[0].replace(" ", "")
            addVal("%sValue_%s" % (key, trialNumber), val[1])
            addVal("%sWeight_%s" % (key, trialNumber), val[2])
        for line in inFile:
            val = line.rstrip().split("\t")
            addVal("%s_%s" % (val[0].replace(" ", ""), trialNumber), val[1])

        inFile.close()
        trialNumber += 1
    processedIds[vals["id"]] = True
    allVals.append(vals)

allKeys.remove("id")
allKeys.sort()
allKeys.insert(0, "id")

outStr = ""
for key in allKeys:
    outStr += key + ","
outStr = outStr[0:len(outStr) - 1]
print outStr
for vals in allVals:
    outStr = ""
    for key in allKeys:
        if vals.has_key(key):
            outStr += vals[key]
        outStr += ","
    outStr = outStr[0:len(outStr) - 1]
    print outStr

