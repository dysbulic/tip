#!/usr/bin/python

#
# Fixes some problems with the sites created with the site builder import
#

import MultipartPostHandler
import urllib, urllib2
import cookielib
import getpass
import os, stat, sys
import re, time

kinteraUsername = "wholcombmpp"

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

def getPage(url, params = None):
        # print "Opening: ", url
        try:
                return opener.open(url, params)
        except urllib2.URLError, details:
                print "Error (%s): %s" % (url, details)

def getParams(data):
        INPUTRE = re.compile("<input([^>]*)>", re.DOTALL)
        VALUEPAIRRE = re.compile("(\\w+)(?:=(?:\"([^\"]*)\"|(\\S+)))?", re.DOTALL)
        params = {}
        for input in INPUTRE.findall(data):
                properties = {}
                for pair in VALUEPAIRRE.findall(input):
                        properties[pair[0].lower()] = pair[1] + pair[2]
                if properties.has_key('type') and properties['type'].lower() == "checkbox" and not properties.has_key('checked'):
                        pass
                elif properties.has_key('name') and properties.has_key('value'):
                        if params.has_key(properties['name']):
                                if not isinstance(params[properties['name']], list):
                                        params[properties['name']] = [params[properties['name']]]
                                params[properties['name']].append(properties['value'])
                        params[properties['name']] = properties['value']
                elif properties.has_key('name'):
                        params[properties['name']] = None
                elif properties.has_key('type') and (properties['type'] == "reset" or properties['type'] == "submit"):
                        pass
                else:
                        print "Couldn't interpret: '%s'" % input
        return params

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

prefix = "Generated: MPP"

for key, cmsId in cmsIds.items():
        if key.find(prefix) == 0:
                SITELISTURI = "%s/kintera_cms/welcome/start.asp?acid=%s&wsid=%s" % (URI, ACID, cmsId)
                page = getPage(SITELISTURI).read()

                CONTIDRE = re.compile("<a href.*onmouseout=\"[^\"]*?{([^}]+)}.*?>(.*?)</a>", re.IGNORECASE)
                sectionIds = {}
                for section in CONTIDRE.findall(page):
                        sectionIds[section[1]] = section[0]

                CIDRE = re.compile("name=\"cid\"\\s+value=\"{([^}]+)}\"")
                cid = CIDRE.search(page).group(1)

                print "Processing: %s (%s)" % (key, cid)

                #
                # Remove the "Generated:" from the beginning of the site name
                PREFSURI = "%s/kintera_sphere/comm/cms_website/websiteEditMisc.asp?wid=%s&cid={%s}&edit=1" % (URI, cmsId, cid)

                params = getParams(getPage(PREFSURI).read())
                params["website_name"] = params["website_name"].replace(prefix, "MPP:")
                params["save"] = "Submit"

                PREFSCHANGEURI = "%s/kintera_sphere/comm/cms_website/websiteEditMisc.asp?action=submit" % URI
                getPage(PREFSCHANGEURI, params).read()
                print "Renamed to %s" % params["website_name"]

                #
                # Add a link to the news stories in the archive
                ADDLINKURI = "%s/kintera_cms/apps/s/link.asp?cid={%s}&bin_pagetype_id=6" % (URI, cid)
                params = getParams(getPage(ADDLINKURI).read())
                params["bin_title"] = "In the News"
                params["bin_comment"] = "0200"
                params["formvalidate"] = "1"
                params["bin_param"] = "redirect"
                
                params["bin_location"] = "5" # copied rather than searched
                
                params["bin_linkpp"] = "{%s}" % sectionIds["More News Stories&hellip;"]
                params["use_link"] = "1"
                params["bin_relevancy_id_list"] = "93402,95278"
                params["createbtn"] = "1"
                params["wf_pubnow"] = "1"

                ADDLINKURI = "%s/kintera_cms/apps/s/link.asp" % (URI)
                getPage(ADDLINKURI, params).read()
                print "Added a link to \"More News Stories\" (%s)" % sectionIds["More News Stories&hellip;"]

                #
                # Remove the existing In the News content listing
                if not sectionIds.has_key("In the News"):
                        print "No \"In the News\" section"
                else:
                        DELURI = "%s/kintera_cms/pp/pplist.asp?cid={%s}&noopener=1" % (URI, cid)
                        params = getParams(getPage(DELURI).read())
                        params["action"] = "delete"
                        
                        # For removing a content listing
                        params["action_bin_id"] = "{%s}http://www.kintera.com/kintera_cms/apps/nl/newsletter2.asp?" % sectionIds["In the News"]
                        
                        # For removing the hyperlink
                        # params["action_bin_id"] = "{%s}http://www.kintera.com/kintera_cms/apps/s/link.asp?" % sectionIds["In the News"]

                        getPage(DELURI, params)
                        params["action"] = "publishall"
                        getPage(DELURI, params)
                        print "Removed old \"In the News\" section (%s)" % sectionIds["In the News"]

                #
                # Change the pagination type on the news stories
                NEWSURI = "%s/kintera_cms/apps/nl/newsletter2.asp?cid={%s}&bin_id={%s}" % (URI, cid, sectionIds["More News Stories&hellip;"])
                params = getParams(getPage(NEWSURI).read())
                params["paginationtype"] = "1" # Drop-down pagination
                params["formvalidate"] = "1"
                params["bin_title"] = params["bin_title"].replace("&amp;", "&") # Kintera is retarded                
                params["okbtn"] = "1"
                getPage(NEWSURI, params)
                print "Changed pagination on \"More News Stories\""

                LINKURI = "%s/kintera_cms/apps/s/link.asp?cid={%s}&bin_id={%s}" % (URI, cid, sectionIds["Search"])
                params = getParams(getPage(LINKURI).read())
                params["bin_link"] = "/site/lookup.asp?c=glKZLeMQIsG&b=1187333"
                params["formvalidate"] = "1"
                params["okbtn"] = "1"
                getPage(LINKURI, params)
                print "Changed target of \"Search\" link (%s)" % sectionIds["Search"]
        elif key.find("MPP:") == 0:
                SITELISTURI = "%s/kintera_cms/welcome/start.asp?acid=%s&wsid=%s" % (URI, ACID, cmsId)
                page = getPage(SITELISTURI).read()

                CONTIDRE = re.compile("<a href.*onmouseout=\"[^\"]*?{([^}]+)}.*?>(.*?)</a>", re.IGNORECASE)
                sectionIds = {}
                for section in CONTIDRE.findall(page):
                        sectionIds[section[1]] = section[0]

                CIDRE = re.compile("name=\"cid\"\\s+value=\"{([^}]+)}\"")
                cid = CIDRE.search(page).group(1)

                print "Processing \"%s\" (%s)" % (key, cid)

                if not sectionIds.has_key("Frontpage  News Listing"):
                        print "No news listing; skipping"
                else:
                        LISTURI = "%s/kintera_cms/apps/nl/inline2.asp?cid={%s}&bin_id={%s}" % (URI, cid, sectionIds["Frontpage  News Listing"])
                        params = getParams(getPage(LISTURI).read())
                        
                        params["bin_title"] = params["bin_title"].replace("  ", " ")
                        if isinstance(params["bin_display"], list):
                                params["bin_display"].remove("c_date_content")
                        else:
                                params["bin_display"] = params["bin_display"].replace(", c_date_content", "")
                        params["formvalidate"] = "1"
                        params["okbtn"] = "1"
                        
                        #getPage(LISTURI, params)
                        #print "Changed display to: %s" % params["bin_display"]

                NEWSURI = ("%s/kintera_cms/apps/nl/newsletter2.asp?cid={%s}&bin_id={%s}" %
                           (URI, cid, sectionIds["Letters to the Editor"]))
                params = getParams(getPage(NEWSURI).read())

                #for param, value in params.items():
                #        print "params[\"%s\"] = %s" % (param, value)

                if params.has_key("contents"):
                        del params["contents"]

                params["formvalidate"] = "1"
                params["okbtn"] = "1"
                params["bin_display"] = params["bin_display"].replace(",pagination", "")
                
                getPage(NEWSURI, params)
                print "Removed pagination from \"Letters to the Editor\""
        else:
                print "Skipping %s" % key
                
