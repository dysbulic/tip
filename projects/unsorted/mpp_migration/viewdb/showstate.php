<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\" " . chr(63) . ">\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Site Builder Database Location Stories</title>
    <link rel="stylesheet" href="odin/menu/dynamic_menu.css" type="text/css" />
    <link rel="stylesheet" href="links.css" type="text/css" />
    <style type="text/css">
      <?php if($_GET['id'] != "") { ?>
      html { margin-right: 2em; margin-left: 2em; }
      <?php } ?>
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
    <h1>Site Builder Database News Categories</h1>
    <table>
      <tr><th>Location</th><th>Number of Stories</th></tr>
    <?php
      $sql = "select location.id, name, count(*) from location" .
             " inner join itemlocation on (location.id = itemlocation.location_id)" .
             " inner join article on (article.id = itemlocation.item_id)" .
             " group by location.id order by name;";
      $result = dbx_query($db, $sql);
      foreach($result->data as $row) {
        echo "<tr><td><a href='?id=$row[0]'>$row[1]</a></td><td><a href='showstate.php?id=$row[0]'>$row[2]</a></td></tr>\n";
      }
    ?>
    </table>
    <?php } else { // id specified ?>
    <?php
      $db = dbx_connect(DBX_MYSQL, "localhost", "kintera_prep", "root", "");
      $sql = "select name, abbreviation from location where id = " . $_GET['id'] . ";";
      $result = dbx_query($db, $sql);
      foreach($result->data as $row) {
        echo "<h1>Site Builder Stories for $row[0] ($row[1])</h1>\n";
      }
    ?>
    <h2><a href="<?php echo $_SERVER['PHP_SELF'] ?>">All States</a></h2>
    <table>
      <tr><th>Title</th><th>Author</th><th>Source</th><th>Publication Date</th></tr>
    <?php
      $sql = "select article.id, title, author.id, author.name, source.id, source.name, publication_date" .
             " from article inner join itemlocation on (article.id = itemlocation.item_id)" .
             " left join author on (author.id = article.author_id)" .
             " left join source on (source.id = article.source_id) " .
             " where itemlocation.location_id = " . $_GET['id'] .
             " order by publication_date;";
      $result = dbx_query($db, $sql);
      foreach($result->data as $row) {
        echo "<tr><td><a href='showstory.php?id=$row[0]'>$row[1]</td>\n";
        echo "    <td><a href='showauthor.php?id=$row[2]'>$row[3]</a></td>\n";
        echo "    <td><a href='showsource.php?id=$row[4]'>$row[5]</td>\n";
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
