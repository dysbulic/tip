<html>
<head>
<meta NAME="GENERATOR" CONTENT="Will using emacs">
<meta NAME="DATE" CONTENT="06/18/1999">
<meta NAME="Author" CONTENT="Will Holcomb">
<title>Freshman/Transfer HPEO Commitment Form</title>
</head>
<body TEXT="#000000" LINK="#0000FF" VLINK="#551A8B" ALINK="#FF0000" BGCOLOR="#FCDADB">
<p align="center">
<h2><strong>Freshmen and Transfer Student HPEO Commitment Form</strong> (&quot;the pink page&quot;)</h2>
</p>
<p align="justify">
<font color="#0000FF" face="Times New Roman" size="3">
<strong>Last updated: June 18, 1999</strong>
</font>
</p>
<p align="justify">
<h4><font face="Times New Roman" size="3">
Be sure to include <b>ALL</b> of the personal information below. To help you in making your choices you may get a hard copy of this form at the Honors center. Some activities may satisfy more than one unit, so some flexibility is allowed. Double-counting is not. Please check with the Honors Directors or e-mail <a href="mailto:edlisic@tntech.edu">Dr. Lisic</a>, if you have questions about this.
</font></h4>
</p>

<form method="POST" action="freshman_hpeo_results.php3">
<input type="hidden" name="Semester" value="Fall 1999">

<table BORDER="0" WIDTH="100%">
  <tr>
    <td>First Name: <input type="text" name="FirstName" size="25"></td>
    <td>Middle Initial: <input type="text" name="MiddleInitial" size="2" maxlength="1"></td>
    <td>Last Name:&nbsp; <input type="text" name="LastName" size="25"></td>
  </tr>
</table>

<table BORDER="0" WIDTH="100%">
  <tr>
    <td>Social Security #:&nbsp; <input type="text" name="SSNo" size="11" maxlength="11"></td>
    <td>E-Mail Address:&nbsp; <input type="text" name="EMail" size="30"></td>
  </tr>
</table>

<table BORDER="0" WIDTH="100%">
 <tr>
   <td colspan="2">
     Please indicate your HPEO choices below
     <br>
     (1x): You may use this activity only one semester per year as an official unit
     <br>
     (2x): You may use this activity both semesters each year.
     <br><br>
    </td>
  </tr>
</table>

<?php
 $honors_db = mysql_connect("localhost", "root", "honors7u");
 mysql_select_db("honors");

 function form_make($type, $query, $number, $title, $qualifier, $form_name)
 {
   echo "<table BORDER=\"0\" WIDTH=\"100%\">";
   print "\n";
   echo "<tr>";
   print "\n";
   echo "<td colspan=\"2\">";
   print "\n";
   echo $number . " <u><strong>" . $title . "</strong></u>: ";

   if($qualifier != "")
     echo "(" . $qualifier . ")";

   echo "<br>";
   echo "\n";

   $result = mysql_query($query);
   $n_records = mysql_num_rows($result);

   for($j=0; $j < $n_records; $j++)
   {
     $box_count = 0;
     $number_possible = mysql_result($result, $j, 6);
     $orig_name = mysql_result($result, $j, 1);
     $activity_description = mysql_result($result, $j, 2);
     $credits = mysql_result($result, $j, 7);

     if(mysql_result($result, $j, 4) == "1")
       $is_specify = "TRUE";
     else
       $is_specify = "FALSE";

     for($i = 0; $i < $number_possible; $i++)
     {
       if($number_possible > 1)
       {
         $box_count++;
         $name = $orig_name . " " . $box_count;
       }
       else
         $name = $orig_name;

       print "<input type=\"" . $type . "\" ";
       print "name=\"" . $form_name . "[]\" ";
       print "value=\"" . $name . "\">";

       print "\n";

       print $name;
       print "\n";
	 
       if($activity_description != "")
       {
	 print " -- ";
	 print $activity_description;
         print "\n";
       }

       if($credits != "")
       {
	 print " -- ";
	 print "(" . $credits . "x)";
         print "\n";
       }

       print "</input>";
       print "\n";

       if($is_specify == "TRUE")
       {
	 print ":&nbsp;";
	 print "<input type=\"text\" name=\"" . $name . "\" size=\"45\">";

	 print "</input>";
	 print "\n";
       }
	 print "<br>";
         print "\n";
     }
   }
   print "</td>";
   print "\n";
   print "</tr>";
   print "\n";
   print "</table>";
   print "\n";
 }

 form_make(
  "radio",
  "SELECT * FROM activities WHERE unit_ID = 1 && experience = '0'",
  "I.",
  "Work Unit",
  "Choose one of these.", 
  "WorkUnit");

 form_make(
  "radio",
  "SELECT * FROM activities WHERE unit_ID = 2 && experience = '0'",
  "IIa.",
  "Group Participation Unit",
  "Either choose one of these or one service unit.", 
  "GroupParticipationUnit");

 form_make(
  "radio",
  "SELECT * FROM activities WHERE unit_ID = 6 && experience = '0'",
  "IIb.",
  "Service Unit",
  "We prefer that you wait a semester to get accustomed to the college environment before doing one of these.", 
  "ServiceUnit");

 form_make(
  "radio",
  "SELECT * FROM activities WHERE unit_ID = 3 && experience = '0'",
  "IIIa.",
  "Developmental Unit",
  "Choose one of these or four activity units.", 
  "DevelopmentalUnit");

 form_make(
  "checkbox",
  "SELECT * FROM activities WHERE unit_ID = 4 && experience = '0'",
  "IIIb.",
  "Activities Unit",
  "Either choose one Developmental Unit or four activities.", 
  "ActivitiesUnit");
?>

<table>
  <tr>
    <td colspan="2">
There are a limited number of slots available for certain units. Please check with the Honors Office about your assignment if you choose one of these activities.
    </td>
  </tr>
  <tr>
    <td colspan="2">
     <div align="center">
     <center>
      <input TYPE="submit" NAME="FORM" VALUE="Submit Form to the Honors Center">
      <input TYPE="reset" VALUE="Reset Form">
     </center>
    </td>
  </tr>
</table>

</form>

</body>
</html>