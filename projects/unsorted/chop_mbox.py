#!/usr/bin/python

"""Usage: chop_mbox.py <mailbox file>+
Chops a mbox file up into individual files named "${date} - ${subject}.txt" """

import datetime, time
import sys, os.path, re

if len(sys.argv) <= 1:
    print __doc__
    sys.exit(1)

# From will@himinbi.org  Sat Aug  2 11:12:14 2003 
headerExp = re.compile("^From (?P<name>[^\\s@]+).+(?P<weekday>\\S+)\\s+(?P<month>\\S+)\\s+(?P<day>\\d+)" +
                       "\\s+(?P<hour>\\d{2}):(?P<minute>\\d{2}):(?P<second>\\d{2})" +
                       "\\s+(?P<year>\\d{2,4})\\s*$")
subjectExp = re.compile("^[Ss]ubject:\s+(.*)$")

months = {}
for month in ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]:
    months[month] = len(months) + 1

outdir = "messages - %s" % time.strftime("%Y-%m-%d %H:%M:%S")
os.mkdir(outdir)
print "Wirting messages to: ", outdir

for file in sys.argv[1:]:
    inHandle = open(file, "r")

    messageCount = 0
    header = inHandle.next()
    headerMatch = headerExp.search(header)
    fileDone = False
    while not fileDone:
        if headerMatch is None:
            print "Error: [%s]: \"%s\" is not a valid start of message" % (file, header)
            sys.exit(-1)
        date = datetime.datetime(int(headerMatch.group("year")), months[headerMatch.group("month")],
                                 int(headerMatch.group("day")), int(headerMatch.group("hour")),
                                 int(headerMatch.group("minute")), int(headerMatch.group("second")))

        subject = None
        headerDone = False
        while not headerDone:
            nextline = inHandle.next()
            if nextline == "\n" or nextline is None:
                headerDone = True
            else:
                if subject is None:
                    subjectMatch = subjectExp.search(nextline)
                    if subjectMatch is not None:
                        subject = subjectMatch.group(1)
                header += nextline
        if subject is None:
            subject = "Untitled"

        subject = re.sub("/", "_", subject)
        filenameBase = "%s.%s.%s" % (date.strftime("%Y-%m-%d.%H:%M:%S"), headerMatch.group("name"), subject)
        filename = "%s.txt" % filenameBase
        count = 0
        while os.path.exists(filename):
            count += 1
            filename = "%s.%s.txt" % (filenameBase, count)

        outHandle = open("%s/%s" % (outdir, filename), "w")
        outHandle.write(header)
        outHandle.write("\n")

        body = ""
        bodyDone = False
        while not bodyDone:
            try:
                nextline = inHandle.next()
                headerMatch = headerExp.search(nextline)
                if headerMatch is not None:
                    header = nextline
                    bodyDone = True
                else:
                    body += nextline
            except StopIteration:
                bodyDone = True
                fileDone = True

        outHandle.write(body)
        outHandle.close()
        messageCount += 1

    # Python has no ternary operator, so I don't know how to do this elegantly
    if messageCount > 1: ses = "s"
    else: ses = ""
    print "Wrote %s message%s from %s" % (messageCount, ses, file)
    inHandle.close()
