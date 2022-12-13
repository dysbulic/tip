<?php
  $form_type = array(
   "Freshman" => "Freshman and Transfer Student HPEO Committment Form",
   "Sophomore" => "Sophomore HPEO Committment Form",
   "Upperclassman" => "Upperclassman HPEO Committment Form");

  $Admin_Email = "wjh3957@tntech.edu";
?>
<?php if(!isset($_POST['type'])): ?>
<html>
<head>
<title>Choose a form</title>
</head>
<body>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
<?php
  while(list($key, $val) = each($form_type))
    echo "<input type=\"submit\" name=\"type\" value=\"" . htmlspecialchars($val) . "\"><br>\n";
?>
</form>
</body>
</html>
<?php elseif(!isset($_GET['semester'])): ?>
<html>
<head>
<title><?php echo $_POST['type']; ?></title>
</head>
<?php switch($_POST['type']): ?>
<?php case $form_type["Freshman"]: ?>
<body TEXT="#000000" LINK="#0000FF" VLINK="#551A8B" ALINK="#FF0000" BGCOLOR="#FCDADB">
<?php break; ?>
<?php case $form_type["Sophomore"]: ?>
<body TEXT="#000000" LINK="#0000FF" VLINK="#551A8B" ALINK="#FF0000" BGCOLOR="#D0D0F2">
<?php break; ?>
<?php case $form_type["Upperclassman"]: ?>
<body TEXT="#000000" LINK="#0000FF" VLINK="#551A8B" ALINK="#FF0000" BGCOLOR="#FFFDB9">
<?php break; ?>
<?php default: ?>
<body TEXT="#000000" LINK="#0000FF" VLINK="#551A8B" ALINK="#FF0000" BGCOLOR="#FFFFFF">
<p>Type not recognized.</p>
<form name="reload" method="post" action="<?php echo getenv("SCRIPT_NAME"); ?>">
<input type="submit" value="Reload">
</form>
<?php break; ?>
<?php endswitch; ?>
<p align="center"><h2><?php echo $_POST['type']; ?></h2></p>
<h4>
<p><font color="#0000FF" size="3">Last Updated: January 13, 2000</font></p>
<p align="justify">Be sure to include <b>all</b> of the personal information below. To help you in making your choices you may get a hard copy of this form at the Honors center. Some activities may satisfy more than one unit, so some flexibility is allowed. Double-counting is not. Please check with the Honors Directors or e-mail <a href="mailto:edlisic@tntech.edu">Dr. Lisic</a>, if you have questions about this.</p>
<p align="justify">When everything is correct on your test form, please click the <i>Submit</i> at the bottom of the form indicating that all the information is accurate.</p>
</h4>
<form method="post" action="<?php echo getenv("SCRIPT_NAME"); ?>">
<input type="hidden" name="semester" value="Spring 2000">
<input type="hidden" name="type" value="<?php echo $_POST['type']; ?>">
<table border="0">
  <tr>
    <td>First Name:&nbsp;<input type="text" name="First_Name" size="25"></td>
    <td>Middle Name:&nbsp;<input type="text" name="Middle_Name" size="25"></td>
    <td>Last Name:&nbsp;<input type="text" name="Last_Name" size="25"></td>
  </tr>
  <tr>
    <td>Social Security Number:&nbsp;<input type="text" name="Social_Security_Number" size="11"></td>
    <td>E-mail Address:&nbsp;<input type="text" name="Email_Address" size="30"></td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td colspan="3">
 Please indicate your HPEO choices below<br>
 (1x): You may use this activity only one semester per year as an official unit<br>
 (2x): You may use this activity both semesters each year.<br>
    </td>
  </tr>
  <tr><td>&nbsp;</td></tr>
<?php switch($_POST['type']): ?>
<?php case $form_type["Freshman"]: ?>
  <tr>
    <th colspan="3" align="left">I. Work Unit: (Choose one)</th>
  </tr>
  <tr>
    <td colspan="3">
    <?php
      $data = array(
       "Work 20 hours for Honors Office (photocopy, answer phone, type: we need you!) (2x)",
       "Work 20 hours for other academic office (2x***)",
       "Work 20 hours for computer lab help desk (2x***)",
       "Work 20 hours for library (2x***)",
       "Work 20 hours for Dr. Lisic (2x***)",
       "Work 20 hours for Dr. Barnes (2x***)",
       "Work 20 hours for Dr. Hood (2x***)");
      echo "    <select name=\"Work_Unit\" size=\"1\">\n";
      echo "    <option value=\"\" selected>No work option</option>\n";
      while(list($key, $val) = each($data))
        echo "    <option value=\"$val\">I." . ($key + 1) . " $val</option>\n";
      echo "    </select>\n";
    ?>
    </td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <th colspan="3" align="left">II. Group Participation/Service Unit: (Choose one)</th>
  </tr>
  <tr>
    <td colspan="3">
    <?php
      $data = array(
       "Honors newsletter staff (2x)",
       "Computer Committee (2x) (help students and faculty with basic computer difficulties)",
       "Ecology Committee (2x) (coordinate Honors recycling efforts, give information to Honors students)",
       "Publicity Committee (2x) (help write flyers and hang them up around campus)",
       "Leadership Committee (2x) (set up and introduce speakers at workshops, etc)",
       "Social Committee (2x) (help plan Honors parties, buy goodies, greet people)",
       "Honors tutor (2x) (tutor in Honors Center 2 hours a week for 10 weeks)",
       "Campus interest group (1x for each specific group) (attend meetings and participate)",
       "Church student group (1x for each specific group) (attend meetings and participate)",
       "Sorority/fraternity (1x for each specific group) (attend meetings and participate)",
       "Intramural sports (1x for each sport)",
       "Putnam County Adult Education (2x*) (tutors)",
       "Cookeville Health Care Center (2x*) (visit lonely elderly patients)",
       "Cookeville Manor Nursing Home (2x*) (visit patients; lead games or singing, etc.)",
       "Cookeville/Putnam County Clean Commission (2x*) (recycling; clean-ups, etc.)",
       "Exchange Club/Holland J. Stephens Center (2x*) (child abuse education for the public)",
       "L. B. J. & C. Development Corp./Head Start (2x*) (children 3-5; play with kids, read to them)",
       "Even Start (2x*) (work with children under 8, adults)",
       "Triad Youth Home (2x*) (boys 13 - 17)",
       "RDS tutor (2x*)");
      echo "    <select name=\"Service_Unit[]\" size=\"1\">\n";
      echo "    <option value=\"\" selected>No service option</option>\n";
      while(list($key, $val) = each($data))
        echo "    <option value=\"$val\">II." . ($key + 1) . " $val</option>\n";
      echo "    <option value=\"\">Other service option</option>\n";
      echo "    </select>\n";
      echo "    <br>\n";
      echo "Other group or service involvement (must be approved by the Honors Director): <input type=\"text\" name=\"Service_Unit[]\" size=\"30\">\n";
    ?>
    </td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <th colspan="3"><h3>Choose <b>either</b> one developmental option or four activity options.</h3></th>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <th colspan="3" align="left">IIIA. Developmental Unit: (Choose one)</th>
  </tr>
  <tr>
    <td colspan="3">
    <?php
      $data = array(
       "Attend national Honors conference (1x - Fall)  (end of October, usually Wednesday-Sunday)",
       "Attend regional conference (1x - Spring)  (March or April, usually Thursday-Saturday)",
       "Attend state conference (1x - Spring) (end of February, usually all day on a Saturday)",
       "Attend Honors Day");
      echo "    <select name=\"Developmental_Unit\" size=\"1\">\n";
      echo "    <option value=\"\" selected>No developmental option</option>\n";
      while(list($key, $val) = each($data))
        echo "    <option value=\"$val\">IIIA." . ($key + 1) . " $val</option>\n";
      echo "    <option value=\"\">Using four activity options</option>\n";
      echo "    </select>\n";
    ?>
    </td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <th colspan="3" align="left">IIIB. Activity Unit: (Choose four)</th>
  </tr>
  <tr>
    <td colspan="3">
    <?php
      $data = array(
       "Travels with Faculty (attend, help set up; Friday nights)",
       "Travels with Faculty (attend, help set up; Friday nights)",
       "Travels with Faculty (attend, help set up; Friday nights)",
       "Honors Forum (attend and discuss; Thursday 11-noon)",
       "Honors Forum (attend and discuss; Thursday 11-noon)",
       "Honors Forum (attend and discuss; Thursday 11-noon)",
       "Thursday Evening with Faculty (Thursday 7-9 p.m.)",
       "Thursday Evening with Faculty (Thursday 7-9 p.m.)",
       "Thursday Evening with Faculty (Thursday 7-9 p.m.)",
       "Perform at the Honors Talent Show (Spring only)");
      $lastkey = 0;
      while(list($key, $val) = each($data))
      {
        echo "    <input type=\"checkbox\" name=\"Activity_Unit[]\" value=\"$val\">IIIB." . ($key + 1) . " $val<br>\n";
	$lastkey = $key;
      }
      for($i = 1; $i <= 4; $i++)
        echo "    IIIB." . ($lastkey + $i + 1) . " Workshop: <input type=\"text\" name=\"Activity_Unit[]\"><br>\n";
    ?>
    </td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <?php break; ?>
/*****
 * Begin Sophomore form
 *****/
<?php case $form_type["Sophomore"]: ?>
  <tr>
    <th colspan="3" align="left">I. Work Unit: (Choose one)</th>
  </tr>
  <tr>
    <td colspan="3">
    <?php
      $data = array(
       "Work 20 hours for Honors Office (photocopy, answer phone, type: we need you!) (2x)",
       "Work 20 hours for other academic office (2x***)",
       "Work 20 hours for computer lab help desk (2x***)",
       "Work 20 hours for library (2x***)",
       "Work 20 hours for Dr. Lisic (2x***)",
       "Work 20 hours for Dr. Barnes (2x***)",
       "Work 20 hours for Dr. Hood (2x***)");
      echo "    <select name=\"Work_Unit\" size=\"1\">\n";
      echo "    <option value=\"\" selected>No work option</option>\n";
      while(list($key, $val) = each($data))
        echo "    <option value=\"$val\">I." . ($key + 1) . " $val</option>\n";
      echo "    </select>\n";
    ?>
    </td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <th colspan="3" align="left">II. Group Participation/Service Unit: (Choose one)</th>
  </tr>
  <tr>
    <td colspan="3">
    <?php
      $data = array(
       "Honors newsletter staff (2x)",
       "Computer Committee (2x) (help students and faculty with basic computer difficulties)",
       "Ecology Committee (2x) (coordinate Honors recycling efforts, give information to Honors students)",
       "Publicity Committee (2x) (help write flyers and hang them up around campus)",
       "Leadership Committee (2x) (set up and introduce speakers at workshops, etc)",
       "Social Committee (2x) (help plan Honors parties, buy goodies, greet people)",
       "Honors tutor (2x) (tutor in Honors Center 2 hours a week for 10 weeks)",
       "Campus interest group (1x for each specific group) (attend meetings and participate)",
       "Church student group (1x for each specific group) (attend meetings and participate)",
       "Sorority/fraternity (1x for each specific group) (attend meetings and participate)",
       "Intramural sports (1x for each sport)",
       "Putnam County Adult Education (2x*) (tutors)",
       "Cookeville Health Care Center (2x*) (visit lonely elderly patients)",
       "Cookeville Manor Nursing Home (2x*) (visit patients; lead games or singing, etc.)",
       "Cookeville/Putnam County Clean Commission (2x*) (recycling; clean-ups, etc.)",
       "Exchange Club/Holland J. Stephens Center (2x*) (child abuse education for the public)",
       "L. B. J. & C. Development Corp./Head Start (2x*) (children 3-5; play with kids, read to them)",
       "Even Start (2x*) (work with children under 8, adults)",
       "Triad Youth Home (2x*) (boys 13 - 17)",
       "RDS tutor (2x*)");
      echo "    <select name=\"Service_Unit[]\" size=\"1\">\n";
      echo "    <option value=\"\" selected>No service option</option>\n";
      while(list($key, $val) = each($data))
        echo "    <option value=\"$val\">II." . ($key + 1) . " $val</option>\n";
      echo "    <option value=\"\">Other service option</option>\n";
      echo "    </select>\n";
      echo "    <br>\n";
      echo "Other group or service involvement (must be approved by the Honors Director): <input type=\"text\" name=\"Service_Unit[]\" size=\"30\">\n";
    ?>
    </td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <th colspan="3"><h3>Choose <b>either</b> one developmental option or four activity options.</h3></th>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <th colspan="3" align="left">IIIA. Developmental Unit: (Choose one)</th>
  </tr>
  <tr>
    <td colspan="3">
    <?php
      $data = array(
       "Attend national Honors conference (1x - Fall)  (end of October, usually Wednesday-Sunday)",
       "Attend regional conference (1x - Spring)  (March or April, usually Thursday-Saturday)",
       "Attend state conference (1x - Spring) (end of February, usually all day on a Saturday)",
       "Attend Honors Day");
      echo "    <select name=\"Developmental_Unit\" size=\"1\">\n";
      echo "    <option value=\"\" selected>No developmental option</option>\n";
      while(list($key, $val) = each($data))
        echo "    <option value=\"$val\">IIIA." . ($key + 1) . " $val</option>\n";
      echo "    <option value=\"\">Using four activity options</option>\n";
      echo "    </select>\n";
    ?>
    </td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <th colspan="3" align="left">IIIB. Activity Unit: (Choose four)</th>
  </tr>
  <tr>
    <td colspan="3">
    <?php
      $data = array(
       "Travels with Faculty (attend, help set up; Friday nights)",
       "Travels with Faculty (attend, help set up; Friday nights)",
       "Travels with Faculty (attend, help set up; Friday nights)",
       "Honors Forum (attend and discuss; Thursday 11-noon)",
       "Honors Forum (attend and discuss; Thursday 11-noon)",
       "Honors Forum (attend and discuss; Thursday 11-noon)",
       "Thursday Evening with Faculty (Thursday 7-9 p.m.)",
       "Thursday Evening with Faculty (Thursday 7-9 p.m.)",
       "Thursday Evening with Faculty (Thursday 7-9 p.m.)",
       "Perform at the Honors Talent Show (Spring only)");
      $lastkey = 0;
      while(list($key, $val) = each($data))
      {
        echo "    <input type=\"checkbox\" name=\"Activity_Unit[]\" value=\"$val\">IIIB." . ($key + 1) . " $val<br>\n";
	$lastkey = $key;
      }
      for($i = 1; $i <= 4; $i++)
        echo "    IIIB." . ($lastkey + $i + 1) . " Workshop: <input type=\"text\" name=\"Activity_Unit[]\"><br>\n";
    ?>
    </td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td colspan="3"><h4>* Community service (We prefer that you wait at least a semester to get involved in these: get adjusted to TTU first. Each unit requires at least 20 hours a semester.)</h4></td>
  </tr>
  <tr>
    <td colspan="3"><h4>*** There are a limited number of slots available. Please check with the Honors Office about your assignment if you choose one of these activities.</h4></td>
  </tr>
  <?php break; ?>
  <?php endswitch; ?>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td colspan="3" align="center">
     <input type="submit" value="Submit From to the Honors Center">&nbsp;&nbsp;&nbsp;<input type="reset" value="Reset Form">
    </td>
  </tr>
</table>
</form>
</body>
</html>
<?php else: ?>
<?php
  $complete = "true";
  $vars = array("First_Name", "Last_Name", "Social_Security_Number", "Email_Address");
  while(list($key, $val) = each($vars))
    if(${$val} == "")
    {
      echo ereg_replace("_", " ", $val) . " is not set; this must be completed to continue.<br>\n";
      $complete = "false";
    }

  if($Social_Security_Number != "" && !ereg("([0-9]{3})-([0-9]{2})-([0-9]{4})", $Social_Security_Number))
  {
    echo "\"$Social_Security_Number\" is not a valid social security number; needs to be of the form XXX-XX-XXXX<br>\n";
    $complete = "false";
  }

  switch($_POST['type']) {
    case $form_type["Freshman"]:
      if($Work_Unit == "") {
        echo "<p>You must choose a work unit.</p>\n";
	$complete = "false";
      }
      if($Service_Unit[0] == "" && $Service_Unit[1] == "") {
        echo "<p>You must choose a service unit.</p>\n";
	$complete = "false";
      }
      if($Developmental_Unit == "") {
        while(list($key, $val) = each($Activity_Unit))
	  if($val != "")
	    $count++;

	if($count < 4) {
          echo "<p>You must choose a developmental unit or four activity units.</p>\n";
	  $complete = "false";
	}
      }
      break;
  }
  if($complete == "false")
    die();

  $name = "$First_Name " . ($Middle_Name != "" ? "$Middle_Name " : "") . $Last_Name;

  $htmlout = "<html>\n";
  $htmlout .= "<head>\n  <title>${name}'s " . $_POST['type'] . "</title>\n</head>\n";
  $htmlout .= "<body>\n";
  $htmlout .= "<font size=\"3\">\n";
  $htmlout .= "<center><h3>" . $_POST['type'] . " for $name for $semester</h3></center>\n";
  $htmlout .= "<ul>\n";
  $htmlout .= "<li>Social security number: $Social_Security_Number</li>\n";
  $htmlout .= "<li>E-mail address: " . (${Email_Address}) . "</li>\n";

  $textout = $_POST['type'] . " for $name for $semester\n\n";
  $textout .= " * Social security number: $Social_Security_Number\n";
  $textout .= " * E-mail address: $Email_Address\n";

  $vars = array("Work_Unit", "Service_Unit", "Developmental_Unit", "Activity_Unit");
  while(list($key, $val) = each($vars))
    if(isset(${$val})) {
      if(is_array(${$val})) {
        $printed = "false";
        while(list($key2, $val2) = each(${$val})) {
	  if($val2 != "") {
	    if($printed == "false") {
	      $htmlout .= "<li>" . ereg_replace("_", " ", $val) . "</li>\n";
	      $htmlout .= "<ul>\n";
	      $textout .= " * " . ereg_replace("_", " ", $val) . "\n";
	      $printed = "true";
	    }
	    $htmlout .= "<li>" . ereg_replace("_", " ", $val2) . "</li>\n";
	    $textout .= "   * " . ereg_replace("_", " ", $val2) . "\n";
	  }
	}
	if($printed == "true")
	  $htmlout .= "</ul>\n";
      } else {
        if(${$val} != "") {
	  $htmlout .= "<li>" . ereg_replace("_", " ", $val) . "</li>\n";
	  $htmlout .= "<ul><li>" . ereg_replace("_", " ", ${$val}) . "</li></ul>\n";
	  $textout .= " * " . ereg_replace("_", " ", $val) . "\n";
	  $textout .= "   * " . ereg_replace("_", " ", ${$val}) . "\n";
	}
      }
    }
  $htmlout .= "</ul>\n";
  echo $htmlout;
  echo "<hr>\n";
  echo "<p>A copy of this page has been mailed to ${Email_Address}</p>\n";
  echo "</body>\n";
  echo "</html>\n";
  $htmlout .= "</body>\n";
  $htmlout .= "</html>\n";
  mail($Admin_Email, $_POST['type'] . " -- $name", $htmlout, "From: Online HPEO System <hpeo@honors.tntech.edu>\r\nX-Mailer: PHP/" . phpversion() . "\r\nReturn-path: HPEO <hpeo@honors.tntech.edu>");
  mail($Email_Address, "$name's " . $_POST['type'], $textout, "From: Online HPEO System <hpeo@honors.tntech.edu>\r\nX-Mailer: PHP/" . phpversion() . "\r\nReturn-path: HPEO <hpeo@honors.tntech.edu>");
?>
<?php endif; ?>



