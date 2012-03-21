<?php
  function space_capitols($str)
  {
    $outstr = ereg_replace("(^[[:print:]][a-z]*)([[:print:]]*)", "\\1", $str);
    $str = ereg_replace("(^[[:print:]][a-z]*)", "", $str);
    while($str != "")
    {
      $outstr = $outstr . " " . ereg_replace("(^[[:print:]][a-z]*)([[:print:]]*)", "\\1", $str);
      $str = ereg_replace("(^[[:print:]][a-z]*)", "", $str);
    }
    return $outstr;
  }
?>  

<?php
  function print_result($result, $db = 0)
  {
    if($db != 0)
      $result = mysql_query($result, $db);

    $cols = mysql_num_fields($result);
    $rows = mysql_num_rows($result);

    if($rows == 1)
    {
      print "<BR>\n";
      for($i = 0; $i < $cols; $i++)
      {
        print space_capitols(mysql_field_name($result, $i)) . ": " . mysql_result($result, 0, $i) . "\n";
	print "<br>\n";
      }
    }
    else if($rows > 1)
    {
      print "<table border=\"1\" cellspacing=\"2\" width=\"100%\">\n";
      print "<tr>\n";

      for($i = 0; $i < $cols; $i++)
      {
        $field_names = mysql_field_name($result, $i);
        print "<td><b>" . space_capitols($field_names) . "</b></td>\n";
      }

      print "</tr>";

      for ($j = 0; $j < $rows; $j++)
      {
        print "<tr>\n";
        for($i = 0; $i < $cols; $i++)
        {
          print "\t<td>" . mysql_result($result, $j, $i) . "</td>\n";
        }
        print "</tr>\n";
      }
      print "</table>\n";
    }
  }
?>

<?php
  function print_link_list($result, $db = 0)
  {
    if($db != 0)
      $result = mysql_query($result, $db);

    $cols = mysql_num_fields($result);
    $rows = mysql_num_rows($result);

    print "<table border=\"0\" cellspacing=\"2\">\n";

    for ($j = 0; $j < $rows; $j++)
    {
      print "<tr>\n";
      $link = "";
      for($i = 0; $i < $cols; $i++)
        $link = $link . mysql_result($result, $j, $i);
      for($i = 0; $i < $cols; $i++)
        print "<td><a href=\"" . getenv("SCRIPT_NAME") . "?name=" . addslashes($link) . "\">" . mysql_result($result, $j, $i) . "</a></td>\n";
      print "</tr>\n";
    }
    print "</table>\n";
  }
?>

<?php
  function print_list($result, $db = 0)
  {
    if($db != 0)
      $result = mysql_query($result, $db);

    $cols = mysql_num_fields($result);
    $rows = mysql_num_rows($result);

    print "<table border=\"0\" cellspacing=\"2\">\n";

    for ($j = 0; $j < $rows; $j++)
    {
      print "<tr>\n";
      $link = "";
      for($i = 0; $i < $cols; $i++)
        $link = $link . mysql_result($result, $j, $i);
      for($i = 0; $i < $cols; $i++)
        print "<td><a href=\"" . getenv("SCRIPT_NAME") . "?name=" . addslashes($link) . "\">" . mysql_result($result, $j, $i) . "</a></td>\n";
      print "</tr>\n";
    }
    print "</table>\n";
  }
?>

<?php
  function print_entry($id, $db)
  {
    $sql = "select FirstName, MiddleName, LastName from Names where ID = $id";
    $result = mysql_query($sql, $db);
    print_result($result);

    if(mysql_num_rows($result) != 1)
    {
      print "Something is wrong in the print_entry function; duplicate names.\n";
      return false;
    }

    $sql = "select Street, Apartment, City, State, Zip, Type from Addresses, AddressType where NameID = $id and AddressTypeID = AddressType.ID";
    print_result($sql, $db);

    $sql = "select Birthday from Birthdays where NameID = $id";
    print_result($sql, $db);

    $sql = "select Nickname from Nicknames where NameID = $id";
    print_result($sql, $db);

    $sql = "select Username, Domain, Type from EmailAddresses, EmailAddressType where NameID = $id and EmailAddressTypeID = EmailAddressType.ID";
    print_result($sql, $db);

    $sql = "select AreaCode, PhoneNumber, Extension, Type from PhoneNumbers, PhoneNumberType where NameID = $id and PhoneNumberTypeID = PhoneNumberType.ID";
    print_result($sql, $db);

    print "<Form action=\"edit.php3\" method=\"post\">\n";
    print "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";
    print "<input type=\"submit\" value=\"Edit Entry\">";
    print "</form>";
  }
?>

<?php
  function print_edit_entry($id, $db)
  {
    print "<Form action=\"edit.php3\" method=\"post\">\n";
    print "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

    $sql = "select FirstName, MiddleName, LastName from Names where ID = $id";
    $result = mysql_query($sql, $db);

    if(mysql_num_rows($result) != 1)
    {
      print "Something is wrong in the print_entry function; duplicate names.\n";
      return false;
    }

    print_result($result);
    print "<input type=\"submit\" name=\"type\" value=\"Change Name\">\n";

    $sql = "select Street, Apartment, City, State, Zip, Type from Addresses, AddressType where NameID = $id and AddressTypeID = AddressType.ID";
    print_result($sql, $db);
    print "<input type=\"submit\" name=\"type\" value=\"Edit Addresses\">\n";

    $sql = "select Birthday from Birthdays where NameID = $id";
    print_result($sql, $db);
    print "<input type=\"submit\" name=\"type\" value=\"Change Birthday\">\n";

    $sql = "select Nickname from Nicknames where NameID = $id";
    print_result($sql, $db);
    print "<input type=\"submit\" name=\"type\" value=\"Edit Nicknames\">\n";

    $sql = "select Username, Domain, Type from EmailAddresses, EmailAddressType where NameID = $id and EmailAddressTypeID = EmailAddressType.ID";
    print_result($sql, $db);
    print "<input type=\"submit\" name=\"type\" value=\"Edit E-mail Addresses\">\n";

    $sql = "select AreaCode, PhoneNumber, Extension, Type from PhoneNumbers, PhoneNumberType where NameID = $id and PhoneNumberTypeID = PhoneNumberType.ID";
    print_result($sql, $db);
    print "<input type=\"submit\" name=\"type\" value=\"Edit Phone Numbers\">\n";

    print "</form>";
  }
?>







