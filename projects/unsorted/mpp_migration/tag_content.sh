#!/bin/sh

if [ -z "$1" ]; then
    echo <<EOF
Returns unique entries for a particular XML entry in the sitebuilder database 
Usage: $0 <tag>
EOF
    exit
fi

tag="$1"

echo "select xml from pool where xml like '%<$tag%';" | \
  /usr/local/mysql/bin/mysql -u root mpp | \
  perl -ne 'chomp; s/.*<'$tag'>(.*)<\/'$tag'>.*/$1/g; $vars{$_}++;
    END { foreach $tagname (sort(keys(%vars))) {
           printf("%s: %d\n", $tagname, $vars{$tagname}); } };'
