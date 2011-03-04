#!/bin/bash

for file in *; do
    [[ -d "$file" && "$file" != "style" ]] && {
        pushd "$file" > /dev/null
        [ -e 'style' ] || {
            echo "$file"
            linkd style ../style
        }
        popd > /dev/null
    }
done

for file in */*html; do
    echo "Checking: ${file##*/}";
    xmllint --loaddtd --noout "$file" || echo "$file";
done
