#!/bin/sh

IFS="
"
PAD="    ";
cat head.html
echo "$PAD<table width=\"100%\">";
for dir in [A-Z]*; do
  files=$(ls -c1 $dir/*.mp3 2> /dev/null);
  if [ "$files" != "" ]; then
    url=${dir// /%20}
    url=${url//,/%2C}
    url=${url//&/%26}
    echo "$PAD  <tr>";
    echo "$PAD    <td colspan=\"3\"><a href=\"$url\">$dir</a></td>";
    echo "$PAD  </tr>";
    for file in $files; do
      name=${file%.mp3}
      name=${name##*/}
      du=`du $file`
      size="${du%%[^0-9]*} Kb"
      file=${file// /%20}
      file=${file//,/%2C}
      file=${file//&/%26}
      file=${file//\"/%22}
      echo "$PAD      <tr>";
      echo "$PAD        <td class=\"filename\"><a href=\"$file\">$name</a></td>";
      echo "$PAD        <td style=\"text-align: right;\">$size</td>";
      echo "$PAD      </tr>";
    done;
    echo "$PAD  <tr><td colspan=\"3\"><hr width=\"10%\" /></td></tr>";
  fi
done;
echo "$PAD</table>"
cat tail.html
