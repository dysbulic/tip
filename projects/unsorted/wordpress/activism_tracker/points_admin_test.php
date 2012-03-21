<?php print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n" ?>
<?php
require('points_db_info.inc');
mysql_connect($host, $user, $password) or print("<!-- Unable to connect to: $user@$host -->\n");
mysql_select_db($database) or print("<!-- Unable to select database: $database -->\n");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Activist Points Interface Test</title>
    <link rel="stylesheet" type="text/css" href="../styles/main.css" />
    <style type="text/css">
      .disabled { display: none; }
      table { border-collapse: collapse; }
      th, td { padding: .25em .5em; border: 1px solid; }
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
    <script type="text/javascript">//<![CDATA[
      var metrics = {
<?php
$query = ("SELECT id, name from metrics");
$result = mysql_query($query);
$metricCount = 0;
if(!$result) {
  print mysql_error();
} else {
  $metricCount = @mysql_num_rows($result);
  for($rows = mysql_num_rows($result), $i = 0; $i < $rows; $i++) {
    printf("'%s' : '%s'%s",
           mysql_result($result, $i, 0),
           mysql_result($result, $i, 1),
           ($i + 1 < $rows ? ",\n" : ""));
  }
}
?>
      };
    //]]></script>
    <script src="points_admin_test_form.js" type="text/javascript"></script>
  </head>
  <body>
    <h1>Add Metrics</h1>
    <form action="metrics_edit.php" method="post">
      <div>
        <label>Metric Name:</label>
        <input type="text" name="newmetric"></input>
        <input type="submit" name="action" value="New Metric"></input>
      </div>
    </form>

    <hr />

<?php if($metricCount > 0) { ?>

    <h1>Edit Metrics</h1>
    <form action="metrics_edit.php" method="post" id="editmetrics">
      <table>
        <thead>
          <tr><th></th><th>Metric</th><th></th></tr>
        </thead>
        <tbody>
<?php
$query = ("SELECT id, name from metrics");
$result = mysql_query($query);
if(!$result) { print '' . mysql_error(); }
while($row = mysql_fetch_array($result)) {
?>
  <tr>
    <form action="metrics_edit.php" method="post">
      <td>
        <?php print "<input type='hidden' name='id' value='$row[0]'></input>\n" ?>
        <input type="submit" name="action" value="Remove Metric"></input>
      </td>
      <td>
        <?php print "<input type='text' name='name' value='$row[1]'></input>\n" ?>
      </td>
      <td>
        <input type="submit" name="action" value="Rename Metric"></input>
      </td>
    </form>
  </tr>
<?php
}
?>
        </tbody>
      </table>
    </form>
    
    <hr />
    
    <h1>Add Points</h1>
    <form action="" onsubmit="addPoints(event); return false;">
      <div>
        <label>Add Points to Activist:</label>
        <select name="userid">
<?php
$query = ("SELECT id, display_name FROM wp_users ORDER BY display_name");
$result = mysql_query($query);
if(!$result) { print '' . mysql_error(); }
while($row = mysql_fetch_array($result)) {
  print "<option value='$row[0]'>$row[1]</option>\n";
}
?>
        </select>
        <input type="button" value="Add Points" onclick="addPoints(event)"></input>
      </div>
    </form>
    <form action="points_edit.php" method="post" id="addpoints" class="disabled">
      <table>
        <thead>
          <tr><th></th><th>Activist</th><th>Metric</th><th>Points</th><th>Description</th></tr>
        </thead>
        <tbody id="points">
        </tbody>
      </table>
      <div><input type="submit" name="action" value="Add Points" /></div>
    </form>
    
    <hr />

<?php
$query = ("SELECT points.id, display_name, metrics.name, points, description" .
          " FROM points JOIN wp_users ON points.user_id = wp_users.id" .
          " JOIN metrics ON metric_id = metrics.id");
$result = mysql_query($query);
if(!$result) { print '' . mysql_error(); }
if(@mysql_num_rows($result) > 0){
?>
    <h1>Edit Points</h1>
    <table>
      <thead>
        <tr><th></th><th>Activist</th><th>Metric</th><th>Points</th><th>Description</th><th></th></tr>
      </thead>
      <tbody>
<?php
while($row = mysql_fetch_array($result)) {
?>
  <tr>
    <form action="points_edit.php" method="post">
      <td>
        <?php print "<input type='hidden' name='id' value='$row[0]'></input>\n" ?>
        <input type="submit" name="action" value="Remove Points"></input>
      </td>
      <td><?php print $row[1] ?></td>
      <td><?php print $row[2] ?></td>
      <td>
        <?php print "<input type='text' name='points' value='$row[3]'></input>\n" ?>
      </td>
      <td>
        <?php print "<input type='text' name='description' value='$row[4]'></input>\n" ?>
      </td>
      <td>
        <input type="submit" name="action" value="Save Points"></input>
      </td>
    </form>
  </tr>
<?php
}
?>
      </tbody>
    </table>

<?php }  } ?>

  </body>
</html>
