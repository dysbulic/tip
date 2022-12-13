<html>
<head>
<meta NAME="Author" CONTENT="dysbulic">
<title>Freshman/Transfer HPEO Form Results</title>
</head>
<body TEXT="#000000" LINK="#0000FF" VLINK="#551A8B" ALINK="#FF0000" BGCOLOR="#FCDADB">
<?php
  $error = false;

  if($FirstName == "")
  {
    echo "Have you no first name? Try again.";
    echo "<br>";
    $error = true;
  }

  if($LastName == "")
  {
    echo "Have you no last name? Try again.";
    echo "<br>";
    $error = true;
  }

  if($WorkUnit == "")
  {
    echo "You are required to sign up for a work unit. Try again.";
    echo "<br>";
    $error = true;
  }

  if($GroupParticipationUnit == "" || ServiceUnit == "")
  {
    echo "You are required to either sign up for a group participation unit or a service unit. Try again.";
    echo "<br>";
    $error = true;
  }

  if($DevelopmentalUnit == "" && (sizeof($ActivityUnit) < 4)
  {
    echo "You are required to sign up for one developmental unit or four activity units. Try again.";
    echo "<br>";
    $error = true;
  }
	
  if($error)
  {
    echo "<form><input type=\"button\" value=\"Go Back\" onClick=\"history.back()\"></form>";
    exit();
  }

  echo "<table border=\"0\" width=\"800\">";
  echo "<tr>";
  echo "<td colspan=\"2\">";

  echo "<p align=\"justify\">";
  echo "I, $FirstName ";
  if($MiddleInitial != "")
    echo $MiddleInitial, ". ";
  echo "$LastName, ";
  if($SSNo != "")
    echo "of social security number $SSNo, ";
  echo "in order to complete my requirements for the Tennessee Technological University Honors Program Honors Program Enrichment Options hereby agree to:";
  echo "</p>";

  echo "</td>";
  echo "</tr>";
  echo "<tr>";
  echo "<td colspan=\"2\">";

  echo "<ul>";

  $process_dump = "";

  echo "<li>Work Unit:</li>";
  echo "<ul>";
  if($WorkUnit == "Other Work")
  {
    echo "<li>$WorkUnit: $Other_Work</li>";
    $process_dump .= "Work Unit: $WorkUnit: $Other_Work\n";
  }
  else
  {
    echo "<li>$WorkUnit</li>";
    $process_dump .= "Work Unit: $WorkUnit\n";
  }
  echo "</ul>";

  echo "<li>Group Participation/Service Unit:</li>";
  echo "<ul>";
  if($GroupParticipation != "")
  {
    if($GroupParticipationUnit == "Other Group Participation")
    {
      echo "<li>Group Participation: $Other_Group_Participation</li>";
      $process_dump .= "Group Participation:  $Other_Group_Participation\n";
    }
    else
    {
      echo "<li>Group Participation: $GroupParticipationUnit</li>";
      $process_dump .= "Group Participation: $GroupParticipationUnit\n";
    }
  }
  else
  {
    if($ServiceUnit == "Other Service")
    {
      echo "<li>Service Unit: $Other_Service</li>";
      $process_dump .= "Service Unit:  $Other_Service\n";
    }
    else
    {
      echo "<li>Service Unit: $ServiceUnit</li>";
      $process_dump .= "Service Unit: $ServiceUnit\n";
    }
  }
  echo "</ul>";

  echo "<li>Developmental Unit:</li>";
  echo "<ul>";
  if($DevelopmentUnit != "")
  {
    if($DevelopmentalUnit == "Other Development")
    {
      echo "<li>Development Unit: $Other_Development</li>";
      $process_dump .= "Development Unit:  $Other_Development\n";
    }
    else
    {
      echo "<li>Development Unit: $DevelopmentUnit</li>";
      $process_dump .= "Development Unit: $DevelopmentUnit\n";
    }
  }
  else
  if($DevelopmentalUnit == "B Options")
  {
    $process_dump .= "Developmental Unit:\n";
    while(list($key, $value) = each($DevelopmentalUnitB))
    {
      echo "<li>", $value, "</li>";
      $process_dump .= "  $value\n";
    }
  }
  else
  {
    echo "<li>$DevelopmentalUnit</li>";
    $process_dump .= "Developmental Unit: $DevelopmentalUnit\n";
  }
  echo "</ul>";

  echo "</ul>";
  echo "<br>";
  echo "For the semester $Semester.";
  $process_dump .= "Semester: $Semester";
  echo "<br><br>";

  echo "</td>";
  echo "</tr>";
  echo "<tr>";
  echo "<td></td>";
  echo "<td width=\"50%\" align=\"center\">";

  echo "<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>";
  echo "<br>";
  echo "<font size=\"-1\">$FirstName $LastName</font>";

  echo "</td>";
  echo "</tr>";
  echo "</table>";

  if($EMail != "")
  {
    echo "<br><br>";
    echo "<p align=\"justify\">";
    echo "A confirmation of this registration has been mailed to $EMail";
    echo "</p>";
    mail($EMail, "HPEO confirmation for $FirstName $LastName",
     "A form entering you into the HPEO program for the Tennessee Tech Honors \n" .
     "Program was submitted to hpeo.honors.tntech.edu with the following\n" .
     "information:" .
     "\n\n$process_dump\n\n" .
     "If you did not make this registration or if you have any questions \n" .
     "please contact hpeo@honors.tntech.edu. \n\n" .
     "                                                     -Will Holcomb",
     "From: Will Holcomb <will@honors.tntech.edu>\r\nX-Mailer: PHP/" . phpversion());
  }
?>
</body>
</html>





