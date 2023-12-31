<html>
<body bgcolor="#ffffff">
This is a test.
<br><br>
<?php
$honors_db = mysql_connect("localhost", "root", "whatever");
mysql_select_db("honors");

if(sizeof($HTTP_POST_VARS) != 0)
{
  print "<ul>";
  while(list($key, $post) = each($HTTP_POST_VARS))
  {
    echo "<li>$key</li>";
    echo "<ul>";
    if(is_array($post))
      while(list($index, $val) = each($post))
      {
        if($val == "")
	  $val = "Empty";
        echo "<li>$index: $val</li>";
      }
    else
    {
      if($post == "")
	$post = "Empty";
      echo "<li>$post</li>";
    }
    echo "</ul>";
  }
  echo "</ul>";
}

print "<table border=\"1\" cellspacing=\"2\">";
print "<tr>";
$result = mysql_query("SELECT * FROM activities WHERE unit_ID = 1 && experience = 'n'");
$n_columns = mysql_num_fields($result);
$n_records = mysql_num_rows($result);
for($i = 0; $i < $n_columns; $i++)
{
  $field_name = mysql_field_name($result, $i);
  print "<td><b>" . $field_name . "</b></td>";
}
print "</tr>";
for($j=0; $j < $n_records; $j++)
{
  print "<tr>";
  for($i = 0; $i < $n_columns; $i++)
  {
    $rows = mysql_result($result, $j, $i);      
    print "<td>" . $rows . "</td>";
  }
}
print "</TABLE>";
?>
</body>
</html>
