#!/usr/bin/env python

import sys, glob

allKeys = []
allVals = []

ignoreKeys = ["uid", "form_name"]

if len(sys.argv) is 0: sys.exit(-1)

def addVal(key, val):
    vals[key] = val.replace(",", "&#44;")
    try:
        allKeys.index(key)
    except ValueError:
        allKeys.append(key)

def processFile(filename, key_pre = "", key_post = ""):
    """Reads in a file of key: value pairs and generates a hash from them."""
    inFile = open(filename, 'r')
    for line in inFile:
        val = line.rstrip().split(": ")
        if len(val) > 1 or line.find(":") > -1:
            try:
                ignoreKeys.index(val[0])
            except ValueError:
                currentKey = key_pre + val[0] + key_post
                try:
                    addVal(currentKey, val[1])
                except IndexError:
                    pass
        else:
            vals[currentKey] += "&#10;" + val[0].replace(",", "&#44;")
    inFile.close()

processedIds = {}
    
for filename in sys.argv[1:]:
    vals = {}
    addVal("id", filename.split(" - ")[0])
    if processedIds.has_key(vals["id"]): continue

    files = glob.glob("%s - *.txt" % vals['id'])
    for idFile in files:
        splitName = idFile.split(" - ")
        shortname = splitName[1].split(" ")[0]
        if len(splitName) > 3: trialId = "_" + splitName[2]
        else: trialId = ""
        processFile(idFile, shortname + "_", trialId)

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

