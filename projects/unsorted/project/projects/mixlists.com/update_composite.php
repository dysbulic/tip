<?php
$_GET['artistmbid'];
$_GET['iscomposite'];

header('Content-Type: text/xml');

require('songs_db_info.inc');
mysql_connect($host, $user, $password);
@mysql_select_db($database) or print("<!-- Unable to select database: $database -->");

$query = ("SELECT count(*) from composite_artist");
$result = mysql_query($query);
if(!$result) { print '' . mysql_error(); }
$row = mysql_fetch_array($result);
$total = $row[0];

$query = ("SELECT count(*) from composite_artist WHERE confidence <> 100");
$result = mysql_query($query);
if(!$result) { print '' . mysql_error(); }
$row = mysql_fetch_array($result);
$count = $row[0];
?>
<artistlist inquestion="<?php print $count ?>" total="<?php print $total ?>" type="<?php print urlencode($_GET['type']) ?>">
<?php
$result = mysql_query($mainquery);
if(!$result) {
  print "  <error>" . mysql_error() . "</error>\n";
} else {
  while($row = mysql_fetch_array($result)) {
    print " <artist guid='$row[0]' confidence='$row[1]' iscomposite='true'>\n";
    print "   <name>" . htmlspecialchars($row[2]) . "</name>\n";
    print "   <breakdown>\n";
    $parts = preg_split("/ *(,| & | and ) */", $row[2]);
    foreach($parts as $part) {
      $query = sprintf("SELECT count(*) from artist where name = '%s'", mysql_real_escape_string($part));
      $artresult = mysql_query($query);
      $artrow = mysql_fetch_array($artresult);
      print "    <artist count='$artrow[0]'><name>" . htmlspecialchars($part) . "</name></artist>\n";
    }
    print "   </breakdown>\n";
    print " </artist>\n";
  }
}
?>
