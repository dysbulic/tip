<?php

require('points_db_info.inc');
$link = mysql_connect($host, $user, $password);
@mysql_select_db($database) or print("<!-- Unable to select database: $database -->");

$error = "";

if(isset($_POST['action'])) {
  switch($_POST['action']) {
  case 'Add Points':
    for($index = 0; isset($_POST['user_id'][$index]); $index++) {
      $query = sprintf("INSERT INTO points(user_id, metric_id, points, description) VALUES ('%s', '%s', '%s', '%s')",
                       mysql_real_escape_string($_POST['user_id'][$index], $link),
                       mysql_real_escape_string($_POST['metric_id'][$index], $link),
                       mysql_real_escape_string($_POST['points'][$index], $link),
                       mysql_real_escape_string($_POST['description'][$index], $link));
      $result = mysql_query($query);
      if(!$result) { $error .= mysql_error(); }
    }
    break;
  case 'Remove Points':
    if(!isset($_POST['id'])) {
      $error = "Error: ID is not set";
    } else {
      $query = sprintf("DELETE FROM points WHERE id = '%s'",
                       mysql_real_escape_string($_POST['id'], $link));
      $result = mysql_query($query);
      if(!$result) { $error = mysql_error(); }
    }
    break;
  case 'Edit':
    $id = isset($_POST['id']) ? $_POST['id'] : $_GET['id'];
    $name = isset($_POST['name']) ? $_POST['name'] : $_GET['name'];
    if(!isset($id)) {
      $error = "Error: ID is not set";
    } else {
      $query = sprintf("UPDATE points SET name = '%s' WHERE id = '%s'",
                       mysql_real_escape_string($name, $link),
                       mysql_real_escape_string($id, $link));
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