<?php print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Tracklist Database Statistics</title>
    <link rel="stylesheet" type="text/css" href="mixlists.css" />
    <style type="text/css">
    </style>
  </head>
  <body>
    <h1><span class="firstletter">M</span>ixlists</h1>
    <div id="content">
    <p></p>

<?php
require('songs_db_info.inc');
mysql_connect($host, $user, $password);
@mysql_select_db($database) or die("Unable to select database: $database");
?>

<h2>Sizes</h2>
<table>
  <tr><th>table</th><th>count</th></tr>
<?php
$result = mysql_query("show tables");
while($row = mysql_fetch_array($result, MYSQL_NUM)) {
  $query = "SELECT '$row[0]', count(*) as count FROM $row[0]";
  $tableresult = mysql_query($query);
  if(!$tableresult) { die('Invalid query: ' . mysql_error()); }
  while($row = mysql_fetch_array($tableresult, MYSQL_NUM)) {
    print '<tr>';
    foreach($row as $col) { print "<td>$col</td>"; }
    print "</tr>\n";
  }
}
?>
</table>

<h2>Artist to Artist Relationships</h2>
<?php
$query = "SELECT artist_artist_rship_type.id AS id, name, count(*) as count FROM artist_artist JOIN artist_artist_rship_type ON artist_artist.rship = artist_artist_rship_type.id GROUP BY artist_artist.rship order by id";
$result = mysql_query($query);
if(!$result) { die('Invalid query: ' . mysql_error()); }
?>
<h2><?php print $query ?></h2>
<table>
  <tr><th>id</th><th>name</th><th>count</th></tr>
<?php
while($row = mysql_fetch_array($result, MYSQL_NUM)) {
  print '<tr>';
  foreach($row as $col) { print "<td>$col</td>"; }
  print "</tr>\n";
}
?>
</table>

<h2>Artist to Track Relationships</h2>
<?php
$query = "SELECT count(*) from track_artist join track on track_artist.track = track.id join artist on track.artist = artist.id where rship = 2 and artist.name like '%&%' limit 10";
$result = mysql_query($query);
if(!$result) { die('Invalid query: ' . mysql_error()); }
?>
<h2><?php print $query ?></h2>
<table>
  <tr><th>id</th><th>name</th><th>count</th></tr>
<?php
while($row = mysql_fetch_array($result, MYSQL_NUM)) {
  print '<tr>';
  foreach($row as $col) { print "<td>$col</td>"; }
  print "</tr>\n";
}
?>
</table>

    </div>
  </body>
</html>
