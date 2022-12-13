#!/opt/local/bin/python

#
# The main site needs friendly links of the form:
#   http://mpp.org/CA/ -> http://ca.mpp.org
#   http://ca.mpp.org/news/
#   http://mpp.org/CA/news/ -> http://ca.mpp.org/news/
#   http://mpp.org/CA/news.html -> http://ca.mpp.org/news/
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
url_utils.getPage(LOGINURI, params).read()

CMSSURI = "%s/kintera_sphere/comm/cms_website/cmsWebsiteList.aspx" % (URI)
params = url_utils.getParams(url_utils.getPage(CMSSURI, params).read())

params["_ctl0:LR1:Pgr"] = "500"
page = url_utils.getPage(CMSSURI, params).read()

# <a href="#" onmouseover="ShowMenu(event,'nuIVL8P2G');return false;" onmouseout="delayhidemenu();return false;">vmpp.org</a></td>
CMSIDRE = re.compile("<a href.*ShowMenu\(event,'([^']+)'\)[^>]+>(.*?)</a>", re.IGNORECASE)
cmsIds = {}

for cms in CMSIDRE.findall(page):
        cmsIds[cms[1]] = cms[0]

# copied from cms list javascript 
ACID = "8nJKJQPlEeKWE"
CIDRE = re.compile("cid={(.*?)}")

# <a class="noUnderline" href="JavaScript: fExpandCollapse('B_12', 1);" onmouseover="Javascript: this.style.cursor='hand'; fShowMoveToFolderDialog(event,'12', true);" onmouseout="JavaScript: this.style.cursor=''; fShowMoveToFolderDialog(event,'12', false);"><img src="../images/plus.jpg" border="0" align="baseline"><img src="../images/1x1t.gif" border="0" width="7" height="1"><img src="../images/folder_closed.gif" border="0" align="baseline"><img src="../images/1x1t.gif" border="0" width="7" height="1">Primary Navigation</a>

SITELISTURI = "%s/kintera_cms/welcome/start.asp?acid=%s&wsid=%s" % (URI, ACID, cmsIds["Marijuana Policy Project"])
mainCID = CIDRE.search(url_utils.getPage(SITELISTURI).read()).group(1)

ARCHIVEIDRE = re.compile("fShowMoveToFolderDialog\([^,]+, *'([^']+)'.*?Archive")
COVERIDRE = re.compile("fShowMoveToFolderDialog\([^,]+, *'([^']+)'.*?Cover")

for key, cmsId in cmsIds.items():
        if key.find("MPP: ") == 0 and not key.find("MPP: Alabama") == 0:
                print "Processing: %s" % key
                SITELISTURI = "%s/kintera_cms/welcome/start.asp?acid=%s&wsid=%s" % (URI, ACID, cmsId)
                CID = CIDRE.search(url_utils.getPage(SITELISTURI).read()).group(1)
                NEWLINKURI = "%s/kintera_cms/apps/s/link.asp?cid={%s}&bin_pagetype_id=6" % (URI, CID)
                params = url_utils.getParams(url_utils.getPage(NEWLINKURI).read())
                params["bin_title"] = "News Link"
                PARENTSELURI = "%s/kintera_cms/pp/ppselect2.asp?cid={%s}&move=2" % (URI, CID)
                params["bin_location"] = ARCHIVEIDRE.search(url_utils.getPage(PARENTSELURI).read()).group(1)
                ITEMSURI = "%s/kintera_cms/pp/ppselect.asp?cid={%s}&opener_bin_id=bin_linkpp" % (URI, CID)
                itemsParams = url_utils.getParams(url_utils.getPage(ITEMSURI).read(), "docForm2")
                itemsParams["search_bin_name"] = "More News"
                itemsParams = url_utils.getParams(url_utils.getPage(ITEMSURI, itemsParams).read())
                params["use_link"] = "1"
                params["bin_linkpp"] = itemsParams["action_bin_id"].split("}")[0] + "}"
                params["createbtn"] = "1"
                params["formvalidate"] = "1"
                NEWLINKURI = "%s/kintera_cms/apps/s/link.asp" % (URI)
                url_utils.getPage(NEWLINKURI, params)
                print "  Creating /news/ link to bin id %s" % params["bin_linkpp"]

                # the link has to be created before it can be edited to add the friendly url

                SITELISTURI = "%s/kintera_cms/pp/pplist.asp?cid={%s}" % (URI, CID)
                page = url_utils.getPage(SITELISTURI).read()
                BINIDRE = re.compile("<a .*?bin_id=\"?{([^}]+)}.*?>(.*?)</a>", re.IGNORECASE)
                binIds = {}
                for item in BINIDRE.findall(page):
                        binIds[item[1]] = item[0]

                LINKEDITURI = "%s/kintera_cms/apps/s/link.asp?cid={%s}&bin_id={%s}&tabpage=Advanced" % (URI, CID, binIds["News Link"])
                linkParams = url_utils.getParams(url_utils.getPage(LINKEDITURI).read())
                FRIENDLYURI = "%s/kintera_cms/pp/editfriendlyurl.asp?cid={%s}&bin_id={%s}" % (URI, CID, binIds["News Link"])
                params = url_utils.getParams(url_utils.getPage(FRIENDLYURI).read())

                DOMAINRE = re.compile("^http://(..).*$")
                abbreviation = DOMAINRE.sub("\\1", linkParams["bin_url"])
                params['domain'] = abbreviation
                params['dirName'] = "news/"
                params['content_nowysiwyg'] = "1"
                params['cnt_pubnow'] = "1"
                params['formvalidate'] = "1"
                params['okbtn'] = "1"

                site = "%s.mpp.org" % abbreviation

                print "  Masking http://%s/news/ => %s" % (site, linkParams["bin_url"])
                url_utils.getPage(FRIENDLYURI, params)

                NEWLINKURI = "%s/kintera_cms/apps/s/link.asp?cid={%s}&bin_pagetype_id=6" % (URI, mainCID)
                params = url_utils.getParams(url_utils.getPage(NEWLINKURI).read())
                params["bin_title"] = "%s Link" % site
                PARENTSELURI = "%s/kintera_cms/pp/ppselect2.asp?cid={%s}&move=2" % (URI, CID)
                params["bin_location"] = COVERIDRE.search(url_utils.getPage(PARENTSELURI).read()).group(1)
                params["use_link"] = ""
                params["bin_link"] = "http://%s" % site
                params["createbtn"] = "1"
                params['cnt_pubnow'] = "1"
                params["formvalidate"] = "1"
                NEWLINKURI = "%s/kintera_cms/apps/s/link.asp" % (URI)
                url_utils.getPage(NEWLINKURI, params)
                print "  Creating new link to %s" % site

                params["bin_title"] = "%s/news/ Link" % site
                params["bin_link"] = "http://%s/news/" % site
                url_utils.getPage(NEWLINKURI, params)
                print "  Creating new link to %s/news/" % site

                SITELISTURI = "%s/kintera_cms/pp/pplist.asp?cid={%s}" % (URI, mainCID)
                page = url_utils.getPage(SITELISTURI).read()
                binIds = {}
                for item in BINIDRE.findall(page):
                        binIds[item[1]] = item[0]

                FRIENDLYURI = "%s/kintera_cms/pp/editfriendlyurl.asp?cid={%s}&bin_id={%s}" % (URI, mainCID, binIds["%s Link" % site])
                params = url_utils.getParams(url_utils.getPage(FRIENDLYURI).read())
                params['domain'] = "www"
                params['dirName'] = "%s/" % abbreviation.upper()
                params['content_nowysiwyg'] = "1"
                params['cnt_pubnow'] = "1"
                params['formvalidate'] = "1"
                params['okbtn'] = "1"

                print "  Masking http://www.mpp.org/%s/ => %s" % (abbreviation.upper(), linkParams["bin_url"])
                url_utils.getPage(FRIENDLYURI, params)

                FRIENDLYURI = "%s/kintera_cms/pp/editfriendlyurl.asp?cid={%s}&bin_id={%s}" % (URI, mainCID, binIds["%s/news/ Link" % site])
                params = url_utils.getParams(url_utils.getPage(FRIENDLYURI).read())
                params['domain'] = "www"
                params['dirName'] = "%s/news/" % abbreviation.upper()
                params['content_nowysiwyg'] = "1"
                params['cnt_pubnow'] = "1"
                params['formvalidate'] = "1"
                params['okbtn'] = "1"

                print "  Masking http://www.mpp.org/%s/news/ => %s" % (abbreviation.upper(), linkParams["bin_url"])
                url_utils.getPage(FRIENDLYURI, params)
