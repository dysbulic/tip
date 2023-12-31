#!/bin/sh

echo "select text from article" | \
  /usr/local/mysql/bin/mysql -u root kintera_prep | \
  perl -n00e "print \"\$2\\n\" while /<a +[^>]*href=([\"']) *(.*?)\\1[^>]*>(.*?)<\\/a>/ig" | \
  sort
