<?php
require('points_db_info.inc');
$link = mysql_connect($host, $user, $password);
@mysql_select_db($database) or print("<!-- Unable to select database: $database -->");

$error = "";

if(isset($_POST['action'])) {
  switch($_POST['action']) {
  case 'New Metric':
    if(isset($_POST['newmetric']) && $_POST['newmetric'] != '') {
      $query = sprintf("INSERT INTO metrics(name) VALUES ('%s')",
                       mysql_real_escape_string($_POST['newmetric'], $link));
      $result = mysql_query($query);
      if(!$result) { $error = mysql_error(); }
    }
    break;
  case 'Remove Metric':
    if(!isset($_POST['id'])) {
      $error = "Error: ID is not set";
    } else {
      $query = sprintf("DELETE FROM metrics WHERE id = '%s'",
                       mysql_real_escape_string($_POST['id'], $link));
      $result = mysql_query($query);
      if(!$result) { $error = mysql_error(); }
    }
    break;
  case 'Rename Metric':
    if(!isset($_POST['id'])) {
      $error = "Error: ID is not set";
    } else {
      $query = sprintf("UPDATE metrics SET name = '%s' WHERE id = '%s'",
                       mysql_real_escape_string($_POST['name'], $link),
                       mysql_real_escape_string($_POST['id'], $link));
      $result = mysql_query($query);
      if(!$result) { $error = mysql_error(); }
    }
    break;
  default:
    $error = "Unknown Action: $action";
  }
}

if($error != "") {
  header("Content-type: text/plain");
  print $error;
} else {
  header("Location: points_admin_test.php");
}
?>
