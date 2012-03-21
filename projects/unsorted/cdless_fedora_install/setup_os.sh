#!/bin/bash

DESTDIR=os
ISOPATTERN=FC3-i386-disc\$i.iso

PROGPATH="${0%/*}"

pushd "$PROGPATH" > /dev/null

WORKDIR="$(pwd)"

[ -d "$DESTDIR" ] || mkdir -vp "$DESTDIR"

for i in $(seq 1 4); do
    ISO=$(eval echo "$ISOPATTERN")
    DIR="disc-$i"
    [ -e "$ISO" ] || (echo "Missing ISO: $ISO" && exit 1)
    [ -d "$DIR" ] || mkdir -vp "$DIR"
    mount | grep "$WORKDIR/$ISO on $WORKDIR/$DIR" > /dev/null || sudo mount -o loop "$ISO" "$DIR" || exit 1
    for dir in $(find "$DIR" -type d); do
        [ "$dir" == "$DIR" ] && continue
        NEWDIR="$DESTDIR/${dir#$DIR/}"
        [ -d "$NEWDIR" ] || mkdir -vp "$NEWDIR"
    done
    for file in $(find "$DIR" -type f); do
        PROGPATH="${file%/*}"
        RELPATH=$(echo $PROGPATH | sed -e "s/[^/]\+/../g")
        NEWFILE="$DESTDIR/${file#$DIR/}"
        [ -e "$NEWFILE" ] || ln -sv "$RELPATH/$file" "$NEWFILE"
    done
    echo "Mounted and symlinked: $ISO"
done

popd > /dev/null
