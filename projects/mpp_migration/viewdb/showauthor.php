<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\" " . chr(63) . ">\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Site Builder Database Authors</title>
    <link rel="stylesheet" href="odin/menu/dynamic_menu.css" type="text/css" />
    <link rel="stylesheet" href="links.css" type="text/css" />
    <style type="text/css">
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
      if($_GET['id'] == "") {
    ?>
    <h1>Site Builder Database News Authors</h1>
    <table>
      <tr><th>Author</th><th>Number of Stories</th><th>Sources</th></tr>
    <?php
      $sql = "select author.id, author.name, count(*) from author" .
             " inner join article on article.author_id = author.id" .
             " group by author.id order by author.name;";
      $result = dbx_query($db, $sql);
      foreach($result->data as $row) {
        echo "<tr><td><a href='?id=$row[0]'>$row[1]</a></td><td><a href='?id=$row[0]'>$row[2]</a></td>\n";
        echo "<td><table>\n";
        $sql = "select source.id, name, count(*) from article" .
               " left join source on (source.id = article.source_id)" .
               " where article.author_id = $row[0]" .
               " group by source.id order by source.name;";
        $sources = dbx_query($db, $sql);
        foreach($sources->data as $sourceRow) {
          echo "<tr><td><a href='showsource.php?id=$sourceRow[0]'>$sourceRow[1]</a></td><td>$sourceRow[2]</td></tr>\n";
        }
        echo "</table></td>\n";
        echo "</tr>\n";
      }
    ?>
    </table>
    <?php } else { // id specified ?>
    <?php
      $sql = "select name from author where id = " . $_GET['id'] . ";";
      $result = dbx_query($db, $sql);
      foreach($result->data as $row) {
        echo "<h1>Site Builder Stories by $row[0]</h1>\n";
      }
    ?>
    <h2><?php echo "<a href='" . $_SERVER['PHP_SELF'] . "'>All Authors</a>" ?></h2>
    <table>
      <tr><th>Title</th><th>Source</th><th>Publication Date</th></tr>
    <?php
      $sql = "select article.id, title, source.id, source.name, publication_date" .
             " from article left join source on (source.id = article.source_id)" .
             " where article.author_id = " . $_GET['id'] . " order by publication_date;";
      $result = dbx_query($db, $sql);
      foreach($result->data as $row) {
        echo "<tr><td><a href='showstory.php?id=$row[0]'>$row[1]</td>\n";
        echo "    <td><a href='showsource.php?id=$row[2]'>$row[3]</a></td>\n";
        echo "    <td>$row[6]</td></tr>\n";
      }
    ?>
    </table>
    <?php
      }
      dbx_close($db);
    ?>
  </body>
</html>
