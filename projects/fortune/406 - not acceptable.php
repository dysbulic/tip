<?php print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>406 - Not Acceptable</title>
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
    <p>The <code>Accept</code> header for your request did not include:</p>
    <?php
    print "$mimeType <ul>\n";
    foreach($acceptedTypes as $type) {
      print "<li><code>$type</code></li>\n";
    }
    print "</ul>\n";
    ?>
    <p>It was: <code><?php print $_SERVER['HTTP_ACCEPT'] ?></code></p>
<?php
  if(function_exists("getallheaders")) {
    print "<p>The headers in the request for this page were:</p>\n";
    print "<ul>\n";
    foreach(getallheaders() as $header => $value) {
      if($header == "Cookie") {
        print "  <li><strong>$header:</strong>\n";
        print "     <ul>\n";
        foreach(split(';', $value) as $pair) {
          print "       <li>$pair</li>\n";
        }
        print "     </ul>\n";
        print "  </li>\n";
      } elseif($header != "Accept") {
        print "  <li><strong>$header:</strong> $value</li>\n";
      }
    }
    print "</ul>\n";
  }
?>
    <p><a href="rest_fortune.phps">Program source.</a></p>
  </body>
</html>
