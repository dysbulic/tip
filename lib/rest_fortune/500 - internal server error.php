<?php
header('HTTP/1.1 500 Internal Server Error');
print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>500 - Internal Server Error</title>
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
    <h1>Fortune REST Service</h1>
    <p>The <code>fortune</code> program is not available on this computer.</p>
    <p>Searched:</p>
    <?php
      print "<ul>\n";
      /* defined in the calling program */
      for($programs as $program) {
        print "  <li>$program</li>\n";
      }
      print "</ul>\n";
    ?>
    <p><a href="rest_fortune.phps">Program source.</a></p>
  </body>
</html>
