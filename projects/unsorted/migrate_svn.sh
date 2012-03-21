#!/bin/bash

# Author: Will Holcomb <wholcomb@gmail.com>
# Date: March 2008

if [[ -z "$1" || ! -d "$1" ]]; then
    cat << EOF
 There are times when a subversion repository moves from one machine
 to another and one needs to change the location for an existing
 repository that, for whatever reason, you don't want to completely
 redownload.

 This script simply starts in a directory and changes the repository
 location recursively.

 Usage: $0 <base directory>
EOF
    exit -1
fi

OLDHOST=wholcomb@svn.himinbi.org\\\/home\\\/wholcomb
NEWHOST=wjholcomb@svn.himinbi.org\\\/home\\\/wjholcomb
PROTO=svn+ssh

PROTO=file
OLDHOST=\\\/home\\\/wholcomb
NEWHOST=\\\/home\\\/wjholcomb

function replacesvnhost() {
    pushd "$1"
    if [ -e .svn/entries ]; then
        sed -e "/$PROTO:\/\/[^\/]*$OLDHOST/s/$OLDHOST/$NEWHOST/" .svn/entries > .svn/entries.new
        mv -fv .svn/entries.new .svn/entries
        for file in .* *; do
            [[ -d "$file" && "$file" != "." && "$file" != ".." ]] && replacesvnhost "$file"
        done
    fi
    popd
}

replacesvnhost "$1"
