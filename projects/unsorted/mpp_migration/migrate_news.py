#!/opt/local/bin/python

#
# To migrate news stories from the Federal policies site over to the main site
#

import MultipartPostHandler
import urllib, urllib2
import cookielib
import getpass
import os, stat, sys
import re, time
import datetime, time
import url_utils

kinteraUsername = "wholcombmpp"
minDate = datetime.date(2005, 11, 1)

try:
        password = os.environ['KINTERAPASS']
except KeyError:
        password = getpass.getpass("(KINTERAPASS) Kintera Password: ")

URI = "https://www.kintera.com"
LOGINURI = "%s/KINTERA_Sphere/login/asp/login.asp?use=yes" % URI
NEWSITEURI = "%s/kintera_sphere/comm/cms_website/websiteEditMisc.asp?action=submit" % URI

cookies = cookielib.CookieJar()
opener = urllib2.build_opener(urllib2.HTTPCookieProcessor(cookies),
                              MultipartPostHandler.MultipartPostHandler)

print "Logging into Kintera as: %s" % kinteraUsername

params = {"LoginName" : kinteraUsername, "Password" : password }
getPage(LOGINURI, params)

CMSSURI = "%s/kintera_sphere/comm/cms_website/cmsWebsiteList.aspx" % (URI)
params = getParams(getPage(CMSSURI, params).read())
params["_ctl0:LR1:Pgr"] = "500"
page = getPage(CMSSURI, params).read()

# <a href="#" onmouseover="ShowMenu(event,'nuIVL8P2G');return false;" onmouseout="delayhidemenu();return false;">vmpp.org</a></td>
CMSIDRE = re.compile("<a href.*ShowMenu\(event,'([^']+)'\)[^>]+>(.*?)</a>", re.IGNORECASE)
cmsIds = {}
for cms in CMSIDRE.findall(page):
        cmsIds[cms[1]] = cms[0]

# copied from cms list javascript 
ACID = "8nJKJQPlEeKWE"

fromCMSID = cmsIds["MPP: DD?"]
toCMSID = cmsIds["Marijuana Policy Project"]

CIDRE = re.compile("cid={(.*?)}")
SITELISTURI = "%s/kintera_cms/welcome/start.asp?acid=%s&wsid=%s" % (URI, ACID, fromCMSID)
fromCID = CIDRE.search(getPage(SITELISTURI).read()).group(1)
SITELISTURI = "%s/kintera_cms/welcome/start.asp?acid=%s&wsid=%s" % (URI, ACID, toCMSID)
toCID = CIDRE.search(getPage(SITELISTURI).read()).group(1)

CONTENTURI = "%s/kintera_cms/content/contentlist.asp?cid={%s}" % (URI, fromCID)

FOLDERRE = re.compile('<td class="lbiFolder".*>([^>&][^<]*)<')
CONTENTRE = re.compile("<input[^>]*name=\"content_id\"[^>]*value=\"{([^}]*)}\".*<a[^>]*>([^<]*)")

startFound = False
buff = ""
name = ""
contentIds = {}
for line in getPage(CONTENTURI):
        match = FOLDERRE.search(line)
        if not startFound and match is not None: continue
        else: startFound = True
        end = line.find("</body>") != -1
        if match is not None or end:
                if buff != "":
                        contentIds[name] = {}
                        for content in CONTENTRE.findall(buff):
                                contentIds[name][content[0]] = content[1]
                if end: break
                else:
                        name = match.group(1)
                        buff = line
        else:
                buff += line

CMSLISTURI = "%s/kintera_cms/content/contentlist.asp?cid={%s}" % (URI, toCID)
page = getPage(CMSLISTURI, params).read()
CONTIDRE = re.compile("<(?:td|a)[^>]*onmouseout=\"[^\"]*?{([^}]+)}.*?>([^<&][^<]*)<", re.IGNORECASE)
sectionIds = {}
for section in CONTIDRE.findall(page):
        sectionIds[section[1]] = section[0]

CREATEURI = "%s/kintera_cms/content/contentedit.asp" % URI

for contId in contentIds["News Stories"]:
        CONTURI = "%s/kintera_cms/content/contentedit.asp?cid={%s}&content_id={%s}" % (URI, fromCID, contId)
        params = getParams(getPage(CONTURI).read())
        date = datetime.date(int(params['content_year']), int(params['content_month']), int(params['content_day']))
        if date >= minDate:
                params['content_folder_id'] = "{%s}" % sectionIds["In the News"]
                params['cid'] = "{%s}" % toCID
                for section in ['content_summary', 'content_body']:
                        for entites in [["&lt;", "<"], ["&gt;", ">"], ["&quot;", "\""],
                                        ["&amp;", "&"], ["&amp;", "&"], [" -- ", "&mdash;"],
                                        ["\\.\\.\\.", "&hellip;"]]:
                                params[section] = re.sub(entites[0], entites[1], params[section])
                                
                params['content_id'] = ""
                params['content_nowysiwyg'] = "1"
                params['cnt_pubnow'] = "1"
                params['formvalidate'] = "1"
                params['createbtn'] = "1"
                print "Migrating: \"%s\"" % params['content_title']
                getPage(CREATEURI, params)
