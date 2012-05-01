#!/usr/bin/python

import os, sys

cssFile = "temp_rectangle.css"
reflectionFile = "reflection.png"
sourceFile = "burnished_rectangle.svg"
fullpath = os.path.abspath(os.path.dirname(sys.argv[0]))
args = "-w 260 -h 260 -cssAlternate file://%s/%s" % (fullpath, cssFile)

colors = { "hiphop" : "#1E4069", "altrock" : "#7C2E2B", "blues" : "#5A1A64", "indiepop" : "#385E42",
           "electronica" : "#493DA4", "poetry" : "#F8FAB5", "bigshed" : "#FFBF75" }

if len(sys.argv) < 2 or sys.argv[1] != "reflonly":
    for color in colors.keys():
        outFile = open(cssFile, 'w')
        outFile.write("#outer { fill: %s ! important; }\n" % colors[color])
        outFile.write("#%s { display: block; }\n" % color)
        outFile.write("#reflection { display: none; }\n")
        outFile.close()
        command = "batik-rasterizer.sh %s -d background_%s.png %s" % (args, color, sourceFile)
        print "Running: %s" % command
        os.system(command)
        # os.unlink(cssFile)

outFile = open(cssFile, 'w')
outFile.write("text, rect, #logo { display: none; }\n")
outFile.write("#reflection, clipPath { display: block; }\n")
outFile.close()
command = "batik-rasterizer.sh %s -d %s %s" % (args, reflectionFile, sourceFile)
print "Running: %s" % command
os.system(command)
