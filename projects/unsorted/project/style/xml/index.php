<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\" ?>" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:xul="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul"
      xml:lang="en">
  <head>
    <title>Stylin&apos;</title>
    <link rel="stylesheet" href="style_browser.css" type="text/css" />
    <style type="text/css">
      .checkholder {
        width: 1em;
        height: 1em;
        float: left;
        text-align: center;
      }
    </style>

    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>

    <script type="text/javascript" src="../javascript_compatability/compatability.js"></script>
    <script type="text/javascript">//<![CDATA[
      addListener(document, "load", function() { alert("Loaded: doc"); });
      addListener(this, "load", init);
      var stylesheets = new Array();
      var frame, urlstyles;
      function init() {
        var menu = document.getElementById("serverstyles");
        for(var i = 0; i < menu.childNodes.length; i++) {
          if(menu.childNodes[i].nodeType == Node.ELEMENT_NODE &&
             menu.childNodes[i].nodeName.toUpperCase() == "A") {
            add_sheet(menu.childNodes[i].childNodes[0].data,
                      menu.childNodes[i]);
          }
        }
        urlstyles = document.getElementById("urlstyles");
        frame = document.getElementById("frame");
        frame_loaded();
        addListener(frame, "load", frame_loaded);
      }
      function add_sheet(title, link, sheet) {
        if(stylesheets[title]) {
          var count = 1;
          while(stylesheets[title + " (" + count + ")"]) count++;
          title += " (" + count + ")";
        }

        stylesheets[title] = new Object();
        stylesheets[title].title = title;
        var checkholder = document.createElement("div");
        checkholder.className = "checkholder";
        stylesheets[title].check = document.createTextNode("");
        checkholder.appendChild(stylesheets[title].check);
        if(link) {
          stylesheets[title].link = link;
          stylesheets[title].link.insertBefore
            (checkholder, stylesheets[title].link.childNodes[0]);
        } else {
          stylesheets[title].link = document.createElement("a");
          stylesheets[title].link.setAttribute
            ("href", "javascript:set_style_enabled('" + title + "')");
          stylesheets[title].link.appendChild(checkholder);
          stylesheets[title].link.appendChild(document.createTextNode(title));
        }
        if(sheet) {
          stylesheets[title].sheet = sheet;
        } else {
          stylesheets[title].sheet = document.createElement("link");
          stylesheets[title].sheet.setAttribute("rel", "stylesheet");
          stylesheets[title].sheet.setAttribute("type", "text/css");
          stylesheets[title].sheet.setAttribute
            ("href", document.location.href.replace(/\/[^\/]*$/, "/") + title);
          stylesheets[title].serverstyle = true;
        }
        set_style_enabled(title, (!stylesheets[title].sheet.disabled &&
                                  !stylesheets[title].serverstyle));
        return stylesheets[title];
      }

      function set_style_enabled(filename, enabled) {
        if(!stylesheets[filename]) {
          alert("No stylesheet named: " + filename);
        }
        if(typeof(enabled) == "undefined") {
          var currentlyDisabled = (stylesheets[filename].check.data == "");
          enabled = currentlyDisabled;
        }
        // The disabled flag is reset on page load, so this must be stored externally
        stylesheets[filename].enabled = enabled;
        stylesheets[filename].sheet.disabled = !enabled;
        if(stylesheets[filename].enabled) {
          stylesheets[filename].link.className = "stylesheet checked";
          stylesheets[filename].check.data = "✓";
        } else {
          stylesheets[filename].link.className = "stylesheet";
          stylesheets[filename].check.data = "";
        }
      }
      function change_site(url) {
        frame.setAttribute("src", url);
      }
      function frame_loaded() {
        var head;
        try {
          var heads = frame.contentDocument.getElementsByTagName("head");
          if(heads.length > 0) head = heads[0];
        } catch(e) {
        }
        if(urlstyles.addedSheets) {
          while(urlstyles.addedSheets.length > 0) {
            var sheet = urlstyles.addedSheets.pop();
            sheet.link.parentNode.removeChild(sheet.link);
            stylesheets[sheet.title] = undefined;
          }
        }
        if(head) {
          urlstyles.addedSheets = new Array();
          var inlineCount = 0;
          for(var i = 0; i < head.childNodes.length; i++) {
            var element = head.childNodes[i];
            if(element.nodeType == Node.ELEMENT_NODE) {
              if((element.nodeName.toUpperCase() == "LINK" &&
                  element.getAttribute("rel").match(/stylesheet/)) ||
                 element.nodeName.toUpperCase() == "STYLE") {
                var title = head.childNodes[i].getAttribute("title");
                if(!title || title == "") {
                  if(element.nodeName.toUpperCase() == "LINK") {
                    title = element.getAttribute("href").replace(/.*\//, '');
                  } else {
                    title = "Inline #" + ++inlineCount;
                  }
                }
                var sheet = add_sheet(title, undefined, head.childNodes[i]);
                urlstyles.appendChild(sheet.link);
                urlstyles.addedSheets.push(sheet);
              }
            }
          }
          for(filename in stylesheets) {
            if(stylesheets[filename] && stylesheets[filename].serverstyle) {
              head.appendChild(stylesheets[filename].sheet);
              stylesheets[filename].sheet.disabled = !stylesheets[filename].enabled;
            }
          }
        }
      }
    //]]></script>
  </head>
  <body>
    <div id="menu">
      <div id="serverstyles">
<?php
$dir = opendir(".");
$files = array();
while($filename = readdir($dir)) {
  if(ereg("\\.css$", $filename, $match)) {
    array_push($files, $filename);
  }
}
sort($files);
foreach($files as $filename) {
  print('<a class="stylesheet" ' .
        'href="javascript:set_style_enabled(\'' . $filename . '\')">' .
        $filename . '</a>' . "\n");
}
closedir($dir);
?>
      </div>
      <div id="urlstyles"></div>
      <div id="location">
        <form onsubmit="change_site(document.forms[0].source.value); return false" action="">
          <input type="text" id="source" value="http://www.himinbi.org"></input>
          <input type="submit" value="Change Site"></input>
        </form>
      </div>
    </div>
    <iframe id="frame" src=".."></iframe>
  </body>
</html>
