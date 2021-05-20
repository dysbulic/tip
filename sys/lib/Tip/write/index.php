<?xml version="1.0" standalone="yes"?>
<?php
/**
 * Main Tip collector and rerouter.
 */

/**
 * Example Input:
 *  <form method="post" action="http://will.tip.dhappy.org/projects/Tip/">
 *    <div id="#q:g&amp;c">
 *      <hidden name="author" value="mailto:will@dhappy.org" />
 *      <hidden name="source" value="http://hoenir.himinbi.org/2009/11/10/declaring-wwiii/#q:g&amp;c" />
 *      <hidden name="destination" value="http://hoenir.himinbi.org/2009/11/10/declaring-wwiii/#g&amp;c"/>
 *      <hidden name="key[]" value="-50 years/warfare/asymmetric/strategist#1/" />
 *      <input type="text" style="width: 48%; float: right;" name="value[]" />
 *      <input type="submit" style="width: 40%; display: block; margin: auto;" name="text" value="Guess" />
 *    </div>
 *  </form>
 */
require('.../auth.php');
require('./auth.php');

$host = 
$database = 'tip';
mysql_connect(
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:lxslt="http://xml.apache.org/xslt" xmlns:doc="http://docbook.org/ns/docbook">
  <head>
    <title>Tip</title>
    <style type="text/css">
      html, body { height: 100%; padding: 0; margin: 0; }
    </style>
    <link rel="stylesheet" href="tip.css" type="text/css"/>
  </head>
  <body>

    <script type="text/javascript">
      var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
      document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
      try {
        var pageTracker = _gat._getTracker("UA-2592249-7");
        pageTracker._trackPageview();
      } catch(err) {}
    </script>
  </body>
</html>
