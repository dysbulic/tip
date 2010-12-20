<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>PHP Array Test</title>
    <link rel="stylesheet" type="text/css" href="/styles/main.css" />
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <?php $teachers = array("english" => "Dr. Hood",
                            "writing" => "Dr. Barnes",
                            "math" => "Dr. Smith",
                            "blah", "blah", "blah"); ?>
    <ul>
    <?php foreach($teachers as $teacher) { ?>
      <li><?php echo($teacher) ?></li>
    <?php } ?>
    <?php foreach($teachers as $subject => $teacher) { ?>
      <li><?php echo($teacher . " => " . $subject) ?></li>
    <?php } ?>
    <?php foreach($_SESSION['vars'] as $var) { ?>
      <li><?php echo($var) ?></li>
    <?php } ?>
      <li><?php echo($teachers['english']) ?></li>
    </ul>
  </body>
</html>
