<?php
$uri = ( $_GET['uri']
	 ? $_GET['uri']
	 : ( $_POST['uri']
	     ? $_POST['uri']
	     : undefined ) );
$filename = preg_replace( '|.*/|', '', $uri );
?>
<?php print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Generator: <?php print $filename?></title>
    <link rel="stylesheet" type="text/css" href=".../style/main.css" />
    <style type="text/css">
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <h1>Generating: <?php print $filename?></h1>
<?php
// No apparent effect
flush();
ob_flush();
?>
<pre><![CDATA[
<?php
echo shell_exec( "make '$filename'" );
?>
]]></pre>
    <script type="application/javascript">
      window.location = '<?php print $filename?>'
    </script>
  </body>
</html>
