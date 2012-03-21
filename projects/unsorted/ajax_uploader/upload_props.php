<?php
  session_save_path(dirname($_SERVER['SCRIPT_FILENAME']) . "/sessions/");
  session_start();
  print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Upload Properties</title>
    <link rel="stylesheet" href="../styles/main.css" type="text/css"/>
    <style type="text/css">
      html, body { height: 100%; }
      h1, h2, h3 { text-align: left; margin-bottom: 0; }
      table { border-collapse: collapse; }
      th, td { border: 1px solid; text-align: left; padding: 0 .5em 0 .5em; }
      #formtarget, #props { border: inset; height: 10em; overflow: auto; margin-left: 1em; width: 100%; }
      #props:hover { height: 100%; }
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <h1>File Upload Properties</h1>
    <?php if(!isset($_FILES)) { ?>
    <p><code>$_FILES</code> is not set.</p>
    <?php } else { ?>
    <p>Elements in the <code>$_FILES</code> array:</p>
    <?php
      function list_array($prefix, $var) {
        print "<li>$prefix";
        if(is_array($var)) {
          print ":<ul>";
          while(list($key, $value) = each($var)) {
            list_array("${prefix}[$key]", $value);
          }
          print "</ul>\n";
        } else {
          print " = $var";
          if(is_uploaded_file($value)) {
            print "<ul>\n";
            print "<li>Creation Time: " . date("F d Y H:i:s.", filectime($value));
            print "<li>Last Modification Time: " . date("F d Y H:i:s.", filemtime($value));
            print "<li>Last Access Time: " . date("F d Y H:i:s.", fileatime($value));
            print "<li>Size: " . filesize($value);
            print "</ul>";
          }
        }
        print "</li>\n";
      }
    ?>
    <ul>
      <?php list_array("\$_FILES", $_FILES) ?>
      <?php list_array("\$_POST", $_POST) ?>
      <?php list_array("\$_SESSION", $_SESSION) ?>
      <li>Session Save Path: <?php print session_save_path() ?></li>
    </ul>
    <?php } ?>
    </body>
</html>
