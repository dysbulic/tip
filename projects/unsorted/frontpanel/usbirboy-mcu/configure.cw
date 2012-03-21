#!/bin/sh

#  Configure script for usbirboy MCU compiling
#
#  Author: Ilkka Urtamo <ilkka@urtamo.com>
#


echo -n "Testing wine: "

wine --version 2>&1 >/dev/null

if [ $? -ne 0 ]; then
    echo "ERROR"
    echo "You have no wine installed or it is not added to the user PATH"
    exit 1 
else
    echo "OK"

fi

echo -n "Testing GNU make: "
make --version >/dev/null 2>/dev/null
if [ $? -ne 0 ]; then
    echo "ERROR"
    echo "You have no make tool installed or it is not added to the user PATH"
    exit 1
else
    echo "OK"
fi

echo -n "Testing CodeWarrior: "
wine 'c:\Program Files\Metrowerks\CodeWarrior CW08_V3.0\prog\chc08.exe -v >/dev/null 2>&1
if [ $? -ne 0 ]; then
    echo "ERROR"
    echo "CodeWarrior not found in the default location"
    exit 1 
else
    echo "OK"

fi

# can't get wine/codewarrior to accept paths with spaces in them,
# so copy the necessary header and library files
echo -n "Copying header and library files: "
mkdir -p cw_include
cp ~/.wine/dosdevices/c:/Program\ Files/Metrowerks/CodeWarrior\ CW08_V3.0/lib/HC08c/include/* cw_include
mkdir -p cw_lib
cp ~/.wine/dosdevices/c:/Program\ Files/Metrowerks/CodeWarrior\ CW08_V3.0/lib/HC08c/lib/ansi.lib cw_lib
echo "done"

echo "Copying Makefile:"
ln -s Makefile.cw_wine Makefile
