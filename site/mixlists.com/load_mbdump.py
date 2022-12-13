#!/usr/bin/python

import os, sys, shutil, subprocess

if len(sys.argv) == 1:
    print "Load a database dump from http://musicbrainz.org/doc/DatabaseDownload"
    print "Usage: %s <mbdump archive> [destination directory]" % sys.argv[0]
    print "If a directory is not specified the archive will be extracted to a new directory."
    sys.exit(-1)

extension = ".tar.bz2"
destdir = sys.argv[1]

if len(sys.argv) > 2:
    destdir = sys.argv[2]
elif destdir.endswith(extension):
    destdir = destdir[0:len(destdir) - len(extension)]

base = destdir

if len(sys.argv) == 2:
    count = 1
    origdir = destdir
    while os.path.exists(destdir):
        destdir = "%s-%s" % (origdir, count)
        count += 1

if not os.path.exists(destdir):
    os.mkdir(destdir)
    
os.chdir(destdir)

if len(sys.argv) > 1 and not os.path.exists(sys.argv[1]):
    os.symlink("../%s" % sys.argv[1], sys.argv[1])
    print "Extracting %s to %s" % (sys.argv[1], destdir)
    process = subprocess.Popen(["tar", "xfj", sys.argv[1]], stdin=None, stdout=None, stderr=None)
    status = os.waitpid(process.pid, 0)
else:
    print "Skipping extracting %s" % (destdir)

# schema=CreateTables.sql?format=raw
# schemaout="${schema%%\?*}"
# if [ ! -e "$schemaout" ]; then
#     wget http://bugs.musicbrainz.org/browser/mb_server/trunk/admin/sql/$schema
#     mv "$schema" "$schemaout"
# fi

outfile = "%s_import.sql" % base
print "Creating: %s/%s" % (destdir, outfile)
shutil.copyfile("../tracklist_tables.sql", outfile)
out = open(outfile, "a")

def ses(count):
    if count == 1: return ""
    else: return "s"

def extractColumns(filename, table, columns):
    # with open(filename) as IN:
    print "Processing %s from %s" % (table, filename),
    IN = open(filename)
    maxLines = 1000
    lineCount = 0
    for line in IN:
        lineCount += 1
        cols = line.split("\t")
        for colIndex in range(len(cols)):
            cols[colIndex] = cols[colIndex].replace("'", "\\'")
        if (lineCount - 1) % maxLines is 0:
            if lineCount is not 1: out.write(";\n")
            out.write("INSERT IGNORE INTO %s VALUES\n" % table)
            sys.stdout.write(".")
            sys.stdout.flush()
        else:
            out.write(",\n")
        out.write("        (");
        for colIndex in range(len(columns)):
            out.write("'%s'" % cols[columns[colIndex]]);
            if colIndex < len(columns) - 1: out.write(", ")
        out.write(")")
    out.write(";\n")
    print "\n   Wrote %s record%s" % (lineCount, ses(lineCount))
    IN.close()
    return lineCount

totalRecords = 0
totalRecords += extractColumns("mbdump/artist", "artist (id, name, sortname, guid)", [0, 1, 4, 2])
totalRecords += extractColumns("mbdump/track", "track (id, name, artist, length, year, guid)", [0, 2, 1, 4, 5, 3])
totalRecords += extractColumns("mbdump/lt_artist_track", "track_artist_rship_type (id, parent, node_order, name, description)", [0, 1, 2, 4, 5])
totalRecords += extractColumns("mbdump/lt_artist_artist", "artist_artist_rship_type (id, parent, node_order, name, description)", [0, 1, 2, 4, 5])
totalRecords += extractColumns("mbdump/l_artist_track", "track_artist (artist, track, rship)", [1, 2, 3])
totalRecords += extractColumns("mbdump/l_artist_artist", "artist_artist (subject, object, rship)", [1, 2, 3])
totalRecords += extractColumns("mbdump/album", "album (id, name, artist, guid)", [0, 2, 1, 3])
totalRecords += extractColumns("mbdump/albumjoin", "album_track (track, album, number)", [1, 2, 3])

print "Processed %s total record%s" % (totalRecords, ses(totalRecords))
