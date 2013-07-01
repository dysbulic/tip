<?php
$admin = "will@himinbi.org";

$vol = "<volunteer sector=\"" . $_POST[sector] . "\">\n";
$vol .= "  <xnl:PersonName>\n";
if($_POST[first_name]) { $vol .= "    <xnl:FirstName>$_POST[first_name]</xnl:FirstName>\n"; }
if($_POST[middle_name]) { $vol .= "    <xnl:MiddleName>$_POST[middle_name]</xnl:MiddleName>\n"; }
if($_POST[last_name]) { $vol .= "    <xnl:LastName>$_POST[last_name]</xnl:LastName>\n"; }
$vol .= "  </xnl:PersonName>\n";

if($_POST[email]) {
  $vol .= "  <cil:EmailAddresses>\n";
  $vol .= "    <cil:EmailAddress>$_POST[email]</cil:EmailAddress>\n";
  $vol .= "  </cil:EmailAddresses>\n";
}

if($_POST[hometown]) { $vol .= "  <home>$_POST[hometown]</home>\n"; }

$vol .= ("  <cil:BirthDate><cil:Date>" .
         sprintf("%04d", $_POST[birthday_year]) . '-' .
         sprintf("%02d", $_POST[birthday_month]) . '-' .
         sprintf("%02d", $_POST[birthday_day]) .
         "</cil:Date></cil:BirthDate>\n");

if($_POST[education_1] || $_POST[education_2]) {
  $vol .= "  <education>\n";
  $vol .= "    <award school=\"\">\n";
  $vol .= "      <degree rank=\"B.S.\" subject=\"\"/>\n";
  $vol .= "      <!--\n";
  if($_POST[education_1]) { $vol .= "    $_POST[education_1]\n"; }
  if($_POST[education_2]) { $vol .= "    $_POST[education_2]\n"; }
  $vol .= "       -->\n";
  $vol .= "      </degree>\n";
  $vol .= "    </award>\n";
  $vol .= "  </education>\n";
}

if($_POST[homepage]) {   $vol .= "  <url type=\"homepage\" href=\"$_POST[homepage]\" />\n"; }
if($_POST[blog]) {   $vol .= "  <url type=\"blog\" href=\"$_POST[blog]\" />\n"; }
if($_POST[photos]) {   $vol .= "  <url type=\"photos\" href=\"$_POST[photos]\" />\n"; }

$vol .= "</volunteer>";

mail($admin, "Roster Update Request: $_POST[first_name] $_POST[last_name]", $vol,
     ("From: " . ($_POST[email] ? $_POST[email] : $admin) . "\r\n" .
      "Reply-To: $admin\r\n" .
      "X-Mailer: PHP/" . phpversion()),
     "-f$admin");
//header("Location: http://mr.pcvs.org");
?>
<?php print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Thanks for the Info</title>
    <link rel="stylesheet" type="text/css" href="http://mr.pcvs.org/styles/table.css" />
    <link rel="stylesheet" type="text/css" href="http://mr.pcvs.org/styles/page.css" />
  </head>
  <body>
    <h1>Thank You</h1>
    <p>Thank you for the info. If you are a new addition give me a couple days and I'll
     get a password out to you. Things are a bit slow here at times. In the interim there
     is probably interesting stuff you haven't seen on the <a href="/">homepage</a>.</p>
  </body>
</html>