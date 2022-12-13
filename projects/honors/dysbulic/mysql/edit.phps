<?php
  require "print_functions.php3";

  $db = mysql_connect("localhost", "will", "aq1sw2");
  mysql_select_db("wills_addresses");

  if(isset($id))
  {
    if(!isset($type))
      print_edit_entry($id, $db);
    else
      switch($type)
      {
        case "Change Name":
	  print "Changing name...\n<BR>\n";
	  break;
        case "Edit Addresses":
	  print "Changing addresses...\n<BR>\n";
	  break;
        case "Change Birthday":
	  print "Changing birthday...\n<BR>\n";
	  break;
        case "Edit Nicknames":
	  print "Changing nickname...\n<BR>\n";
	  break;
        case "Edit E-mail Addresses":
	  print "Changing E-mail addresses...\n<BR>\n";
	  break;
        case "Edit Phone Numbers":
	  print "Changing phone number...\n<BR>\n";
	  break;
	default:
	  print "\"" . $type . "\" is an unrecognized option to " . getenv("SCRIPT_NAME") . "\n";
	  break;
	}
  }
?>