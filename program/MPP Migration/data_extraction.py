#!/usr/bin/env python

"""
Title: Kintera Data Extraction
Author: Will Holcomb <wholcomb@gmail.com>
Date: June 2007

The bulk of MPP's data is currently housed in a mediocre CMS from
Kintera. A copy of this data is needed, but it isn't possible to get a
dump from them This program spiders the CMS and saves the information
while maintaining the semantic structure.
"""

import MultipartPostHandler
import urllib, urllib2, url_utils
import cookielib
import getpass
import re, os, sys
import traceback, inspect
import socket, time

# python, py-libxml2 installed in OSX using darwinports. libxml2dom
# installed from source

import libxml2dom
from libxml2dom.macrolib import LSException

# timeout in seconds -- this can only be set globally for urllib2

timeout = 40
print "Set socket timeout to %s seconds" % timeout

# Authentication information

kinteraUsername = "wholcombmpp"
baseDir = "export"
dryRun = False # if enabled no files are written
replaceFiles = False # if enabled content items are redownloaded
pathChars = " :&;()',\"?" # special characters allowed in path names
maxTimeouts = 15 # Maximum number of times to fail before giving up

defaultContentURI = "Web Page"
contentURIs = { "Web Page" : "%s/kintera_cms/pp/pp.asp?parent_bin_id=&cid={%s}&bin_id={%s}",
                "Inline Content Listing By Folder" : "%s/kintera_cms/apps/nl/inline2.asp?parent_bin_id=&cid={%s}&bin_id={%s}",
                "Inline Web Feature Listing" : "%s/kintera_cms/apps/lk/inlinelinks.asp?parent_bin_id=&cid={%s}&bin_id={%s}",
                "External Hyperlink" : "%s/kintera_cms/apps/s/link.asp?parent_bin_id=&cid={%s}&bin_id={%s}",
                "Content Listing By Folder" : "%s/kintera_cms/apps/nl/newsletter2.asp?parent_bin_id=&cid={%s}&bin_id={%s}",
                "Contact Form - Standard" : "%s/kintera_cms/apps/ka/default.asp?parent_bin_id=&cid={%s}&bin_id={%s}",
                "Spread the Word" : "%s/kintera_cms/apps/email/spreadWord.asp?parent_bin_id=&cid={%s}&bin_id={%s}",
                "Action Center" : "%s/kintera_cms/apps/ka/actioncenter.asp?parent_bin_id=&cid={%s}&bin_id={%s}"
                }
contentURIs["Inline Content"] = contentURIs["Web Page"]

docTypes = { "Web Page" : "website",
             "Inline Content" : "content",
             "Content Listing By Folder" : "listing",
             "Inline Content Listing By Folder" : "listing",
             "External Hyperlink" : "externlink",
             "Action Center" : "actioncenter",
             "Contact Form - Standard" : "contactform",
             "Inline Web Feature Listing" : "listing",
             "Spread the Word" : "spreadtheword",
             "Content Library" : "contentitem"
             }

def flush_print(string):
        print "%s: %s" % (time.strftime("%Y/%m/%d %H:%M:%S"), string)
        sys.stdout.flush()

try:
        flush_print("Logging in with username: %s" % kinteraUsername)
except NameError:
        kinteraUsername = raw_input("Kintera Username: ")

try:
        password = os.environ['KINTERAPASS']
except KeyError:
        password = getpass.getpass("(KINTERAPASS) Kintera Password: ")

# urllib is used to retrieve the various pages. To handle the repeated
# tasks a function is defined.

cookies = cookielib.CookieJar()
opener = urllib2.build_opener(urllib2.HTTPCookieProcessor(cookies),
                              MultipartPostHandler.MultipartPostHandler)

maxWidth = 105
def getPage(url, params = None):
        global maxTimeouts
        result = None
        errors = 0
        socket.setdefaulttimeout(timeout)
        while result is None:
                try:
                        printURL = url
                        if len(printURL) > maxWidth:
                                printURL = printURL.lstrip("http://")
                                printURL = printURL.lstrip("https://")
                        if len(printURL) > maxWidth:
                                firstslash = printURL.find('/')
                                printURL = printURL[0:firstslash] + "..." + printURL[-(maxWidth - 3 - firstslash):]
                        flush_print("Getting: %s" % printURL)
                        result = opener.open(url, params)
                        page = result.read()
                        flush_print("Read: (%d): %s" % (len(page), printURL))
                        return page
                except (socket.sslerror, urllib2.URLError), error:
                        reason = str(error)
                        if hasattr(error, "reason"):
                                reason = error.reason
                        elif hasattr(error, "message"):
                                reason = error.message
                        errors += 1
                        flush_print("Error: (%d/%d) (%s): %s" % (errors, maxTimeouts, url, reason))
                        # retry on a timeout
                        if str(reason).find("timed out") < 0 or errors > maxTimeouts:
                                raise error
                        newTimeout = round(timeout * (1 + errors / float(maxTimeouts)))
                        flush_print("Setting socket timeout to %d" % (newTimeout))
                        socket.setdefaulttimeout(newTimeout)

# The login information is stored in a cookie, so authenticate and
# save the session cookies

URI = "https://www.kintera.com"
LOGINURI = "%s/kintera_sphere/login/asp/login.asp?use=yes" % URI

params = {"LoginName" : kinteraUsername, "Password" : password }
getPage(LOGINURI, params)

# To start, get a list of all the CMSes

CMSLIST = "%s/kintera_sphere/comm/cms_website/cmsWebsiteList.aspx" % URI

cmsList = getPage(CMSLIST, params)
params = url_utils.getParams(cmsList, "Form1")
params['_ctl0$LR1$Pgr'] = 100

params['__EVENTARGUMENT'] = 'NewPageSize'
params['__EVENTTARGET'] = '_ctl0$LR1$Pgr'

cmsList = getPage(CMSLIST, params)

# Each CMS has an id associated with it

ACIDRE = re.compile(".*start.asp\?acid=([^&]*).*", re.DOTALL)

acidMatch = ACIDRE.match(cmsList)
if acidMatch is None:
        flush_print("Could not find site ID")
        sys.exit(-1)

acid = acidMatch.group(1)
flush_print("Using Site ID: '%s'" % acid)

# Pull the IDs out for each site

cmsListDoc = libxml2dom.parseString(cmsList, html = 1)
CMSRE = re.compile("http://([^/]+)/site/lookup.asp\?c=(.+)", re.DOTALL)
MENURE = re.compile("ShowMenu\(event, *'(.*)'.*")
FOLEXPCOLLRE = re.compile("fExpandCollapse\(([0-9]*), *[01][^01]")
FILEXPCOLLRE = re.compile("fExpandCollapse\('([0-9]*)', *1[01]")
CIDRE = re.compile(".*[^a]cid={([^}]*)}.*", re.DOTALL)
BINIDRE = re.compile("{([^}]*)}.*")

# Using the parseFile method is causing the program to abort with a
# "too many files open" error

def parseFile(filename, html = True):
        flush_print("Parsing: %s" % filename)
        fileHandle = open(filename)
        fileDoc = libxml2dom.parse(fileHandle)
        fileHandle.close()
        #flush_print("Parsed: %s" % filename)
        return fileDoc

# Cache bin ids because reparsing files takes exponential time

binids = {}
def getbinid(filename):
        path = '/'.join(directories) + '/' + filename
        if binids.has_key(path):
                pass #flush_print("Cache hit: %s -> %s" % (path, binids[path]))
        else:
                fileDoc = parseFile(filename, html = False)
                binids[path] = fileDoc.documentElement.getAttribute("binid").strip("{}")
                #flush_print("Cache miss: %s -> %s" % (path, binids[path]))
        return binids[path]

def storeContent(params, filename = None):
        global docTypes
        # this gets blanked sometimes
        binid = params['bin_id']
        title = params['bin_title']
        elementType = params['elementType']

        if filename is None:
                filename = "%s.xml" % title
        filename = urllib.quote(filename, pathChars)

        if os.path.exists(filename):
                try:
                        matched = binid == getbinid(filename)
                        if not matched:
                                count = 1
                                (base, ext) = os.path.splitext(filename)
                                filename = "%s.%s%s" % (base, count, ext)
                                while not matched and os.path.exists(filename):
                                        matched = binid == getbinid(filename)
                                        if not matched:
                                                count += 1
                                                filename = "%s.%s%s" % (base, count, ext)
                        if matched and not replaceFiles:
                                flush_print("Skipping: Extant: %s" % filename)
                                return
                except LSException:
                        flush_print("Error: Couldn't parse: %s" % filename)
        
        docType = "unknown"
        if docTypes.has_key(elementType):
                docType = docTypes[elementType]
        else:
                flush_print("Error: Unknown document type: %s: Using unknown" % elementType)
                docType = "unknown"

        contentPage = getPage(params['contenturi'])
        params = url_utils.getParams(contentPage, "docForm", params)
        if params is None and contentPage.find("<script language=\"JavaScript\">alert('Permission Denied.')</script>") > 0:
                flush_print("Error: Permission Denied: %s" % title)
                docType = "error"
                params = { "error" : "Permission Denied",
                           "title" : title }
        elif params is None:
                flush_print("Could Not Load: %s - %s" % (elementType, title))
                return
                        
        for key in params.keys():
                if params[key] is not None:
                        try:
                                params[key] = unicode(params[key])
                        except UnicodeError:
                                flush_print("Error: Converting Key to Entities: %s" % key)
                                params[key] = encode_unicode(params[key])

        flush_print("Created Output Document: (%s): %s" % (docType, filename))
        outputDoc = libxml2dom.createDocument("http://mpp.org/migration/%s" % docType, docType, None)
        outputRoot = outputDoc.documentElement
        if params.has_key("url"):
                outputRoot.setAttribute("url", params['url'])
        outputRoot.setAttribute("type", elementType)
        outputRoot.setAttribute("binid", binid)

        if params.has_key("content_year"):
                date = "%s/%s/%s" % (params['content_month'], params['content_day'], params['content_year'])
                if date != "//":
                        params['content_date'] = date

        paramNames = { "error" : "error",
                       "bin_title" : "title",
                       "bin_subtitle" : "subtitle",
                       "bin_comment" : "comment",
                       "bin_description" : "description",
                       "bin_source_id" : "sourceid",
                       "bin_link" : "href",
                       "event_id" : "eventid",
                       "searchlocation" : "locationid",
                       "searchsegmentation" : "searchsegmentation",
                       "spreadword_subject" : "subject",
                       "spreadword_appendmsg" : "editmsg",
                       "spreadword_body" : "body",
                       "content_date_created" : "datecreated",
                       "content_date_modified" : "datemodified",
                       "author" : "content_author",
                       "copyright" : "content_copyright",
                       "content_date" : "contentdate"
                       }

        for param in paramNames.keys():
                if params.has_key(param) and params[param] != "":
                        outputRoot.appendChild(outputDoc.createElement(paramNames[param]))
                        outputRoot.lastChild.appendChild(outputDoc.createTextNode(params[param]))

        htmlParamNames = { "content_summary" : "summary", "content_body" : "body" }
        for param in htmlParamNames.keys():
                if params.has_key(param) and params[param] != "":
                        params[param] = replaceEntities(params[param])
                        params[param] = encode_unicode(params[param])
                        outputRoot.appendChild(outputDoc.createElement(htmlParamNames[param]))
                        appendHTML(outputRoot.lastChild, params[param])

        if not dryRun:
                outFile = open(filename, "w")
                outFile.write(outputDoc.toString(encoding = "iso-8859-1", prettyprint = 1))
                outFile.close()

def appendHTML(node, html):
        html = "<html>%s</html>" % html
        doc = libxml2dom.parseString(html, html = 1)
        if doc.documentElement.childNodes.length > 0:
                for child in doc.documentElement.childNodes[0].childNodes:
                        node.appendChild(node.importNode(child, True))

directories = []
def dirdepth(depth):
        global directories
        while len(directories) > depth:
                os.chdir("..")
                directories.pop()

def chdir(dirName, depth = 1):
        global directories
        dirdepth(depth)
        dirName = urllib.quote(dirName, pathChars)
        if not dryRun:
                if not os.path.exists(dirName):
                        os.mkdir(dirName)
                os.chdir(dirName)
                directories.append(dirName)
        flush_print("Changing Directory: %s" % '/'.join(directories))

chdir(baseDir)

def replaceEntities(text):
        text = text.replace("&lt;", "<")
        text = text.replace("&gt;", ">")
        text = text.replace("&amp;", "&")
        text = text.replace("&quot;", '"')
        text = text.replace("\r\n", "\n")
        return text

def encode_unicode(string):
        out = ""
        for char in string:
                if ord(char) == 8212:
                        out += "'"
                elif ord(char) == 160:
                        out += " "
                elif ord(char) > 128:
                        out += "&#x%x;" % ord(char)
                else:
                        out += char
        return out

def processSite(siteDoc, baseDepth = 2):
        global currentDepth
        for childRow in siteDoc.getElementsByTagName("tr"):
                elementChildren = childRow.getElementsByTagName("td")
                if elementChildren.length > 0:
                        firstCell = elementChildren.item(0)
                        elementClass = firstCell.getAttribute("class")
                        if elementClass is not None and elementClass.startswith("lbi"):
                                linkName = "Unknown"
                                try:
                                        linkName = encode_unicode(firstCell.xpath(".//a[last()]/text()")[0].value)
                                except IndexError:
                                        flush_print("Error: No text found for link name")
                                if elementClass == "lbiFolder":
                                        chdir(linkName, baseDepth)
                                elif elementClass == "lbi":
                                        params = { "bin_title" : linkName }

                                        urls = elementChildren.item(2).getElementsByTagName("a")
                                        if urls.length == 0:
                                                params['url'] = None
                                        else:
                                                params['url'] = urls.item(0).childNodes[0].value.rstrip("/")
                                        linkText = firstCell.getElementsByTagName("input").item(0).getAttribute("value")
                                        params['bin_id'] = BINIDRE.match(linkText).group(1).strip("{}")
                                        params['elementType'] = elementChildren.item(3).childNodes[0].value[1:]

                                        depth = baseDepth + 1
                                        hasChild = False
                                        for image in firstCell.getElementsByTagName("img"):
                                                source = image.getAttribute("src")
                                                if source.endswith("1x1t.gif"):
                                                        depth += 1
                                                elif(source.endswith("jsTree_minus_L.gif") or source.endswith("jsTree_plus_L.gif")
                                                     or source.endswith("jsTree_minus_E.gif") or source.endswith("jsTree_plus_E.gif")):
                                                        hasChild = True
                                        dirdepth(depth)

                                        if contentURIs.has_key(params['elementType']):
                                                params['contenturi'] = contentURIs[params['elementType']] % (URI, cid, params['bin_id'])
                                        else:
                                                flush_print("Error: No Content URI for %s: Using %s" % (params['elementType'], defaultContentURI))
                                                params['contenturi'] = contentURIs[defaultContentURI] % (URI, cid, params['bin_id'])
                                        
                                        storeContent(params)

                                        if hasChild:
                                                chdir(linkName, depth)
                                else:
                                        flush_print("Error: Unknown Class: %s" % elementClass)

def processContent(siteDoc):
        global currentDepth
        chdir("Content Library", 2)
        for childRow in siteDoc.getElementsByTagName("tr"):
                elementChildren = childRow.getElementsByTagName("td")
                if elementChildren.length > 0:
                        firstCell = elementChildren.item(0)
                        elementClass = firstCell.getAttribute("class")
                        if elementClass is not None and elementClass.startswith("lbi"):
                                try:
                                        linkName = encode_unicode(firstCell.xpath(".//a[last()]/text()")[0].value)
                                        if elementClass == "lbiFolder":
                                                chdir(linkName, 3)
                                        elif elementClass == "lbi":
                                                params = {}
                                                params['bin_id'] = firstCell.xpath(".//input/@value")[0].value.strip("{}")
                                                params['bin_title'] = linkName
                                                
                                                params['url'] = ("%s/kintera_cms/content/contentedit.asp?cid={%s}&content_id={%s}" %
                                                                 (URI, cid, params['bin_id']))
                                                params['contenturi'] = params['url']
                                                params['elementType'] = "Content Library"
                                                storeContent(params)
                                        else:
                                                flush_print("Error: Unknown Class: %s" % elementClass)
                                except:
                                        flush_print("Error:")
                                        traceback.print_exc(file=sys.stdout)

title = "Unknown"
url = None
websiteId = None
cid = None
for child in cmsListDoc.getElementsByTagName("a"):
        if child.getAttribute("href") == "#" and child.getAttribute("onmouseover") is not None:
                title = child.childNodes[0].value
                menuMatch = MENURE.match(child.getAttribute("onmouseover"))
                if menuMatch is None:
                        flush_print("Couldn't find id for: %s" % title)
                else:
                        websiteId = menuMatch.group(1)
        
        cmsMatch = CMSRE.match(child.getAttribute("href"))
        if cmsMatch is not None:
                url = cmsMatch.group(1)
                flush_print("%s: %s (%s)" % (title, url, websiteId))
                chdir(title)
                try:
                        # Each site needs to have all the folders expanded to
                        # properly spider them. Which folders are expanded is
                        # handled by a POST variable
                
                        SITELISTURI = "%s/kintera_cms/welcome/start.asp?acid=%s&wsid=%s" % (URI, acid, websiteId)
                        contentListPage = getPage(SITELISTURI)

                        params = url_utils.getParams(contentListPage, "docForm")

                        params["expanded_areaid_list"] = ""
                        for folderId in FOLEXPCOLLRE.findall(contentListPage):
                                if params["expanded_areaid_list"] is not "":
                                        params["expanded_areaid_list"] += ","
                                params["expanded_areaid_list"] += folderId

                        params["expanded_bindex_list"] = ""
                        for fileId in FILEXPCOLLRE.findall(contentListPage):
                                if params["expanded_bindex_list"] is not "":
                                        params["expanded_bindex_list"] += ","
                                params["expanded_bindex_list"] += fileId

                        # Save the content from each element

                        CONTENTLISTURI = "%s/kintera_cms/pp/pplist.asp" % URI
                        contentListPage = getPage(CONTENTLISTURI, params)

                        cid = CIDRE.match(contentListPage).group(1)
                        flush_print("Site ID: %s" % cid)

                        siteDoc = libxml2dom.parseString(contentListPage, html = 1)
                        processSite(siteDoc)

                        CONTENTLISTURI = "%s/kintera_cms/content/contentlist.asp?cid={%s}" % (URI, cid)
                        contentList = getPage(CONTENTLISTURI)
                        contentListDoc = libxml2dom.parseString(contentList, html = 1)

                        params = url_utils.getParams(contentList, "docForm")
                        params["expanded_content_areaid_list"] = ""
                        for node in contentListDoc.xpath("//td[@class='lbiFolder']/a/text()"):
                                if params["expanded_content_areaid_list"] is not "":
                                        params["expanded_content_areaid_list"] += ","
                                params["expanded_content_areaid_list"] += node.value

                        contentList = getPage(CONTENTLISTURI, params)
                        contentListDoc = libxml2dom.parseString(contentList, html = 1)
                        processContent(contentListDoc)

                        #flush_print("(%s:%s) %s : '%s'" % (child.nodeType, child.TEXT_NODE, name, child.getAttribute("href")))
                except:
                        print "Error: Site: %s" % url
                        traceback.print_exc(file=sys.stdout)
                        
