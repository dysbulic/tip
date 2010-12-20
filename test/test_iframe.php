<?php
$inframe = (isset($_SERVER["HTTP_REFERER"]) &&
            preg_match("'" . $_SERVER["SCRIPT_NAME"] . "'", $_SERVER["HTTP_REFERER"]));
?>
<?php print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Internal Frame Test<?php if($inframe) print " (inframe)" ?></title>
    <style type="text/css">
      body {
        background-color: white;
      }
      iframe {
        width: 100%;
        height: 20em;
      }
    </style>
    <style type="text/css">
      body {
        <?php if($inframe) { ?>
        background-color: green;
        <?php } else { ?>
        background-color: orange;
        <?php } ?>
      }
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
    <script type="text/javascript" src="../javascript_compatability/compatability.js"></script>
    <?php if(!$inframe) { ?>
    <script type="text/javascript">
      function getContent() {
        var iframe = document.getElementsByTagName("iframe")[0];
        var body = document.getElementsByTagName("body")[0];
        var msg = document.createElement("div");
        msg.appendChild(document.createTextNode("Content Document: " + iframe.contentDocument));
        body.appendChild(msg);
        msg = document.createElement("div");
        msg.appendChild(document.createTextNode
          ("Stylesheets: " + iframe.contentDocument.styleSheets));
        body.appendChild(msg);
        msg = document.createElement("div");
        msg.appendChild(document.createTextNode
          ("Rule #1: " + iframe.contentDocument.styleSheets[iframe.contentDocument.styleSheets.length - 1].cssRules[0].cssText + " / " + document.styleSheets[document.styleSheets.length - 1].cssRules[0].cssText));
        body.appendChild(msg);
        iframe.contentDocument.styleSheets[iframe.contentDocument.styleSheets.length] =
          document.styleSheets[document.styleSheets.length - 1];
        msg = document.createElement("div");
        msg.appendChild(document.createTextNode
          ("Rule #1: " + iframe.contentDocument.styleSheets[iframe.contentDocument.styleSheets.length - 1].cssRules[0].cssText + " / " + document.styleSheets[document.styleSheets.length - 1].cssRules[0].cssText));
        body.appendChild(msg);
        
        var link = document.createElement("link");
        link.setAttribute("rel", "stylesheet");
        link.setAttribute("type", "text/css");
        link.setAttribute("href", "javascript_test.css");
        iframe.contentDocument.getElementsByTagName("head")[0].appendChild(link);
      }
    </script>
    <?php } ?>
  </head>
  <body onload="if(typeof(getContent) != 'undefined') getContent()">
    <?php if(!$inframe) { ?>
      <iframe id="frame" src="test_iframe.php"></iframe>
      <a href="<?php print $_SERVER["SCRIPT_NAME"] . "s" ?>">source</a>
      <form action="" onsubmit="try { document.getElementById('frame').src = document.forms[0].url.value; } finally { return false; }">
        <input type="text" name="url" value="." />
        <input type="submit" value="Go" />
      </form>
    <?php } else { ?>
      <div>Testing</div>
    <?php } ?>
  </body>
</html>
