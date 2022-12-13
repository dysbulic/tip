<HTML>
<HEAD>
  <title>Search page</title>
</HEAD>
<body>
<?php
  require "print_functions.php3"
?>

<?php if(!isset($name)): ?>

<FORM NAME="NameSearch" METHOD="POST" ACTION=<?php echo "\"" . getenv("SCRIPT_NAME") . "\"";?>>
Name:&nbsp;
<input type="text" name="name" size="25">
<input TYPE="submit" VALUE="Search">
<input TYPE="submit" NAME="name" VALUE="Select All">
<input TYPE="reset" VALUE="Reset">
</form>

<?php else:
  $db = mysql_connect("localhost", "will", "aq1sw2");
  mysql_select_db("wills_addresses");

  if($name == "Select All")
  {
    $name = "";
    $rows = 0;
  }
  else
  {
    $searchname = str_replace(" ", "", $name);
    $sql = "select FirstName, MiddleName, LastName from Names where concat(FirstName, MiddleName, LastName) like \"$searchname\"";

    $result = mysql_query($sql, $db);
    $rows = mysql_num_rows($result);
  }

  if($rows == 0)
  {
    $searchname = str_replace(" ", "%", $name);
    $searchname = "%" . $searchname . "%";
    $sql = "select FirstName, MiddleName, LastName from Names where concat(FirstName, MiddleName, LastName) like \"$searchname\"";

    $result = mysql_query($sql, $db);
  }

  if(mysql_num_rows($result) == 0)
    print "No results for search string \"$name\"\n";
  else if(mysql_num_rows($result) > 1)
    print_list($result);
  else
  {
    $sql = "select ID from Names where concat(FirstName, MiddleName, LastName) like \"$searchname\"";
    $result = mysql_query($sql, $db);

    print_entry(mysql_result($result, 0, 0), $db);
  } 
?>

<?php endif; ?>
<HR>
Written by Will
</body>
</html>




