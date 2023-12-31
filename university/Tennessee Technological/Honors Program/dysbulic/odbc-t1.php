<html>
<head>
<title>Quick &amp; dirty ODBC test</title>
</head>
<body>
<h1>ODBC Test 1 - Connection</h1>
<?php
if(isset($_GET['dbuser'])){
?>
Connecting to <?php echo $_GET['dsn'] ?> as <?php echo $_GET['dbuser'] ?>
<p>
<?php
  if(function_exists("odbc_connect")) $conn = odbc_connect($_GET['dsn'],$_GET['dbuser'],$_GET['dbpwd']);
  if(isset($conn) && !$conn) {
?>
<h2>Error connecting to database! Check DSN, username and password</h2>
<?php
  } else {
?>
<h2>Connection successful</h2>
<a href="<?php print $_SERVER['SCRIPT_NAME'].replace("1","2").'?dbuser='.$dbuser.'&dsn='.$dsn.'&dbpwd='.$dbpwd ?>">Proceed to next test</a>
| <a href="<?php print $_SERVER['SCRIPT_NAME'] ?>">Change login information</a>
<?php
  }
} else {
?>
<em>You will need permisson to create tables for the following tests!</em>
<form action="<?php print $_SERVER['SCRIPT_NAME'] ?>">
<table>
<tr><td>Database (DSN): </td><td><input type="text" name="dsn"></td></tr>
<tr><td>User: </td><td><input type="text" name="dbuser"></td></tr>
<tr><td>Password: </td><td><input type="password" name="dbpwd"></td></tr>
</table>
<br>
<input type="submit" value="connect">

</form>
<?php
} ?>
</body>
</html>
