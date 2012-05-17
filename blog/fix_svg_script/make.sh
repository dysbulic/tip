#!/bin/bash

SVGWEB=svgweb-2009-11-23-Gelatinous-Cube.zip
[ -e "$SVGWEB" ] || wget "http://svgweb.googlecode.com/files/$SVGWEB"

unzip "$SVGWEB"
