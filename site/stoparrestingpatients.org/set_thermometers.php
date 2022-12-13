<?php print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:tmpl="http://odin.himinbi.org/templ/0.1/"
      tmpl:iscontent="false">
  <head>
    <title>Set Thermometer Values</title>
    <style type="text/css">
ul { list-style: none; }
label span { display: block; float: left; text-align: right; width: 8em; padding-right: .5em; }
label span:after { content: ':'; }
    </style>
  </head>
  <body>
    <h1>Set Thermometer Values</h1>
<?php
require "db_auth_info.php";
mysql_connect($host, $user, $password);
@mysql_select_db($database) or die( "Unable to select database");

if($_POST['id'] != "") {
  print "<ul>";
  for($i = 0; $i < count($_POST['id']); $i++) {
    if($_POST['value'][$i] != "") {
      $id = $_POST['id'][$i];
      $description = $_POST['desc'][$i];
      $value = $_POST['value'][$i];
      $query = sprintf('insert into thermometer_values(thermometer, value) values ("%s", "%s")',
                       mysql_real_escape_string($id), mysql_real_escape_string($value));
      mysql_query($query);
      print "<li>Set $description to $value</li>";
    }
  }
  print "</ul>";
}

$query = "select id, description from thermometers";
$result = mysql_query($query);

print '<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post">' . "\n";
print "<ul>\n";

for($i = 0; $i < mysql_numrows($result); $i++) {
  $description = mysql_result($result, $i, "description");
  $id = mysql_result($result, $i, "id");
  print("<input type='hidden' name='id[]' value='$id'/>\n");
  print("<input type='hidden' name='desc[]' value='$description'/>\n");
  $query = "select value from thermometer_values where thermometer = $id order by time desc limit 1";
  $nextresult = mysql_query($query);
  if(mysql_numrows($nextresult) > 0) {
    $value = mysql_result($nextresult, 0, 'value');
  } else {
    $value = 0;
  }
  print("<li><label><span>$description</span><input type='text' name='value[]' value='$value'/></li>\n");
}

print '<li><input type="submit" />';
print "</ul>\n";

mysql_close();
?>
    <p><a href=".">Return to homepage</a></p>

  </body>
</html>
