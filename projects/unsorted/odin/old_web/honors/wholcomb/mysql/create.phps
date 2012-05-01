<html>
<?php if("$name" == ""): ?>
<head>
<title>Create a new unit</title>
</head>
<body>
<table>
<form method="post" action="<?php echo getenv("SCRIPT_NAME"); ?>">
<tr>
  <td>Unit name:</td>
  <td><input type="text" size="20" name="name"></td>
</tr>
<tr>
  <td>Description:</td>
  <td><textarea name="description" cols="50" rows="10" wrap="soft"></textarea></td>
</tr>
<tr>
  <td>Number required for completion:</td>
  <td><input type="text" size="5" name="number" value="1"></td>
</tr>
<tr>
  <td><input type="checkbox" name="specify" value="1"></td>
  <td>Additional specification needed</td>
</tr>
<?php
  $db = mysql_connect("localhost", "root", "honors7u") or die("Database connect failed");
  mysql_select_db("honors") or die("Table select failed");

  $sql = "select id, name from units order by id";

  $result = mysql_query($sql, $db) or die("Querry \"$sql\" failed");

  echo "<tr><th colspan=\"2\">Units to include</th></tr>\n";
  while($row = mysql_fetch_array($result))
  {
    echo "<tr>\n";
    echo "  <td><input type=\"checkbox\" name=\"option[]\" value=\"" . $row["id"] . "\"></td>\n";
    echo "  <td>" . $row["name"] . "</td>\n";
    echo "</tr>\n";
  }
?>
<tr>
  <td colspan="2"><input type="submit" value="Enter"><input type="reset" value="Reset"></td>
</tr>
</form>
</table>
</body>
<?php else: ?>
<?php
  function print_form($id, $db = 0, $count = 1)
  {
    if($db = 0)
    {
      $db = mysql_connect("localhost", "root", "honors7u") or die("Database connect failed");
      mysql_select_db("honors") or die("Table select failed");
    }

    echo "<ol>\n";

    $sql = "select name, description, number, select from units where id = \"$id\"";
    $result = mysql_query($sql, $db) or die("Querry \"$sql\" failed");

    while($row = mysql_fetch_row($info))
      echo "<li>" . $row["name"] . "; " . $row["description"] . "</li>\n";

    $sql = "select child_id as id from unit_relationships where parent_id = \"$id\"";
    $result = mysql_query($sql, $db) or die("Querry \"$sql\" failed");

    while($row = mysql_fetch_row($info))
      print_form($row["id"], $db);

    echo "</ol>\n";

/*    if(mysql_affected_rows() == 0)
    {
      $sql = "select name from options where id = \"$id\"";
      $result = mysql_query($sql, $db) or die("Querry \"$sql\" failed");
      while($row = mysql_fetch_array($result))
        echo "<tr><td>" . $row["name"] . "</td></tr>\n";
    }
    else
    {
      echo "<table>\n";
      while($row = mysql_fetch_array($result))
        print_form($row["id"], $db);
      echo "</table>\n";
    }
*/?>
<?php
  if(isset($option) && isset($number))
    if(sizeof($option) < $number)
      die("<p>You attempted to require $number of " . sizeof($option) . " options, this isn't possible.</p>");
    elseif(isset($option) && !isset($number))
      $number = sizeof($option);

  $db = mysql_connect("localhost", "root", "honors7u") or die("Database connect failed");
  mysql_select_db("honors") or die("Table select failed");

  $sql = "insert into units (name, description, specify, number) values (\"$name\", \"$description\", \"$specify\", \"$number\")";

  $result = mysql_query($sql, $db) or die("Querry \"$sql\" failed");

  $id = mysql_insert_id();

  if(isset($option))
    while(list($key, $val) = each($option))
    {
      $sql = "insert into unit_relationships (parent_id, child_id) values (\"$id\", \"$val\")";
      $result = mysql_query($sql, $db) or die("Querry \"$sql\" failed");
    }
  print_form($id, $db);
?>
<?php endif; ?>
</html>






