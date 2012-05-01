<?php echo '<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\" ?' . ">\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xml:lang="en">
  <head>
    <title>Styles</title>
<?php
$dirName = "../styles/";
$dir = opendir($dirName);
$files = array();
while($filename = readdir($dir)) {
  if(ereg("\\.css$", $filename, $match)) {
    array_push($files, $filename);
  }
}
sort($files);
foreach($files as $filename) {
  print('  <link rel="alternate stylesheet" type="text/css" href="' . $dirName . $filename . '" title="' . $filename . "\" />\n");
}
closedir($dir);
?>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>

    <script type="text/javascript" src="../javascript_compatability/compatability.js"></script>
    <script type="text/javascript" src="randomize_stylesheet.js"></script>
    <script type="text/javascript">//<![CDATA[
    //]]></script>
  </head>
  <body onload="setActiveStylesheet()">
    <h1>Rotating Stylesheets</h1>
    <p><a href="http://mpp.org">MPP</a> wants to have the option of the appearance of the front page changing slightly when people visit. Rotating title banners and whatnot. The subject has been covered pretty thouroughly on <a href="http://www.alistapart.com/stories/alternate/">A List Apart</a>. All I'm doing is making <a href="randomize_stylesheet.js">the script</a> run at load time.</p>
    <p>The default duration for the cookie to keep the style looking the same is one day.</p>
    <p>The nice thing about using an <code>onunload</code> listener is it will also notice if they change to an alternate stylesheet using the browser. (<code>View</code> &rarr; <code>Page Style</code>)</p>
    <hr />
    <ul>
    <?php
$dir = opendir($dirName);
$files = array();
while($filename = readdir($dir)) {
  if(ereg("\\.css$", $filename, $match)) {
    array_push($files, $filename);
  }
}
sort($files);
foreach($files as $filename) {
  print("  <li><a href=\"javascript:setActiveStylesheet('$filename')\">$filename</a></li>\n");
}
closedir($dir);
?>
    </ul>
    <script type="text/javascript">
      function clearCookie() {
        setCookie({styleTitle:''}, -1);
        removeListener(this, "unload", stylesheetSaver);
        disableAlternateStylesheets();
      }
    </script>
    <form>
      <div><input type="button" onclick="javascript:clearCookie()" value="Clear Cookie"></input></div>
    </form>
  </body>
</html>
