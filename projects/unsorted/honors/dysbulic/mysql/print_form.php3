<html>
<?php
  function print_form($id, $db = 0, $count = 1)
  {
    if($db == 0)
    {
      $db = mysql_connect("localhost", "root", "honors7u") or die("Database connect failed");
      mysql_select_db("honors") or die("Table select failed");
    }

    echo "<ol>\n";

    $sql = "select name, description, number, specify from units where id = \"$id\"";
    $result = mysql_query($sql, $db) or die("Querry \"$sql\" failed");

    while($row = mysql_fetch_array($result))
      echo "<li>" . $row["name"] . "; " . $row["description"] . "</li>\n";

    $sql = "select child_id as id from unit_relationships where parent_id = \"$id\"";
    $result = mysql_query($sql, $db) or die("Querry \"$sql\" failed");

    while($row = mysql_fetch_array($result))
      print_form($row["id"], $db);

    echo "</ol>\n";
  }
?>

<?php
  function print_form_2($id, $db = 0)
  {
    if($db == 0)
    {
      $db = mysql_connect("localhost", "root", "honors7u") or die("Database connect failed");
      mysql_select_db("honors") or die("Table select failed");
    }

    $sql = "select id, name, description, number, specify from units where id = \"$id\"";
    $stack[] = mysql_query($sql, $db) or die("Querry \"$sql\" failed");
    $depth = 1;

    while($depth > 0)
    {
      if($row = mysql_fetch_array($result))
      {
        $sql = "select id, name, description, number, specify from units where id = \"" . $row["id"] . "\"";
	$temp = mysql_query($sql, $db) or die("Querry \"$sql\" failed");

        echo "<li>" . $row["name"] . ($row["description"] != "" ? "; " . $row["description"] :"") . "</li>\n";

	if(mysql_affected_rows($temp) > 0)
	{
	  $depth++;
	  $stack[$depth] = $result;
	  $result = $temp;
	  echo "<ol>\n";
	}
      }
      else
      {
        $result = $stack[$depth];
	$depth--;
	echo "</ol>\n";
      }
    }
  }
?>
<?php print_form_2(57); ?>
</html>
