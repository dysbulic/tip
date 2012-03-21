<html>
<head>
<title>Form composition edit page</title>
</head>
<body>
<?php if(!isset($type) || !isset($number_sections) || intval($number_sections) == 0): ?>
<?php
  $db = mysql_connect("localhost", "root", "honors7u") or die("Database connect failed");
  mysql_select_db("honors") or die("Table select failed");

  $sql = "select id, name from form_types order by id";

  $result = mysql_query($sql, $db) or die("Querry \"$sql\" failed");
  $rows = mysql_num_rows($result);

  if($rows > 0)
  {
    echo "<form name=\"HPEO_selection\" method=\"post\" action=\"" . getenv("SCRIPT_NAME") . "\">\n";
    while($row = mysql_fetch_array($result))
      echo "<input type=\"radio\" name=\"type\" value=\"" . $row["id"] . "\">" . $row["name"] . "<br>\n";
    echo "Number of sections:&nbsp;<input type=\"text\" size=\"5\" name=\"number_sections\" value=\"4\"><br>\n";
    echo "<input type=\"submit\" value=\"Edit\">\n";
    echo "</form>\n";
  }
  else
  {
    echo "<p>There are no forms to choose from.</p>\n";
    die();
  }
?>
<?php elseif(!isset($form_completed)): ?>
<?php
  $db = mysql_connect("localhost", "root", "honors7u") or die("Database connect failed");
  mysql_select_db("honors") or die("Table select failed");

  $sql = "select name from form_types where id = \"$type\"";

  $result = mysql_query($sql, $db) or die("Querry \"$sql\" failed");
  $rows = mysql_num_rows($result);

  if($rows == 1)
  {
    echo "Composition for " . mysql_result($result, 0, 0) . "\n";
    echo "<hr>\n";
  }
  else
  {
    die("<p>Error: \"$sql\" returned $rows</p>");
  }

  $sql = "select name from units order by id";

  $result = mysql_query($sql, $db) or die("Querry \"$sql\" failed");
  $rows = mysql_num_rows($result);
  
  if($rows > 0)
  {
    echo "<form name=\"HPEO_selection\" method=\"post\" action=\"" . getenv("SCRIPT_NAME") . "\">\n";
    echo "<input type=\"hidden\" name=\"form_completed\" value=\"true\">\n";
    echo "<input type=\"hidden\" name=\"type\" value=\"$type\">\n";
    echo "<input type=\"hidden\" name=\"number_sections\" value=\"$number_sections\">\n";
    echo "<table width=\"50%\">\n";
    for($i = 0; $i < $number_sections; $i++)
    {
      echo "<tr><th colspan=\"2\" align=\"center\">Section " . ($i + 1) . "</th></tr>\n";
      mysql_data_seek($result, 0);
      while($row = mysql_fetch_array($result))
      {
	echo "<tr><td align=\"center\">\n";
	echo "<input type=\"text\" size=\"3\" value=\"0\" name=\"" . $row["name"] . "[]\">\n";
	echo "</td><td align=\"left\">";
	echo $row["name"] . " Unit\n";
	echo "</td></tr>\n";
      }
    }
    echo "<tr><td colspan=\"2\" align=\"center\">\n";
    echo "<input type=\"submit\" value=\"Enter\">\n";
    echo "<input type=\"reset\" value=\"Reset\">\n";
    echo "</td></tr>\n";
    echo "</table>\n";
    echo "</form>\n";
  }
  else
  {
    echo "is_int($number_sections) = " . (is_int($number_sections) ? "true" : "false");
  }
?>
<?php else: ?>
<?php
  $db = mysql_connect("localhost", "root", "honors7u") or die("Database connect failed");
  mysql_select_db("honors") or die("Table select failed");

  $sql = "select id from form_types where id = \"$type\"";

  $result = mysql_query($sql, $db) or die("Querry \"$sql\" failed");
  $rows = mysql_num_rows($result);

  if($rows != 1)
    die("Error: \"$type\" not unique form identifier\n");
  else
    $id = mysql_result($result, 0, 0);

  $sql = "delete from form_composition where form_id = \"$id\"";

  mysql_query($sql, $db) or die("Querry \"$sql\" failed");

  $sql = "select id, name from units";

  $result = mysql_query($sql, $db) or die("Querry \"$sql\" failed");
  $rows = mysql_num_rows($result);

  if($rows <= 0)
    die("Error: $rows units returned returned from \"$sql\"\n");

  for($i = 0; $i < $number_sections; $i++)
    for($j = 0; $j < $rows; $j++)
    {
      if(${ereg_replace(" ", "_", mysql_result($result, $j, 1))}[$i] > 0)
      {
        $sql = "insert into form_composition (unit_id, form_id, section_number, number_credits) values (" . mysql_result($result, $j, 0) . ", $id, $i, " . ${ereg_replace(" ", "_", mysql_result($result, $j, 1))}[$i] . ")";
	mysql_query($sql, $db) or die("Querry \"$sql\" failed");
      }
    }

  $sql = "select section_number, unit_id, units.name as unit_name, number_credits from form_composition, units where form_id = \"$id\" and units.id = form_composition.unit_id order by section_number, unit_id";

  $result = mysql_query($sql, $db) or die("Querry \"$sql\" failed");
  $rows = mysql_num_rows($result);

  if($rows <= 0)
    die("Empty form");

  echo "<ul>\n";
  while($row = mysql_fetch_array($result))
  {
    if(isset($p_sec_num) && $p_sec_num != $row["section_number"])
    {
      echo "</ul>\n";
      echo "</ul><hr><ul>\n";
    }

    if(!isset($p_sec_num) || $p_sec_num != $row["section_number"])
    {
      echo "<li>Section #" . ($row["section_number"] + 1) . "</li>\n";
      echo "<ul>\n";
      $p_sec_num = $row["section_number"];
    }

    $sql = "select options.name from options, unit_composition where unit_composition.unit_id = " . $row["unit_id"] . " and options.id = unit_composition.option_id";

    $uresult = mysql_query($sql, $db) or die("Querry \"$sql\" failed");

    echo "<li>" . $row["unit_name"] . " Unit (Choose " . $row["number_credits"] . ")</li>\n";
    echo "<ul>\n";
    while($row = mysql_fetch_array($uresult))
      echo "<li>" . $row["name"] . "</li>\n";
    echo "</ul>\n";
  }
  echo "</ul>\n";
  echo "</ul>\n";
?>
<?php endif; ?>
<hr>
Written by: Will
</body>
</html>






