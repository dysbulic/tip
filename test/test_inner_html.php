<?php
$isxml = isset($_GET["type"]) && $_GET["type"] == "xml";
if($isxml) {
    header("Content-type: application/xhtml+xml");
  }
  print '<?xml version="1.0" encoding="UTF-8" ?' . ">\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Test InnerHTML Access from XHTML (<?php if($isxml) { print "xml"; } else { print "html"; } ?>)</title>
    <link type="text/css" rel="stylesheet" href="javascript_test.css" />
    <style type="text/css">
      a.type {
        padding: 1em;
        font-weight: bold;
        text-decoration: none;
        border: 2px solid;
        margin: 2em;
        background-color: Highlight;
        color: HighlightText;
      }
      a.type:hover {
        border-color: Highlight;
        background-color: Menu;
        color: MenuText;
      }
    </style>
    <script type="text/javascript">//<![CDATA[
      function adddiv() {
      }
    //]]></script>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body onload="adddiv()">
    <p>I have a program that is accessing the <code>innerHTML</code>
    member of a dynamically created <code>div</code>. It is working
    when the page is sent as text/html, but there is an exception
    thrown when it is application/xhtml+xml. <i>(This is a <a
    href="https://bugzilla.mozilla.org/show_bug.cgi?id=155723">known
    bug</a>.)</i> This page can be loaded using either method:</p>
    <div style="text-align: center">
      <a class="type" href="<?php print $_SERVER['SCRIPT_NAME'] ?>"><acromyn title="HyperText Markup Language">HTML</acromyn></a>
      <a class="type" href="<?php print $_SERVER['SCRIPT_NAME'] ?>?type=xml"><acromyn title="eXtensible Markup Language">XML</acromyn></a>
    </div>
    <form action="" onsubmit="eval(this['code'].value); return false">
      <div style="height: 90%">
        <textarea name="code" cols="" rows=""
>var body = document.getElementsByTagName("body").item(0);
body.appendChild(document.createElement("hr"));
body.appendChild(document.createElement("div"));
try {
  body.lastChild.innerHTML = "&lt;b&gt;This is a test&amp;hellip;&lt;/b&gt;";
} catch(e) {
  body.appendChild(document.createTextNode(e.toString()));
}</textarea>
      </div>
      <div><input type="submit" value="Go"></input></div>
    </form>

  </body>
</html>
