#!/bin/bash

KINDLE=/cygdrive/h/documents/
CONVERTER=~/.../lib/calibre/convert2mobi.bat

if [ ! -d "$KINDLE" ]; then
    echo "Kindle not detected at: $KINDLE"
#    exit -1
fi

for file in */*.html; do
    mobifile="${file%html}mobi"
    doc="$(basename "$file")"
    mobidoc="${doc%html}mobi"
    kindlemobi="$KINDLE$mobidoc"
    if [[ ! -e "$kindlemobi" || "$file" -nt "$kindlemobi" ]]; then
        pushd "$(dirname "$file")" > /dev/null
        if [[ ! -e "$mobidoc" || "$doc" -nt "$mobidoc" ]]; then
            # This command will only run once without workaround
            # http://bugs.calibre-ebook.com/ticket/3923
            
            # Doesn't wait for execution complete,
            # "start" command inappropriately escapes quote character
            cygstart $CONVERTER "\"$doc\""
        fi
        if [[ -e "$KINDLE" && "$mobidoc" -nt "$kindlemobi" ]]; then
            echo "Copying: $mobidoc"
            cp "$mobidoc" "$KINDLE"
        else
            echo "Missing: $mobidoc"
        fi
        popd > /dev/null
    fi
done
        
