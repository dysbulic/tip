#!/usr/bin/env python

# Simple script to take a group of e-mails in separate files and make
# them into a RSS feed (for importing into wordpress)

import glob, re
from datetime import datetime

print """<?xml version="1.0"?>
<rss version="2.0">
  <channel>
    <title>Journals from the RIM</title>
    <link>http://odin.himinbi.org/jsfromtherim/</link>
    <description>Will Holcomb's Journals from Mauritania</description>
    <language>en-us</language>
    <managingEditor>wholcomb@gmail.com</managingEditor>\n"""

allfiles = []
for file in glob.glob("*.txt"):
    allfiles.append(file)
allfiles.sort()

for file in allfiles:
    infile = open(file, "r")
    firstline = infile.readline()
    if firstline.split()[0] != "From":
        print "<!-- Error: %s does not start with 'From' -- not an e-mail message? -->" % file
        continue
    splitname = file.replace("-", ".").split(".")
    date = datetime(int(splitname[0]), int(splitname[1]), int(splitname[2]),
                    int(splitname[3]), int(splitname[4]), int(splitname[5]))

    headers = {}
    lastKey = ""
    while infile:
        line = infile.readline().rstrip()
        if line == "":
            break
        else:
            if line.startswith("\t") or line.startswith(" "):
                line = headers[lastKey] + "\t" + line.strip()
            else:
                try:
                    (lastKey, line) = line.split(":", 1)
                    lastKey = lastKey.lower()
                except ValueError:
                    print "Error: Bad line: '%s' (%s)" % (line, file)
            headers[lastKey] = line.strip()

    title =  headers["subject"].replace("[rim] ", "").split(" - ", 1)[-1]
    
    paraRegex = re.compile("(\\S+( \\S+){6,}) *\n(\\S)")
    text = infile.read().strip()
    
    text = paraRegex.sub("\\1 \\3", text)
    text = text.replace("&", "&amp;")
    text = text.replace("<", "&lt;")
    
    print "<item>"
    print "  <title>%s</title>" % title
    print "  <pubDate>%s</pubDate>" % date
    print "  <description>" + text + "</description>"
    print "</item>"

print "  </channel>"
print "</rss>"

