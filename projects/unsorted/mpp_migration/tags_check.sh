#!/bin/sh

for tag in $(echo "select xml from pool;" | \
  /usr/local/mysql/bin/mysql -u root mpp | \
  perl -ne 'chomp; s/[^<]*<\/?([^ >]+)[^>]*\/?>/$1:/g;
  foreach $var (split(/:/, $_)) { $vars{$var}++; }
    END { foreach $tagname (sort(keys(%vars))) {
    printf("%s ", $tagname, $vars{$tagname}); } };'); do
  echo "select '$tag//$tag', count(*) from pool where xml like '%<$tag%<$tag%'" | /usr/local/mysql/bin/mysql -u root mpp | tail -n1
done
