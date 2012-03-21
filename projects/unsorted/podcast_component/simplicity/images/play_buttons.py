#!/usr/bin/python

import os, sys

cssFile = "temp_play_button.css"
sourceFile = "play_button.svg"
fullpath = os.path.abspath(os.path.dirname(sys.argv[0]))
args = "-w 45 -h 45 -cssAlternate file://%s/%s" % (fullpath, cssFile)

colors = { "hiphop" : "#1E4069", "altrock" : "#7C2E2B", "blues" : "#5A1A64", "indiepop" : "#385E42",
           "electronica" : "#493DA4", "poetry" : "#F8FAB5", "bigshed" : "#FFBF75" }

if len(sys.argv) < 2 or sys.argv[1] != "reflonly":
    for color in colors.keys():
        outFile = open(cssFile, 'w')
        outFile.write("#background { fill: %s ! important; }\n" % colors[color])
        outFile.close()
        command = "batik-rasterizer.sh %s -d play_%s.png %s" % (args, color, sourceFile)
        print "Running: %s" % command
        os.system(command)
        os.unlink(cssFile)
