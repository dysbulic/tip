#!/usr/bin/env python

"""
Author: Will Holcomb <wholcomb@gmail.com>
Date: 2007/05/30
Usage:
  Examines a subversion repository and rsyncs the unversioned items
   with a remote source

  sync_nonversioned.py <directory> <remote destination>
  sync_nonversioned.py sites web@sites.com:sites
  
Dependencies:
  Unix utilities accessed by this program:
    * svn - examines the subversion repository
    * rsync - copies the files to the remote server
"""

import os, sys
import getpass
import subprocess
from tempfile import mkstemp

if len(sys.argv) < 3:
    print __doc__
    sys.exit(-1)

tmpOut, tmpFilename = mkstemp(".tmp", "dir_sync.")

print "Writing file list to: %s" % tmpFilename
rsyncCommand = "set"

sourcePath = sys.argv[1]

# if the path contains an :, assume that it is a remote path
if sourcePath.find(":") < 0:
    os.chdir(sourcePath)
    svnCommand = "svn status"
else:
    server, directory = sourcePath.split(":", 2)
    svnCommand = "ssh %s 'svn status %s'" % (server, directory)

svn = subprocess.Popen(svnCommand, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
#(svnin, svnout) = os.popen2(svnCommand, "r")

lineCount = 0
for line in svn.stdout.readlines():
    if line.startswith("?"):
        line = line[1:].strip()
        os.write(tmpOut, line + "\n")
        lineCount += 1

os.close(tmpOut)

print "Added %d files to %s" % (lineCount, tmpFilename)
sys.exit(-1)

# rsyncpass = getpass.getpass("Rsync Password: ")

rsyncCommand = ["rsync", "--recursive", "--progress", "--verbose",
                "--files-from=%s" % tmpFilename, ".", sys.argv[2]]

#rsync = subprocess.Popen(rsyncCommand, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE)

subprocess.call(rsyncCommand)

print "Removing %s" % tmpFilename
os.unlink(tmpFilename)

sys.exit(-1)

for line in rsync.stdout.readlines():
    line = line.strip()
    print "L: %s" % line

svnCommand = "svn status %s" % sys.argv[1]
(svnin, svnout) = os.popen2(svnCommand, "r")

for line in svnout.readlines():
    if line.startswith("?"):
        line = line[1:].strip()
        print "Sending: %s" % line
        rsync.stdin.write(line + "\n")

rsync.stdin.close()
for line in rsync.stdout.readlines():
    print line
