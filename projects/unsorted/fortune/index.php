<?php print('<?xml version="1.0" encoding="UTF-8"?' . ">\n") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Fortune REST Service</title>
    <link rel="stylesheet" type="text/css" href="../styles/main.css" />

    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>

    <script type="text/javascript" src="../javascript_compatability/compatability.js"></script>
    <script type="text/javascript">//<![CDATA[
      function get_fortune(event) {
        var callback = function(request) {
          if(request.readyState == 4 && request.status == 200) {
            fortuneElement = document.getElementById("fortune");
            switch(this.type) {
              case 'text/xml':
                fortuneElement.innerHTML = request.responseXML.childNodes[0].childNodes[0].data;
                break;
              case 'application/rss+xml':
                var item = request.responseXML.getElementsByTagName("description")[1];
                if(item != null) {
                  fortuneElement.innerHTML = item.childNodes[0].data;
                } else {
                  var text = request.responseText.replace(/</g, "&lt;");
                  fortuneElement.innerHTML = "<p><strong>Error:</strong> No fortune found in:</p><pre>" + text + "</pre>";
                }
                break;
              default:
                if(fortuneElement.childNodes.length > 1) {
                  while(fortuneElement.childNodes.length > 0) {
                    fortuneElement.removeChild(fortuneElement.lastChild);
                  }
                }
                if(fortuneElement.childNodes.length == 0) {
                  fortuneElement.appendChild(document.createTextNode(" ")); // if this is "" it doesn't work in Safari
                }
                fortuneElement.childNodes[0].data = request.responseText;
            }  
          }
        }
        callback.type = getSource(event).value;
        var request = getXMLHttpRequest(callback);
        request.open("GET", "rest_fortune.php", true);
        request.setRequestHeader("Accept", callback.type);
        request.send(null);
      }
     //]]></script>
  </head>
  <body>
    <h1>Fortune <a href="http://www.ics.uci.edu/~fielding/pubs/dissertation/top.htm"><acronym title="Representational State Transfer">REST</acronym></a> Service</h1>
    <p>This is an <a href="rest_fortune.phps"><acronym title="Asynchronous JavaScript + XML">AJAX</acronym> service</a> which returns the text from the <a href="http://en.wikipedia.org/wiki/Fortune_(program)">fortune program</a>. There are three return types:</p>

    <pre id="fortune"></pre>
    <form action="">
      <ul>
        <li><input type="button" onclick="get_fortune(event)" value="text/plain"></input></li>
        <li><input type="button" onclick="get_fortune(event)" value="text/xml"></input></li>
        <li><input type="button" onclick="get_fortune(event)" value="application/rss+xml"></input></li>
      </ul>
    </form>

    <hr />

    <p>The <code>XMLHTTPRequest</code> can return either a <code>responseText</code> or a <code>responseXML</code>. If the service is accessed with a higher <code>Accept</code> priority for <code>text/plain</code> then text is returned. If <code>text/xml</code> has a higher priority or <code>*/*</code> is the only match then <acronym title="Extensible Markup Language">XML</acronym> is returned, with the fortune is wrapped in &lt;fortune&gt; tags.</p>
    <p><a href="http://www.xml.com/pub/a/2004/08/11/rest.html">XML.com</a> recommends also supporting a <code>mimeType</code> parameter and that makes sense to me. There could be times you are only able to specify a URI, like maybe with an <acronym title="Really Simple Syndication">RSS</acronym> feed. It is also handy for user agents like <a href="http://www.konqueror.org">Konqueror</a> whose version of <code>setRequestHeader</code> on appends to the accepted list.</p>
    <p>I also added support for a <code>X-Accept-Namespace</code> HTTP request header to be able to handle different types of XML. It is currently simply an alternate method for specifying <code>application/rss+xml</code>.</p>
    <p>There is a sample AJAX script to access this service in this page. Either the <a href="rest_fortune.php?mimeType=text/plain">text/plain</a>, <a href="rest_fortune.php?mimeType=text/xml">text/xml</a> or <a href="rest_fortune.php?mimeType=application/rss%2Bxml">application/rss+xml</a>.</p>
    <p>Known bugs:</p>
    <ul>
      <li>In IE6, the rss feed doesn't parse</li>
      <li>In IE6, the unix linefeeds are ignored and the whole fortune is on a single line</li>
      <li>In Konquerer, setting the Accept header only appends, so <code>text/xml</code> is always returned</li>
    </ul>
  </body>
</html>
