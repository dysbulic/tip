<?php
header('HTTP/1.1 400 Bad Request');
print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>400 - Bad Request</title>
    <link rel="stylesheet" type="text/css" href="../styles/main.css" />
    <style type="text/css">
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <h1>Missing Parameter</h1>
    <p>This service requires the <code>type</code> parameter be set where <code>type</type> is one of:</p>
    <?php
    print "<ul>\n";
    foreach($acceptedTypes as $type) {
      print "<li><code>$type</code></li>\n";
    }
    print "</ul>\n";
    ?>
    <p><a href="composite_artist.phps">Program source.</a></p>
  </body>
</html>
