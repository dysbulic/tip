#!/bin/bash

BATIK_DIR=$(find "${0%/*}/" -name batik-\* -type d | head -n1)
BATIK_CMD=$BATIK_DIR/batik-rasterizer.jar
if [ -e "$BATIK_CMD" ]; then
    echo "Running: $BATIK_CMD"
    java -jar "$BATIK_CMD" "$@"
else
    echo "Could not find $BATIK_CMD"
fi
