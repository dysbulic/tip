#!/bin/bash

# Package an e-book to a rar

dir="$1"
[ ! -d "$dir" ] && {
    echo "Usage $0 [book_dir]"
    exit -1
}

dir="${dir%/}"

out="$dir.html.rar"
[ -e "$out" ] && {
    echo "Output already exists: $out"
    echo "Continue? [y/N]"
    read -n 1 continue
    [[ "$continue" == "y" ||  "$continue" == "Y" ]] || {
        exit -2
    }
}

zipper="/cygdrive/c/Program Files/7-Zip/7z.exe"
[ -e "$zipper" ] && {
    "$zipper" a -x\!*/*.log -x\!*/*.mobi -x\!\*~ "$out" "$dir"
    echo "Created: $out"
}
