#!/usr/bin/env python

import sys, os

if len(sys.argv) == 1:
    print "Usage: group_postprocess.py <log file>"
    print " Combines multiple trials onto a single line"
    sys.exit(-1)

allVals = {}
    
for filename in sys.argv[1:]:
    inFile = open(filename)

    # first line is the fields
    # first two elements are "id" and "trial"
    for line in inFile:
        cols = line.rstrip().split(", ")[2:]
        break

    for line in inFile:
        if line.rstrip() is "": continue
        vals = line.rstrip().split(", ")
        subjectId = int(vals[0])
        trialId = int(vals[1])
        if not allVals.has_key(subjectId): allVals[subjectId] = {}
        allVals[subjectId][trialId] = vals[2:]
    inFile.close()

outStr = "id"
for col in cols:
    for trialNum in range(2,3 + 1):
        outStr += ",%s_%d" % (col, trialNum)
print outStr

checked = {}

for subjectId in allVals.keys():
    outStr = str(subjectId)
    for colNum in range(len(cols)):
        for trialNum in range(2,3 + 1):
            outStr += ","
            valId = "%s-%s" % (subjectId, trialNum)
            if not allVals[subjectId].has_key(trialNum):
                if not checked.has_key(valId): sys.stderr.write("Subject %s has no trial %s\n" % (subjectId, trialNum))
                checked[valId] = True
            else:
                outStr += str(allVals[subjectId][trialNum][colNum])
    print outStr
