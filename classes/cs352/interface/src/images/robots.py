#!/usr/bin/python

import os, sys

cssFile = "temp_robot.css"
jsFile = "set_bot_number.js"
sourceFile = "robot_image.svg"
fullpath = os.path.abspath(os.path.dirname(sys.argv[0]))
args = "-w 72 -h 72 -cssAlternate file://%s/%s -scripts text/ecmascript -onload" % (fullpath, cssFile)

colors = { 'active' : 'e72bcf', 'inactive' : 'f00' }
opacities = { 'opaque' : '1', 'trans' : '.2' }

for num in range(1, 10):
    for opacity in opacities.keys():
        outFile = open(cssFile, 'w')
        outFile.write("text, rect, path { stroke-opacity: %s !important; fill-opacity: %s !important; }\n" %
                      (opacities[opacity], opacities[opacity]))
        outFile.write("rect, path { fill: none !important; stroke: none !important; }\n");
        outFile.close()
        outFile = open(jsFile, 'w')
        outFile.write("document.getElementById('botnum').appendChild(document.createTextNode('%s'));\n" % num)
        outFile.close()
        command = ("batik-rasterizer.sh %s -d robot_%s_%s.png %s" %
                   (args, num, opacity, sourceFile))
        print "Running: %s" % command
        os.system(command)

for bgcolor in colors.keys():
    for opacity in opacities.keys():
        outFile = open(cssFile, 'w')
        outFile.write("#box { fill: #%s ! important; }\n" % colors[bgcolor])
        outFile.write("text, rect, path { stroke-opacity: %s !important; fill-opacity: %s !important; }\n" %
                      (opacities[opacity], opacities[opacity]))
        outFile.write("text { fill: none !important; stroke: none !important; }\n");
        outFile.close()
        command = ("batik-rasterizer.sh %s -d robot_%s_%s.png %s" %
                   (args, bgcolor, opacity, sourceFile))
        print "Running: %s" % command
        os.system(command)


os.unlink(cssFile)
os.unlink(jsFile)
