#!/opt/local/bin/python
# This is the darwinports interpreter which has the MySQLdb library installed

#
# Script to take the contents of MPP's sitebuilder database and upload the information into Kintera
# Will Holcomb <wholcomb@gmail.com> 2006/04/12
#

import MultipartPostHandler
import urllib, urllib2
import cookielib
import MySQLdb
from xml import dom
from xml.dom.xmlbuilder import DOMInputSource, DOMBuilder
import getpass
import os, stat, sys
import re, time

setupSQL = "prep_db_setup.sql"
markupProgram = "./markup.pl"
kinteraUsername = "wholcombmpp"
#
# The first step is to take the information out of the site builder and put it in a more
# queriable form. The database has been replicated localy in the mpp database.
#

oldDbName = "mpp"
newDbName = "kintera_prep"

mysqlUser = "will"
mysqlUser = "root"

limitSuffix = '' # "limit 40"

doUpdate = True
doReset = False
createContent = False

#
# These are replacements made for sections when a state is copied
#
STATEREPRE = re.compile("%state%", re.IGNORECASE) # replaced with the location name
ABBREPRE = re.compile("%abbreviation%", re.IGNORECASE) # replaced with the location abbreviation

#
# Sections to run replacements on
#
replaceableSections = ["MPP %state%", "Home", "In the News Header", "Letters to the Editor Header",
                       "Press Releases Header", "Take Action Header", "Alerts Header", "Legislation Header",
                       "Tell a Friend", "Tell a Friend Wrapper", "Feedback Wrapper"]

#
# Mappings between sitebuilder types and Kintera content folders. These folders are
# where each of these content items are stored
#
typeFolders = { "news" : "News Stories", "alert" : "Alerts", "pr" : "Press Releases", "action" : "Action Items",
                "letter" : "Letters to the Editor" }
typeIds = {}
for type in typeFolders.keys():
        typeIds[type] = len(typeIds) + 1


def replaceEntites(html):
        if html is not None:
                for entites in [["&lt;", "<"], ["&gt;", ">"], ["&quot;", "\""],
                                ["&amp;", "&"], ["&amp;", "&"], [" -- ", "&mdash;"],
                                ["\\.\\.\\.", "&hellip;"]]:
                        html = re.sub(entites[0], entites[1], html)
        return html

def markup(text):
        if text is not None:
                (fdin, fdout) = os.popen2(markupProgram, "rw")
                fdin.write(text)
                fdin.close()
                text = fdout.read()
                fdout.close()
        return text

def showdate(date):
        if date is None: return None
        else: return date.strftime("%Y/%m/%d %H:%M:%S")

def ses(count):
        if count == 1: return ""
        else: return "s"

if doReset:
        db = MySQLdb.connect(user=mysqlUser, db=oldDbName)
        db.query("drop database %s" % newDbName)
        db.close()

#
# I only want to do the migration if the database doesn't already exist. Otherwise the
# data might already be present and processed, and I would corrupt it.
#
try:
        olddb = MySQLdb.connect(user=mysqlUser, db=oldDbName)
        newdb = MySQLdb.connect(user=mysqlUser, db=newDbName)
        print "Skipping Migration: %s already exists" % newDbName
        doUpdate = False
except MySQLdb.Error, details:
        newdb = MySQLdb.connect(user=mysqlUser)
        print "Creating Database: %s" % newDbName
        newdb.query("create database %s" % newDbName)
        newdb.close()
        doUpdate = True

# grant all privileges on kintera_prep.* to 'will'@'localhost';
# flush privileges;

if doUpdate:
        newdb = MySQLdb.connect(user=mysqlUser, db=newDbName)

        tableCursor = newdb.cursor()

        try:
                print "Loading Setup: %s" % setupSQL
                # cursor.execute has a buffer length that prevents the entire file being passed in
                sqlEndRE = re.compile("^([^;]*;)(.*)$") # end of a line in sql
                buffer = ""
                for line in open(setupSQL):
                        sqlMatch = sqlEndRE.search(line)
                        if sqlMatch is None:
                                buffer += line
                        else:
                                buffer += sqlMatch.group(1)
                                tableCursor.execute(buffer)
                                buffer = sqlMatch.group(2)
                if buffer is not "":
                        tableCursor.execute(buffer)
        except IOError:
                print "Error: Missing SQL Setup: ", setupSQL

        for type in typeFolders.keys():
                tableCursor.execute("insert into type (id, name) values (%s, %s)",
                                    (typeIds[type], type))

        tableCursor.close()

        newsSQL = ("select id, timestamp, date, title, xml, type from pool where " +
                   " or ".join(["type = '%s'" % type for type in typeFolders.keys()]))

        newsCursor = olddb.cursor()
        newsCursor.execute(newsSQL)

        def getTag(tagName, xml):
                regex = re.compile("<%s>(?P<name>[^<]*)" % tagName)
                content = None
                match = regex.search(xml)
                if match is not None:
                        content = replaceEntites("%s" % match.group('name'))
                return content

        entryCursor = newdb.cursor()

        def getUID(name, table, column = "name"):
                if name is not None:
                        entryCursor.execute("select id from %s where %s = %%s" % (table, column), name)
                        uidRow = entryCursor.fetchone()
                        if uidRow is None:
                                entryCursor.execute("insert into %s (%s) values (%%s)" % (table, column), name)
                                uidRow = entryCursor.execute("select last_insert_id()")
                                uidRow = entryCursor.fetchone()
                        return uidRow[0]
                else:
                        return name
        
        while 1:
                row = newsCursor.fetchone()
                if row is None: break
                xml = row[4]
                title = replaceEntites(row[3])
                if title is None: title = "<i>Untitled</i>"
                id = row[0]
                subtitle = getTag("subtitle", xml)
                author = getTag("(author|byline)", xml)
                authorId = getUID(author, "author")
                source = getTag("source", xml)
                sourceId = getUID(source, "source")
                pubDate = row[2]
                typeId = typeIds[row[5]]
                print "Processing: [%s]: (%s) %s" % (showdate(pubDate), id, title)
                text = markup(getTag("text", xml))
                entryCursor.execute("""insert into article (id, type_id, title, subtitle, author_id, source_id, text, publication_date)
                                       values (%s, %s, %s, %s, %s, %s, %s, %s)""",
                                    (id, typeId, title, subtitle, authorId, sourceId, text, pubDate))

        states = {"AL" : "Alabama",              "AK" : "Alaska",        "AS" : "American Samoa",
                  "AZ" : "Arizona",              "AR" : "Arkansas",      "CA" : "California",
                  "CO" : "Colorado",             "CT" : "Connecticut",   "DE" : "Delaware",
                  "DC" : "District of Columbia", "FL" : "Florida",       "GA" : "Georgia",
                  "GU" : "Guam",                 "HI" : "Hawaii",        "ID" : "Idaho",
                  "IL" : "Illinois",             "IN" : "Indiana",       "IA" : "Iowa",
                  "KS" : "Kansas",               "KY" : "Kentucky",      "LA" : "Louisiana",
                  "ME" : "Maine",                "MD" : "Maryland",      "MA" : "Massachusetts",
                  "MI" : "Michigan",             "MN" : "Minnesota",     "MS" : "Mississippi",
                  "MO" : "Missouri",             "MT" : "Montana",       "NE" : "Nebraska",
                  "NV" : "Nevada",               "NH" : "New Hampshire", "NJ" : "New Jersey",
                  "NM" : "New Mexico",           "NY" : "New York",      "NC" : "North Carolina",
                  "ND" : "North Dakota",         "OH" : "Ohio",          "OK" : "Oklahoma",
                  "OR" : "Oregon",               "PW" : "Palau",         "PA" : "Pennsylvania",
                  "PR" : "Puerto Rico",          "RI" : "Rhode Island",  "SC" : "South Carolina",
                  "SD" : "South Dakota",         "TN" : "Tennessee",     "TX" : "Texas",
                  "UT" : "Utah",                 "VT" : "Vermont",       "VI" : "Virgin Islands",
                  "VA" : "Virginia",             "WA" : "Washington",    "WV" : "West Virginia",
                  "WI" : "Wisconsin",            "WY" : "Wyoming",
                  "USA" : "Federal Policies",
                  "DD" : "DD?", "WDC" : "WDC?",  "DCTR" : "DCTR?", "DCI" : "DCI?" }

        newsCursor.execute("select distinct category from poolcategory")
        idsCursor = olddb.cursor()

        while 1:
                row = newsCursor.fetchone()
                if row is None: break
                abbreviation = row[0]
                entryCursor.execute("insert into location (abbreviation, name) values (%s, %s)",
                                    (abbreviation, states[abbreviation]))
                entryCursor.execute("select last_insert_id();")
                locationRow = entryCursor.fetchone()
                locationId = locationRow[0]
                print "Processing Location: %s (%s): [%s]"% (states[abbreviation], abbreviation, locationId)
                idsCursor.execute("select distinct id from poolcategory where category = %s", abbreviation)
                while 1:
                        idRow = idsCursor.fetchone()
                        if idRow is None: break
                        entryCursor.execute("insert into itemlocation (item_id, location_id) values (%s, %s)",
                                            (idRow[0], locationId))

        billsSQL = "select id, timestamp, date, title, xml, type from pool where type = 'bill';"
        newsCursor.execute(billsSQL)

        while 1:
                row = newsCursor.fetchone()
                if row is None: break
                id = row[0]
                xml = row[4]
                title = replaceEntites(row[3])
                if title is None: title = "<i>Untitled</i>"
                number = getTag("number", xml)
                summary = getTag("summary", xml)
                sponsors = getTag("sponsors", xml)
                history = replaceEntites(getTag("billhist", xml))
                status = replaceEntites(getTag("billstatus", xml))
                statusId = getUID(status, "status", "text")
                pubDate = row[2]
                print "Processing: [%s]: (%s) %s" % (showdate(pubDate), id, title)
                text = getTag("text", xml)
                text = markup(text)
                entryCursor.execute("""insert into bill (id, title, number, summary, sponsors, history, status_id, text, publication_date)
                                       values (%s, %s, %s, %s, %s, %s, %s, %s, %s)""",
                                    (id, title, number, summary, sponsors, history, statusId, text, pubDate))
                for index in range(1,4):
                        title = replaceEntites(getTag("misctitle%s", xml))
                        text = replaceEntites(getTag("misctext%s", xml))
                        if title is not None or text is not None:
                                entryCursor.execute("insert into note (bill_id, title, text) values (%s, %s, %s)",
                                                    (id, title, text))

if not createContent:
        sys.exit(0)

sitename = "Created Folder - %s" % time.strftime("%Y/%m/%d %H:%M:%S")
sitedir = "site_template"

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

templateId = "ftJYL6NFIoF" # template id for -- Script State Template --

locationCursor = newdb.cursor()
locationCursor.execute("select id, name, abbreviation from location order by abbreviation limit 1")
while 1:
        locationRow = locationCursor.fetchone()
        if locationRow is None: break
        params = { "website_type" : "I",
                   "website_name" : "Generated: MPP %s" % (locationRow[1]),
                   "cms_template_id" : templateId,
                   "description" : "Site for %s (%s) generated %s by %s"
                                   % (locationRow[1], locationRow[2], sys.argv[0], time.strftime("%d %B %Y %H:%M:%S")),
                   "eventRecordAccess" : "825", "eventRecordAccess" : "826" }
        page = getPage(NEWSITEURI, params).read()
        REGRE = re.compile("strTarget\\s*=\\s*\"(../)*([^\"]+)\"")
        REGURI = REGRE.search(page).group(2)
        page = getPage(URI + "/" + REGURI).read() # this is needed to cause the page to actually generate
        CIDRE = re.compile("name=\"cid\"\\s+value=\"{([^}]+)}\"")
        cid = CIDRE.search(page).group(1)
        print "Creating Site: %s (%s)" % (params['website_name'], cid)

        CMSLISTURI = "%s/kintera_cms/content/contentlist.asp?cid={%s}" % (URI, cid)
        page = getPage(CMSLISTURI, params).read()
        CONTIDRE = re.compile("<a href.*onmouseout=\"[^\"]*?{([^}]+)}.*?>(.*?)</a>", re.IGNORECASE)
        sectionIds = {}
        for section in CONTIDRE.findall(page):
                sectionIds[section[1]] = section[0]

        SITELISTURI = "%s/kintera_cms/pp/pplist.asp?cid={%s}" % (URI, cid)
        page = getPage(SITELISTURI, params).read()
        BINIDRE = re.compile("<a .*?bin_id=\"?{([^}]+)}.*?>(.*?)</a>", re.IGNORECASE)
        binIds = {}
        for item in BINIDRE.findall(page):
                binIds[item[1]] = item[0]

        CEDITURI = "%s/kintera_cms/content/contentedit.asp?cid={%s}&content_id={%s}"
        SITEEDITURI = "%s/kintera_cms/apps/s/link.asp?cid={%s}&bin_id={%s}"

        def replaceReplacables(editURI):
                params = getParams(getPage(editURI).read())
                for section in ['content_body', 'content_title', 'content_subtitle', 'bin_title',
                                'spreadword_subject', 'spreadword_appendmsg', 'spreadword_body']:
                        if params.has_key(section):
                                params[section] = STATEREPRE.sub(locationRow[1], params[section])
                                params[section] = ABBREPRE.sub(locationRow[2], params[section])
                                params[section] = replaceEntites(params[section])

                params['content_nowysiwyg'] = "1"
                params['cnt_pubnow'] = "1"
                params['formvalidate'] = "1"
                params['updatebtn'] = "1"
                params['okbtn'] = "1"
                return getPage(editURI, params)
                
        for section in replaceableSections:
                found = False
                if sectionIds.has_key(section):
                        replaceReplacables(CEDITURI % (URI, cid, sectionIds[section]))
                        print ("Replaced content %%state%% => %s and %%abbreviation%% => %s in %s" %
                               (locationRow[1], locationRow[2], section))
                        found = True
                if binIds.has_key(section):
                        replaceReplacables(SITEEDITURI % (URI, cid, binIds[section]))
                        print ("Replaced site element %%state%% => %s and %%abbreviation%% => %s in %s" %
                               (locationRow[1], locationRow[2], section))
                        found = True
                if not found:
                        print "Error: No ID found for section: '%s'" % section
        
        TELLEDITURI = "%s/kintera_cms/apps/email/spreadWord.asp?cid={%s}&bin_id={%s}" % (URI, cid, binIds['Tell a Friend'])
        replaceReplacables(TELLEDITURI)

        frontpageCursor = olddb.cursor()
        frontpageCursor.execute("select title, timestamp, text from page where path = %s", locationRow[2])
        section = "Frontpage Content"
        while 1:
                frontpageRow = frontpageCursor.fetchone()
                if frontpageRow is None: break
                editURI = CEDITURI % (URI, cid, sectionIds[section])
                params = getParams(getPage(editURI).read())
                params['content_body'] = "<h1>%s</h1>\n" % frontpageRow[0]
                params['content_body'] += ("<h2 class='updatenotice'>Last update: %s</h2>\n" %
                                           (frontpageRow[1].strftime("%B %%s, %Y") % frontpageRow[1].strftime("%d").lstrip("0")))
                if frontpageRow[2] is not None:
                        print "Setting Frontpage: %s" % frontpageRow[0]
                        params['content_body'] += markup(frontpageRow[2])
                else:
                        print "Error: '%s' has no frontpage content" % frontpageRow[0]
                params['content_nowysiwyg'] = "1"
                params['cnt_pubnow'] = "1"
                params['formvalidate'] = "1"
                params['updatebtn'] = "1"
                getPage(editURI, params)

        CREATEURI = "%s/kintera_cms/content/contentedit.asp?cid={%s}" % (URI, cid)
        page = getPage(CREATEURI).read()

        FIDRE = re.compile("<option\\s+value=\"{([^}]+)}\"[^>]>([^<]+)")
        contentFolders = {}
        for folder in FIDRE.findall(page):
                contentFolders[folder[1].rstrip()] = folder[0]

        DATERE = re.compile("(\d{4})-0*(\d{1,2})-0*(\d{1,2})")
        newsCursor = newdb.cursor()

        def addArticles(type, params):
                if contentFolders[typeFolders[type]] is None:
                        print "Error: Could not find folder for '%s'" % typeFolders[type]
                        return None
                params['content_folder_id'] = "{%s}" % contentFolders[typeFolders[type]]
                articlesSQL = ("select title, subtitle, author.name, source.name, publication_date, text" +
                               " from article inner join itemlocation on (article.id = itemlocation.item_id) " +
                               " left join author on (article.author_id = author.id)" +
                               " left join source on (article.source_id = source.id)" +
                               " where itemlocation.location_id = %s " % locationRow[0] +
                               " and type_id = %s %s" % (typeIds[type], limitSuffix))
                newsCursor.execute(articlesSQL)
                articleCount = 0
                while 1:
                        newsRow = newsCursor.fetchone()
                        if newsRow is None: break
                        articleCount += 1
                        for property in ['title', 'subtitle', 'summary', 'author', 'year', 'month', 'day', 'body']:
                                key = 'content_' + property
                                if params.has_key(key): del params[key]

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

#                        if type == "alert":
#                                preface = "Please use the link below to"
#                                if params['content_body'].startsWith(
                        
                        getPage(CREATEURI, params)
                        print "Added: [%s]: %s" % (type, params['content_title'])
                print ("Added %s article%s [%s:%s] => '%s':{%s} to %s" %
                       (articleCount, ses(articleCount), type, typeIds[type], typeFolders[type],
                        contentFolders[typeFolders[type]], locationRow[1]))
                if type == "pr" and articleCount == 0:
                        editURI = SITEEDITURI % (URI, cid, binIds[typeFolders['pr']])
                        params['bin_active'] = "0"
                        getPage(editURI, params)
                        print "No Press Releases: Section Disabled"
                if type == "letter" and articleCount <= 1:
                        GENEDITURI = ("%s/kintera_cms/apps/nl/newsletter2.asp?cid={%s}&bin_id={%s}" %
                                      (URI, cid, binIds[typeFolders['letter']]))
                        genparams = getParams(getPage(GENEDITURI).read())
                        if genparams.has_key('contents'): del genparams['contents']
                        genparams['formvalidate'] = "1"
                        genparams['content_nowysiwyg'] = "1"
                        genparams['cnt_pubnow'] = "1"
                        genparams['okbtn'] = "1"
                        getPage(GENEDITURI, genparams)
                        print "Removed pagination from: %s" % typeFolders['letter']

        params = getParams(page)
        params['formvalidate'] = "1"
        params['createbtn'] = "1"
        params['content_nowysiwyg'] = "1"
        params['cnt_pubnow'] = "1"

        for type in typeFolders.keys():
                addArticles(type, params)

        billsSQL = ("select bill.id, title, number, summary, sponsors, history, status.text, publication_date, bill.text" +
                    " from bill inner join itemlocation on (bill.id = itemlocation.item_id)" +
                    " left join status on (bill.status_id = status.id)" +
                    " where itemlocation.location_id = %s" % locationRow[0])
        newsCursor.execute(billsSQL)

        params['content_folder_id'] = "{%s}" % contentFolders['Bills']
        # Matches the status message for dead bills
        DEADRE = re.compile(".*(die|dead).*", re.IGNORECASE)
        while 1:
                billRow = newsCursor.fetchone()
                if billRow is None: break
                id = billRow[0]
                params['content_title'] = "%s: %s" % (billRow[2], replaceEntites(billRow[1]))
                params['content_author'] = "Sponsored By: %s" % billRow[4]
                status = billRow[6]
                if status is not None: billIsDead = DEADRE.match(status) is not None
                else: billIsDead = False
                summaryClass = "summary"
                if billIsDead:
                        summaryClass += " deadbill"
                params['content_summary'] = "<div class='%s'>" % summaryClass
                if billIsDead: params['content_summary'] += "<div class='deadnotice'>This bill is dead.</div>"
                if billRow[3] is not None:
                        params['content_summary'] += billRow[3]
                params['content_summary'] += "<div class='status'>%s</div>" % status
                params['content_summary'] += "</div>"
                if billRow[7] is not None:
                        params['content_year'] = billRow[7].year
                        params['content_month'] = billRow[7].month
                        params['content_day'] = billRow[7].day
                else:
                        for property in ['year', 'month', 'day']:
                                params['content_' + property] = ""
                params['content_body'] = "<div class='bill'>"
                if billRow[8] is not None: params['content_body'] += "<div class='text'>%s</div>" % billRow[8]
                params['content_body'] += "<hr />"
                if billRow[5] is not None: params['content_body'] += "<div class='history'>%s</div>" % billRow[5]
                params['content_body'] += "</div>"

                page = getPage(CREATEURI, params)
                print "Added: [bill]: %s" % (params['content_title'])
                
sys.exit()

CMSURI = "%s/kintera_sphere/comm/cms_website/cmsWebsiteList.aspx" % URI
page = getPage(CMSURI).read()
ACIDRE = re.compile("acid=([^&]*)")
acid = ACIDRE.search(page).group(1)
SITEIDRE = re.compile("ShowMenu\\s*\\([^,]*,\\s*'([^']+)'[^>]+>Marijuana Policy Project")
siteid = SITEIDRE.search(page).group(1)

NEWSITEURI = "%s/kintera_sphere/comm/cms_website/websiteEditMisc.asp?action=submit" % URI

params = {"website_type" : "I", "website_name" : sitename,
          "eventRecordAccess" : 825, "eventRecordAccess" : 826,
          "description" : "Test website created by Will's python script"}
for var in ["wid", "samewindow", "t", "edit", "cms_template_id", "cms_personalization_category_id", "cms_parent_id"]:
    params[var] = ""

page = getPage(NEWSITEURI, urllib.urlencode(params)).read()
REGRE = re.compile("strTarget\\s*=\\s*\"(../)*([^\"]+)\"")
REGURI = REGRE.search(page).group(2)
page = getPage(URI + "/" + REGURI).read()

CIDRE = re.compile("name=\"cid\"\\s+value=\"{([^}]+)}\"")
cid = CIDRE.search(page).group(1)

# SITEURI="%s/kintera_cms/welcome/start.asp?acid=%s&wsid=%s" % (URI, acid, siteid)
# 
# page = getPage(SITEURI).read()
# CNTRE = re.compile("<a.*href=\"([^\"]*)\"[^>]*>Content Management")
# CNTURI = CNTRE.search(page).group(1)
# 
# CIDRE = re.compile("cid={([^}]+)}")
# cid = CIDRE.search(CNTURI).group(1)
# 

print "ACID/SITEID/CID: \"%s\" / \"%s\" / \"%s\"" % (acid, siteid, cid)

FNEWURI = "%s/kintera_cms/content/folder.asp?cid={%s}&new=1" % (URI, cid)

params = {"cid" : "{" + cid + "}", "forminited" : 1,
          "folder_title" : "Python Test Folder", "createbtn" : 1 }

FMAKEURI = "%s/kintera_cms/content/folder.asp?cid={%s}" % (URI, cid)
for var in ["folder_id", "folderDelete", "noopener", "targetURL", "folder_description"]:
    params[var] = ""

getPage(FMAKEURI, urllib.urlencode(params))

def uploadFile(filename, fid = ""):
    print "Uploading: ", filename
    UPLOADURI = "%s/kintera_cms/files/filesupload2.asp?cid={%s}" % (URI, cid)
    if fid is not "" and not fid.startswith("{"):
        fid = "{%s}" % fid

    params = {"targetURL" : "%s/kintera_cms/files/fileslist.asp?cid={%s}-*-imageonly=" % (URI, cid),
              "FILE1" : open(filename, "rb"), "Folder1" : fid}
    for var in ["fusage", "bin_id", "fullurl", "imageonly", "custom", "single", "eventtype_bin_id",
               "opener_stuff_id", "opener_stuff_name", "opener_submit", "noopener"]:
        params[var] = ""

    for i in range(1, 6):
        for base in ["FILE", "Folder", "DESCRIPTION", "KEYWORDS"]:
            key = "%s%s" % (base, i)
            if key not in params:
                params[key] = ""

    getPage(UPLOADURI, params)

FILEFURI = "%s/kintera_cms/files/folder.asp" % URI

for file in os.listdir(sitedir):
    file = os.path.join(sitedir, file)
    if os.path.isdir(file):
        foldername = file.split("/")[-1]
        params = {"cid" : "{%s}" % cid, "forminited" : 1, "createbtn" : 1,
          "folder_title" : foldername}
        for var in ["folder_id", "folderDelete", "targetURL", "folder_description"]:
            params[var] = ""
        getPage(FILEFURI, params)
        FILELISTURI = "%s/kintera_cms/files/fileslist.asp?cid={%s}&new=1" % (URI, cid)
        page = getPage(FILELISTURI).read()
        FIDRE = re.compile("<option\\s+value=\"{([^}]+)}\"[^>]>" + foldername)
        fid = FIDRE.search(page).group(1)
        
        for subfile in os.listdir(file):
            uploadFile(os.path.join(file, subfile), fid)
    else:
        uploadFile(file, "")

