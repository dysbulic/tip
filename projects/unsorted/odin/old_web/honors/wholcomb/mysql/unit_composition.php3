<html>
<head>
  <title>Honors forms generation</title>
</head>
<body bgcolor="#ffffff" fgcolor="#000000">
<?php if(!isset($unit)): ?>
<p>Select a unit to change the options for:</p>
<hr>
<?php
  $db = mysql_connect("localhost", "root", "honors7u");
  mysql_select_db("honors");

  $sql = "select id, name from units order by id";
  
  $result = mysql_query($sql, $db);
  $rows = mysql_num_rows($result);

  if($rows == 0)
    echo "There are no units in the database";
  else
  {
    echo "<form name=\"UnitEdit\" METHOD=\"POST\" ACTION=\"" . getenv("SCRIPT_NAME") . "\">\n";
    while($row = mysql_fetch_array($result))
      echo "<input type=\"radio\" name=\"unit\" value=\"" . $row["id"] . "\">" . $row["name"] . " Unit<br>\n";
    echo "<input type=\"submit\" value=\"Edit\">\n";
    echo "<input TYPE=\"reset\" VALUE=\"Reset\">\n";
    echo "</form>\n";
  }
?>
<?php elseif (!isset($unit_comp)): ?>
<?php
  $db = mysql_connect("localhost", "root", "honors7u");
  mysql_select_db("honors");

  $sql = "Select name from units where id = $unit";
  $result = mysql_query($sql, $db);
  $rows = mysql_num_rows($result);

  if($rows == 1)
  {
    echo "<p>Choose options for " . mysql_result($result, 0, 0) . " unit:</p>\n";
    echo "<hr>\n";
  }
  else
  {
    die("<p>Error: search on unit number \"$unit\" returned $rows.</p>\n");
  }

  $sql = "select id, name, description from options order by id";
  $result = mysql_query($sql, $db);
  $rows = mysql_num_rows($result);

  if($rows > 0)
  {
    echo "<form name=\"UnitEdit\" METHOD=\"POST\" ACTION=\"" . getenv("SCRIPT_NAME") . "\">\n";
    echo "<input type=\"hidden\" name=\"unit\" value=\"$unit\">\n";
    while($row = mysql_fetch_array($result))
    {
      echo "<input type=\"checkbox\" name=\"unit_comp[]\" value=\"" . $row["id"] . "\">" . $row["name"] . ($row["description"] != "" ? ": " . $row["description"] : "") . "<br>\n";
    }
    echo "<input type=\"submit\" value=\"Select\">\n";
    echo "<input TYPE=\"reset\" VALUE=\"Reset\">\n";
    echo "</form>\n";
  }
  else
    echo "There are no activities to be selected from. <br>\n";
?>
<?php else: ?>
<?php
  $db = mysql_connect("localhost", "root", "honors7u");
  mysql_select_db("honors");

  $sql = "Select name from units where id = $unit";
  $result = mysql_query($sql, $db);
  $rows = mysql_num_rows($result);

  if($rows == 1)
  {
    echo "<p>Options for " . mysql_result($result, 0, 0) . " unit:</p>\n";
    echo "<hr>\n";
  }
  else
  {
    echo "<p>Error in units database; search on unit number \"$unit\" returned $rows.</p>\n";
    die();
  }

  $sql = "delete from unit_composition where unit_id = $unit";
  $result = mysql_query($sql, $db);

  while (list($key, $val) = each($unit_comp))
  {
    $sql = "insert into unit_composition (unit_id, option_id) values ($unit, $val)";
    $result = mysql_query($sql, $db);
  }

  $sql = "select distinct options.id, options.name from options, unit_composition where options.id = unit_composition.option_id and unit_composition.unit_id = $unit order by options.id";
  $result = mysql_query($sql, $db);
  $rows = mysql_num_rows($result);

  if($rows > 0)
  {
    echo "<ul>\n";
    while($row = mysql_fetch_array($result))
      echo "<li>" . $row["id"] . ": " . $row["name"] . "</li>\n";
    echo "</ul>\n";
  }
  else
  {
    echo "<p>Unit has $rows options selected</p>\n";
  }
?>
<?php endif; ?>
<hr>
Written by Will
</body>
</html>
    










