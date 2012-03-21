<?php
require_once("common.inc");
session_start();
print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <link rel="stylesheet" href="../styles/main.css" type="text/css" />
    <link rel="stylesheet" href="login_box.css" type="text/css" />
    <title>PHP Logins</title>
    <style type="text/css">
    </style>
  </head>
  <body>
    <h1>PHP Login</h1>
    <p>I have an application I want to write where people log in.</p>
    <hr />
<?php
if(isset($_GET['reset'])) {
  unset($_SESSION['db']);
}
if(!isset($_SESSION['db'])) { // get the db
  include('get_db.inc');
}
if(isset($_SESSION['db'])) { // set once a db is selected
  //if(!true) {
  //  include
  //}
  
  $db = get_db($_SESSION['db']);
  if(isset($_GET["username"])) {
    unset($_SESSION["user"]);
    $db = get_db();
    if(!$db) {
      print '<h3>Not possible to authenticate user: could not connect</h3>' . "\n";
    } else {
      $sql = "select password from users where username = '" . $_GET['username'] . "'";
      $result = dbx_query($db, $sql);
      if($result->rows == 0) {
        print '<h3>Not possible to authenticate user: unknown user</h3>' . "\n";
      } else {
        if($result->data[0][0] == $_GET['password']) {
          $_SESSION["user"] = new User($_GET['username']);
        } else {
          print "<h3>Not possible to authenticate user: bad password: " . $result->data[0][0] . "</h3>\n";
        }
      }
      dbx_close($db);
    }
  }
  if(isset($_SESSION['user'])) {
    $db = get_db();
    if(!$db) {
      print '<h3>Not possible to retrieve users: could not connect</h3>' . "\n";
    } else {
      $sql = "select username from users";
      $result = dbx_query($db, $sql);
      print "<table>\n";
      foreach($result->data as $row) {
        print "<tr>";
        foreach($row as $field) {
          print "<td>$field</td>";
        }
        print "</tr>\n";
      }
      print "</table>\n";
      dbx_close($db);
    }
  } else {
?>
    <div id="loginbox">
      <form action="<?php print $_SERVER["PHP_SELF"] ?>">
        <div class="line">
          <div class="label">Username:</div>
          <div class="field"><input type="text" name="username" /></div>
        </div>
        <div class="line">
          <div class="label">Password:</div>
          <div class="field"><input type="password" name="password" /></div>
        </div>
        <div class="line">
          <div class="field"><input type="submit" value="Login" /></div>
        </div>
      </form>
    </div>
<?php
  }
}
?>
    <hr />
    <a href="<?php print $_SERVER['SCRIPT_NAME'] ?>?reset">Reset</a>
  </body>
</html>
