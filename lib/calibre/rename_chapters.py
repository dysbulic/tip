#!/usr/bin/env python

import libxml2
import os, glob, shutil
import tempfile

# From: http://stackoverflow.com/questions/1447575/symlinks-on-windows
#import ctypes
#kdll = ctypes.windll.LoadLibrary("kernel32.dll")

preface = "../../"

cmd = open('make_links.bat', 'w')

for file in glob.glob("*html"):
    basename, extension = os.path.splitext(file)

    libxml2.initParser()
    doc = libxml2.readFile(file, None, libxml2.XML_PARSE_DTDLOAD)
    #doc = libxml2.parseFile(file, None, libxml2.XML_PARSE_DTDLOAD)
    ctx = doc.xpathNewContext()
    ctx.xpathRegisterNs('html', "http://www.w3.org/1999/xhtml")

    authorElems = ctx.xpathEval("//html:meta[@name='author']")
    if len(authorElems) == 0:
        print "No Author in: " + file
    elif len(authorElems) > 1:
        print "%d authors in %s" % (len(authors), file)
    else:
        author = authorElems[0].prop("content")
        titleElems = ctx.xpathEval("//html:h1|//html:h2|//html:h3")
        if len(titleElems) > 0:
            title = titleElems[0].content
            name = "%s - %s" % (author, title)
            name = name.replace("/", "_")
            dir = "%s%s" % (preface, name)
            try:
                os.mkdir(dir)
            except OSError:
                # already exists
                pass
            newfile = "%s/%s%s" % (dir, name, extension)
            if not os.path.exists(newfile):
                print "Moving '%s' to '%s'" % (file, newfile)
                os.rename(file, newfile)

                # ctypes.windll does not exist in cygwin
                #kdll.CreateSymbolicLinkA(newfile, file, 0)

                # sharing violation
                #cmd = tempfile.NamedTemporaryFile(suffix='.bat')
                cmd.write('mklink /J "%s" "%s"\n' % (name, dir))
    doc.freeDoc()
    ctx.xpathFreeContext()

cmd.close()
# cygstart doesn't block - race condition
os.system("cygstart %s" % cmd.name)
#os.remove(batch)
