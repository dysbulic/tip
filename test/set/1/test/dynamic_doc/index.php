<?php print('<?xml version="1.0" encoding="UTF-8" standalone="no" ?>' . "\n") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <?php
      $filename = !isset($_SERVER["REQUEST_URI"])
                   ? "" : preg_replace("/.*\//", "", $_SERVER["REQUEST_URI"]);
      if($filename != "") {
        print('<meta name="robots" content="noindex,nofollow" />' . "\n");
      }
    ?>
    <title>PHP URL Test: <?php print($filename) ?></title>
    <link type="text/css" rel="stylesheet" href="../javascript_test.css" />
    <style type="text/css">
      h1, h2 { text-align: center; }
      a { text-decoration: none; padding: .25em; }
      a:hover { border: solid; }
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
    <h1>PHP URL Test</h1>
    <h2>Any random filename should resolve to this file&hellip;</h1>
    <p>This page was accessed as:</p>
    <ul>
<?php foreach(array("SCRIPT_URL", "SCRIPT_URI", "SCRIPT_FILENAME",
                    "REQUEST_URI", "SCRIPT_NAME", "PHP_SELF",
                    "PATH_TRANSLATED") as $name) { ?>
      <li><code><?php print($name) ?></code> =
       <?php if(isset($_SERVER[$name])) print $_SERVER[$name]; else print "not set"; ?></h2>
<?php } ?>
    </ul>
    <h2>
      <a href="<?php print($_SERVER[SCRIPT_NAME] . 's') ?>">source</a>
      <a href=".htaccess">htaccess</a>
    </h2>
    <h2>
<?php
  for($i = 0; $i < 10; $i++) {
    $num = rand(1000, 100000);
    print("      <a href=\"$num\">$num</a>\n");
  }
?>
    </h2>
  </body>
</html>
