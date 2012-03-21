<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\" " . chr(63) . ">\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Site Builder Database Sources</title>
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
    <h1>Site Builder Database News Sources</h1>
    <table>
      <tr><th>Source</th><th>Number of Stories</th><th>Authors</th></tr>
    <?php
      $sql = "select source.id, source.name, count(*) from source" .
             " inner join article on (article.source_id = source.id)" .
             " group by source.id order by source.name;";
      $result = dbx_query($db, $sql);
      foreach($result->data as $row) {
        echo "<tr><td><a href='?id=$row[0]'>$row[1]</a></td><td><a href='?id=$row[0]'>$row[2]</a></td>\n";
        echo "<td><table>\n";
        $sql = "select author.id, name, count(*) from author" .
               " inner join article on (author.id = article.author_id)" .
               " where article.source_id = $row[0]" .
               " group by author.id order by author.name;";
        $authors = dbx_query($db, $sql);
        foreach($authors->data as $authorRow) {
          echo "<tr><td><a href='showauthor.php?id=$authorRow[0]'>$authorRow[1]</a></td><td>$authorRow[2]</td></tr>\n";
        }
        echo "</table></td>\n";
        echo "</tr>\n";
      }
    ?>
    </table>
    <?php } else { // id specified ?>
    <?php
      $sql = "select name from source where id = " . $_GET['id'] . ";";
      $result = dbx_query($db, $sql);
      foreach($result->data as $row) {
        echo "<h1>Site Builder Stories for <cite>$row[0]</cite></h1>\n";
      }
    ?>
    <h2><?php echo "<a href='" . $_SERVER['PHP_SELF'] . "'>All Sources</a>" ?></h2>
    <table>
      <tr><th>Title</th><th>Author</th><th>Publication Date</th></tr>
    <?php
      $sql = "select article.id, title, author.id, author.name, publication_date" .
             " from article left join author on (author.id = article.author_id)" .
             " where article.source_id = " . $_GET['id'] . " order by publication_date;";
      $result = dbx_query($db, $sql);
      foreach($result->data as $row) {
        echo "<tr><td><a href='showstory.php?id=$row[0]'>$row[1]</td>\n";
        echo "    <td><a href='showauthor.php?id=$row[2]'>$row[3]</a></td>\n";
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
