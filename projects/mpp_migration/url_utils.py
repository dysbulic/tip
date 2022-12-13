#!/opt/local/bin/python

import MultipartPostHandler
import urllib, urllib2
import cookielib
import re, sys

import libxml2dom
from libxml2dom.macrolib import LSException


opener = urllib2.build_opener(urllib2.HTTPCookieProcessor(),
                              MultipartPostHandler.MultipartPostHandler)

def getPage(url, params = None):
        # print "Opening: ", url
        try:
                return opener.open(url, params)
        except urllib2.URLError, details:
                print "Error (%s): %s" % (url, details)

def getParams(data, formName = None, params = {}):
        doc = libxml2dom.parseString(data, html = 1)
        xpath = "//form"
        if formName is not None:
                xpath += "[@name = '%s']" % formName
        for form in doc.xpath(xpath):
                for inputElm in form.xpath(".//input"):
                        elmType = inputElm.getAttribute("type").lower()
                        if elmType == "reset":
                                continue
                        if not elmType == "checkbox" or inputElm.hasAttribute("checked"):
                                params[inputElm.getAttribute("name")] = inputElm.getAttribute("value")
                for selectElm in form.xpath(".//select"):
                        options = selectElm.xpath(".//option[@selected]")
                        if len(options) > 1:
                                print "Error: Multiple selected options not handled: %s (%d)" % (inputElm.getAttribute("name"), len(options))
                        for optionElm in options:
                                params[inputElm.getAttribute("name")] = optionElm.getAttribute("value")
                for textElm in form.xpath("textarea"):
                        params[inputElm.getAttribute("name")] = "".join(inputElm.xpath(".//text()"))
        return params

# Old version that sometimes takes 20 minutes to complete
def getRegexParams(data, formName = None, params = {}):
        if data is None:
                print "Error: No data passed to form processor"
                return params
        if formName is not None:
                FORMRE = re.compile("^.*(<form[^>]*name=['\"]?%s['\"]?[ >].*?</form>).*$" % formName, re.DOTALL)
                formMatch = FORMRE.search(data)
                if formMatch is None:
                        print "Error: Form did not match: %s" % formName
                        return None
                data = formMatch.group(1)
        INPUTRE = re.compile("<input([^>]*)>", re.DOTALL)
        VALUEPAIRRE = re.compile("(\\w+)(?:=(?:\"([^\"]*)\"|(\\S+)))?", re.DOTALL)
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
                elif properties['type'] == "button":
                        print "Skipped button: %s" % properties['value']
                else:
                        print "Couldn't interpret: '%s'" % input
        #SELECTRE = re.compile("<select name=\"?([^>\"]*)\"?>.*?<option value=\"?[^>\"]*\"?[^>]*selected", re.DOTALL)
        SELECTRE = re.compile("<select[^>]*name=[\"']?([^>\"']*)[^>]*>(.*?)</select>", re.DOTALL | re.IGNORECASE)
        OPTIONRE = re.compile(".*?<option[^>]*value=[\"']?([^>\"']*)[^>]*selected", re.DOTALL | re.IGNORECASE)
        for select in SELECTRE.findall(data):
                name = select[0]
                for value in OPTIONRE.findall(select[1]):
                        params[name] = value
        TEXTAREARE = re.compile("<textarea.*name *= *(?:\"([^\"]*)\"|(\\S+))[^>]*>(.*?)</textarea>", re.DOTALL)
        for textarea in TEXTAREARE.findall(data):
                params[textarea[0]] = textarea[1]
        return params
