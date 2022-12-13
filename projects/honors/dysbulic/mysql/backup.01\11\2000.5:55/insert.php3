<?php if(isset($LastName)): ?>
<?php
  $db = mysql_connect("localhost", "will", "aq1sw2");
  mysql_select_db("wills_addresses");

  $sql = "select FirstName, MiddleName, LastName from Names where concat(FirstName, MiddleName, LastName) like \"" . $FirstName . $MiddleName . $LastName . "\"";
  mysql_query($sql);
  if(mysql_affected_rows() < 1)
  {
    $sql = "insert into Names (FirstName, MiddleName, LastName) values (\"$FirstName\", \"$MiddleName\", \"$LastName\")";
    mysql_query($sql);// or die "we've got trouble";
  }
  else
  {
    echo (isset($FirstName) ? "$FirstName " : "") .
         (isset($MiddleName) ? "$MiddleName " : "") .
         (isset($LastName) ? "$LastName " : "") .
         "is already in the database.";
  }
?>
<?php else: ?>
<FORM NAME="NameInsert" METHOD="POST" ACTION="<?php echo getenv("SCRIPT_NAME");?>">
First Name:&nbsp; <input type="text" name="FirstName" size="25"><br>
Middle Name:&nbsp; <input type="text" name="MiddleName" size="25"><br>
Last Name:&nbsp; <input type="text" name="LastName" size="25"><br>
<input TYPE="submit" VALUE="Insert">
<input TYPE="reset" VALUE="Reset">
</form>
<?php endif; ?>