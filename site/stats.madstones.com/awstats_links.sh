#!/bin/sh

# clear: find -maxdepth 1 -type l | xargs rm -v

AWSTATSDIR=../awstats-6.1

function checklink() {
    source=$1
    target=$2
    [ -z "$target" ] && target="${source##*/}"
    echo "Checking $target -> $source"
    [ -e "$source" ] || echo "$target doesn't exist"
    if [[ -e "$target" && ! -h "$target" ]]; then
        echo "$target exists, but isn't a link"
    elif [ ! -e "$target" ]; then
        ln -s "$source" "$target"
    fi
}

for file in lib lang plugins awstats.pl; do
    checklink $AWSTATSDIR/wwwroot/cgi-bin/$file
done

checklink $AWSTATSDIR/wwwroot/icon icons
#checklink $BASEDIR/tools
checklink $AWSTATSDIR/tools/logresolvemerge.pl
checklink $AWSTATSDIR/tools/awstats_buildstaticpages.pl

SCRIPTDIR=../himinbi.org/odin/stats
for file in $SCRIPTDIR/generate*.sh $SCRIPTDIR/awstats.common.conf; do
    checklink $file
done

checklink $SCRIPTDIR/listindex.php index.php
