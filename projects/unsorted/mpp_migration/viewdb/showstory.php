<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\" " . chr(63) . ">\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Site Builder Database Story</title>
    <link rel="stylesheet" href="odin/menu/dynamic_menu.css" type="text/css" />
    <link rel="stylesheet" href="links.css" type="text/css" />
    <style type="text/css">
      body { padding: 2em 1.5em 0 1.5em; }
      h2 { margin: 0; }
      .author { font-style: italic; }
    </style>

    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>

    <script type="text/javascript" src="odin/javascript_compatability/compatability.js"></script>
    <script type="text/javascript" src="odin/menu/menu.js"></script>
  </head>
  <body onload="makemenu('dynamicmenu')">
    <?php
      $db = dbx_connect(DBX_MYSQL, "localhost", "kintera_prep", "root", "");
      $sql = "select title, subtitle, author.name, source.name, text" .
             " from article left join author on (article.author_id = author.id)" .
             " left join source on (article.source_id = source.id)" .
             " where article.id = " . $_GET['id'] . ";";
      $result = dbx_query($db, $sql);
      foreach($result->data as $row) {
        echo "<h1>$row[0]</h1>\n";
        if($row[1] != null) { echo "<h2 class='subtitle'>$row[1]</h1>\n"; }
        echo "<h2 class='author'>$row[2]</h1>\n";
        echo "<h2 class='source'><cite>$row[3]</cite></h1>\n";
        echo $row[4];
      }
      dbx_close($db);
    ?>
  </body>
</html>
