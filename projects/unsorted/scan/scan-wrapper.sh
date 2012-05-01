#!/bin/bash

if [ -z "$*" ]; then
    echo "Usage: ${0#*/} <hostname> [port] [type]"
    exit
fi

[ ! -z "$2" ] && PORT="on port #$2"
TYPE="${3:-Unusual}";

dir=${0%/*}
PROGRAM=$dir/scan.pl

[ ! -e $PROGRAM ] && echo "Error: $PROGRAM not found" && exit 1

$PROGRAM $1 | mail -s "Attention: $TYPE access detected from $1 $PORT" root
