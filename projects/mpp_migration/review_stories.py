#!/opt/local/bin/python

# for another program to work, I need for every state site to have a listing
# of all the news stories

import getpass
import os, stat, sys
import re, time
import datetime, time
import url_utils
import MySQLdb

# First, log into Kintera and establish the cookies and whatnot

kinteraUsername = "wholcombmpp"
dbName = "kintera_prep"
mysqlUser = "root"

try:
        password = os.environ['KINTERAPASS']
except KeyError:
        password = getpass.getpass("(KINTERAPASS) Kintera Password: ")

db = MySQLdb.connect(user=mysqlUser, db=dbName)

URI = "https://www.kintera.com"
LOGINURI = "%s/KINTERA_Sphere/login/asp/login.asp?use=yes" % URI

print "Logging into Kintera as: %s" % kinteraUsername

params = {"LoginName" : kinteraUsername, "Password" : password }
url_utils.getPage(LOGINURI, params)

# Next get a list of all the CMSes

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
FOLDERRE = re.compile('<td class="lbiFolder".*>([^>&][^<]*)<')
CONTENTRE = re.compile("<input[^>]*name=\"content_id\"[^>]*value=\"{([^}]*)}\".*<a[^>]*>([^<]*)")

for cmsId in cmsIds:
    if cmsId.find("MPP: "):
        continue
    location = cmsId[5:]

    cursor = db.cursor()
    cursor.execute("select id from location where name = %s", location)
    stateRow = cursor.fetchone()
    if stateRow is None:
            print "'%s' is not in the database" % location
            continue
    locationId = stateRow[0]

    print "Processing: '%s' (%s)" % (location, locationId)

    SITELISTURI = "%s/kintera_cms/welcome/start.asp?acid=%s&wsid=%s" % (URI, ACID, cmsIds[cmsId])
    cid = CIDRE.search(url_utils.getPage(SITELISTURI).read()).group(1)

    CONTENTURI = "%s/kintera_cms/content/contentlist.asp?cid={%s}" % (URI, cid)
    contentList = url_utils.getPage(CONTENTURI).read()

    folderNames = ""
    EXPRE = re.compile("fExpandCollapse\('([^']*?)'")
    for folderName in EXPRE.findall(contentList):
            folderNames += "," + folderName

    params = url_utils.getParams(contentList, "docForm")
    params["expanded_content_areaid_list"] = folderNames
    
    startFound = False
    buff = ""
    name = ""
    contentIds = {}
    for line in url_utils.getPage(CONTENTURI, params):
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

    missingCount = 0
    for newsId, title in contentIds["News Stories"].items():
            cursor.execute("select article.id from itemlocation left join article on item_id = article.id where title = %s and location_id = %s", (title, locationId))
            articleRow = cursor.fetchone()
            if articleRow is None:
                print "  Missing Story: '%s'" % title
                missingCount += 1
            else:
                cursor.execute("update article set kintera_id = %s where id = %s", (newsId, articleRow[0]))
                # print "  Set Kintera ID for '%s' to '%s'" % (title, newsId)

    CREATEURI = "%s/kintera_cms/content/contentedit.asp?cid={%s}" % (URI, cid)
    createPage = url_utils.getPage(CREATEURI).read()
    params = url_utils.getParams(createPage)
    params['content_nowysiwyg'] = "1"
    params['cnt_pubnow'] = "1"
    params['formvalidate'] = "1"
    params['okbtn'] = "1"
    params['createbtn'] = "1"

    FIDRE = re.compile("<option\\s+value=\"{([^}]+)}\"[^>]>([^<]+)")
    contentFolders = {}
    for folder in FIDRE.findall(createPage):
        contentFolders[folder[1].rstrip()] = folder[0]

    articlesSQL = ("select title, subtitle, author.name, source.name, publication_date, text" +
                   " from article inner join itemlocation on (article.id = itemlocation.item_id) " +
                   " left join author on (article.author_id = author.id)" +
                   " left join source on (article.source_id = source.id)" +
                   " left join type on (article.type_id = type.id)" +
                   " where location_id = %s " % locationId +
                   " and type.name = 'news' and kintera_id is null")
    cursor.execute(articlesSQL)
    articleCount = 0
    while 1:
            newsRow = cursor.fetchone()
            if newsRow is None: break
            articleCount += 1
            for property in ['title', 'subtitle', 'summary', 'author', 'year', 'month', 'day', 'body']:
                key = 'content_' + property
                if params.has_key(key): del params[key]
            
            params['content_folder_id'] = "{%s}" % contentFolders["News Stories"]
            params['content_title'] = newsRow[0]
            if newsRow[1] is not None: params['content_subtitle'] = newsRow[1]
                        
            #
            # In Kintera is is not possible to show the byline in an inline content listing
            # so, the summary field has to be used as a workaround.
            #
            authorField = "content_summary" # content_author
            if newsRow[2] is not None: params['content_author'] = newsRow[2]
                        
            params[authorField] = "<div class='byline'>"
            if newsRow[2] is not None: params[authorField] +=  "<div class='author'>%s</div>" % newsRow[2]
            if newsRow[4] is not None:
                params['content_year'] = newsRow[4].year
                params['content_month'] = newsRow[4].month
                params['content_day'] = newsRow[4].day
                params[authorField] +=  ("<div class='date'>%s</div>"
                                         % (newsRow[4].strftime("%B %%s, %Y") % newsRow[4].strftime("%d").lstrip("0")))
                if newsRow[3] is not None: params[authorField] +=  "<div class='source'><cite>%s</cite></div>" % newsRow[3]
            params[authorField] +=  "</div>"

            params['content_body'] = newsRow[5]

            url_utils.getPage(CREATEURI, params)
            print "  Added: %s" % (params['content_title'])

    print " Total:: Missing: %s / Added: %s (%s)" % (missingCount, articleCount, (missingCount + articleCount))
