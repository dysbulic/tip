<?php print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n"; ?>
<?php
require('songs_db_info.inc');
mysql_connect($host, $user, $password);
@mysql_select_db($database) or die("Unable to select database: $database");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Restructure Tracklist Database</title>
    <link rel="stylesheet" type="text/css" href="mixlists.css" />
    <style type="text/css">
    </style>
  </head>
  <body>
    <h1><span class="firstletter">M</span>ixlists</h1>
    <div id="content">
    <p>The <a href="database_schema.html">tracks list database</a> is an adapted form of the <a href="http://musicbrainz.com">MusicBrainz</a> database. MusicBrainz structure is a little odd. Each track has a single "artist" associated with it which is the artist as it would appear in an ID3 tag. This might be "Metric" for the track "Dead Disco" or it might be "Christina Aguilera, Lil&apos; Kim, Mya &amp; Pink" for the recent remake of Lady Marmalade.</p>

    <p>There are "<a href="http://musicbrainz.org/doc/AdvancedRelationships">advanced relationships</a>" that allow the association of tagged metadata relationships to both artists and tracks. Advanced relationships are used in two distinct ways so far as composite artists are concerned. (<em>I verified this understanding <a href="http://www.nabble.com/distinct-artist-names-tf2778151s2885.html">via e-mail</a>.</em>)</p>

    <ul>
      <li>Multiple artists collaborating with equal credit on a track. In this case the "artist" for the track is a composite name consisting of the different artists. That composite artist then has a "collaboration by" advanced relationship to each of the contributing artists.</li>
      <li>Multiple artists featured (not primary artists) on a track. Individual artist records have advanced relationships of "performer" or "instrument" or whatever to the track. The composite artist name for the featured artists is in a "(feat.)" addendum to the track name.</li>
    </ul>

      <p>The issue is a little complicated because the <a href="http://wiki.musicbrainz.org/CollaborationRelationshipType">definition of "collaboration"</a> includes not only composite artist names but also short-lived organizational names. For example, "<a href="http://musicbrainz.org/artist/87f27b14-cbd0-44ea-93a3-6fead5cb45e7.html">Bad Meets Evil</a>" which is the name that Eminem and Royce 5&apos;9&quot; used to produce four songs together, is considered a collaboration.</p>

    <p>That is all well and good except I want a list of all non-composite artists. With the existing data this requires dereferencing lots of cross-references and it is not possible to exclude non-composite collaborations. <?php print '<a href="' . $_SERVER['SCRIPT_NAME'] . 's">'?>This program<?php print '</a>' ?> creates a table with only non-composite artists.</p>

<?php
$query = ("CREATE TABLE IF NOT EXISTS atomic_track_artist\n" .
          "     (artist INT NOT NULL REFERENCES artist(id),\n" .
          "      track INT NOT NULL REFERENCES track(id),\n" .
          "      PRIMARY KEY(artist, track))");
print "<pre class='code'>$query</pre>\n";
///$result = mysql_query($query);
if(!$result) { print '' . mysql_error(); }
printf("Executed: %d\n", mysql_affected_rows());
?>

    <p>Theoretically all composite artists are listed as collaborations, so start the list with all artists that produced a track, but are not collaborators.</p>

<?php
// TODO: The relationship id needs to be looked up
$query = ("INSERT IGNORE INTO atomic_track_artist(artist, track)\n" .
          "    SELECT artist, id FROM track WHERE id NOT IN\n" .
          "    (SELECT subject FROM artist_artist WHERE rship = 11)");
print "<pre class='code'>$query</pre>\n";
//$result = mysql_query($query);
if(!$result) { print '' . mysql_error(); }
printf("Inserted Rows: %d\n", mysql_affected_rows());
?>

    <p>Any further cleaning to this list must be done by hand. There is an additional table which persists across imports to allow recleaning new data:</p>

<?php
$query = ("CREATE TABLE IF NOT EXISTS composite_artist\n" .
          "     (artist INT NOT NULL REFERENCES artist(id),\n" .
          "      guid char(36) NOT NULL REFERENCES artist(guid),\n" .
          "      is_composite BOOLEAN NOT NULL,\n" .
          "      confidence TINYINT UNSIGNED NOT NULL,\n" .
          "      PRIMARY KEY(guid))");
print "<pre class='code'>$query</pre>\n";
//$result = mysql_query($query);
if(!$result) { print '' . mysql_error(); }
printf("Executed: %d\n", mysql_affected_rows());
?>

    <p>Possible composite artists are stored here so that they don&apos;t have to be rechecked in future edits. The internal id could theoretically change between database versions, so the guid is stored and the ids can be regenerated from those. A guess is made as to whether an artist is composite or not, but the only way that the confidence score goes to 100 is with user intervention.</p>

    <p>Artists that have ampersands or the word "and" in their name and are not listed as collaborations are guessed to be non-composite with a score of 50. Only artists which have performed a track are considered. (Including a join to the tracks table to verify that that an artist produced a track increases the run time from two seconds to one that I forcefully exited without a solution after nine hours.)</p>

<?php
// TODO: The relationshp id needs to be looked up
$query = ("INSERT IGNORE INTO composite_artist\n" .
          "  (artist, guid, is_composite, confidence)\n" .
          "   SELECT id, guid, FALSE, 50 FROM artist WHERE id NOT IN\n" .
          "   (SELECT subject FROM artist_artist JOIN track\n" .
          "    ON subject = artist WHERE rship = 11)\n" .
          "   AND (name LIKE '% & %' OR name LIKE '% and %')");
print "<pre class='unused code'>$query</pre>\n";
$query = ("INSERT IGNORE INTO composite_artist\n" .
          "  (artist, guid, is_composite, confidence)\n" .
          "   SELECT id, guid, FALSE, 50 FROM artist WHERE id NOT IN\n" .
          "   (SELECT subject FROM artist_artist WHERE rship = 11)\n" .
          "   AND (name LIKE '% & %' OR name LIKE '% and %')");
print "<pre class='code'>$query</pre>\n";
//$result = mysql_query($query);
if(!$result) { print '' . mysql_error(); }
printf("Inserted Rows: %d\n", mysql_affected_rows());

$query = ("DELETE FROM composite_artist WHERE artist NOT IN\n" .
          "   (SELECT artist FROM track)");
print "<pre class='code'>$query</pre>\n";
$query = ("DELETE FROM composite_artist WHERE NOT EXISTS\n" .
          "   (SELECT track.artist FROM track\n" .
          "    WHERE track.artist = composite_artist.artist)");
print "<pre class='code'>$query</pre>\n";
?>

    <p>Collaborating artists that contain an ampersand or the word "and" are likely composites. They are entered with a positive confidence score of 50.</p>

<?php
// TODO: The relationshp id needs to be looked up
$query = ("INSERT IGNORE INTO composite_artist\n" .
          "  (artist, guid, is_composite, confidence)\n" .
          "   SELECT artist.id, guid, TRUE, 50 FROM artist_artist JOIN artist\n" .
          "   ON artist_artist.subject = artist.id WHERE rship = 11\n" .
          "    AND (name LIKE '% & %' OR name LIKE '% and %')");
print "<pre class='code'>$query</pre>\n";
//$result = mysql_query($query);
if(!$result) { print '' . mysql_error(); }
printf("Inserted Rows: %d\n", mysql_affected_rows());
?>

    <p>Artists who have &amp; or "and" in their name who are not collaborations should not be composite.</p>


<?php
// TODO: The relationshp id needs to be looked up
$query = ("INSERT IGNORE INTO composite_artist\n" .
          "  (artist, guid, is_composite, confidence)\n" .
          "   SELECT id, guid, FALSE, 50 FROM artist\n" .
          "   WHERE name LIKE '% & %' OR name LIKE '% and %'");
print "<pre class='code'>$query</pre>\n";
//$result = mysql_query($query);
if(!$result) { print '' . mysql_error(); }
printf("Inserted Rows: %d\n", mysql_affected_rows());
?>

<?php
$query = ("SELECT COUNT(DISTINCT artist.id) FROM atomic_track_artist JOIN artist\n" .
          "   ON atomic_track_artist.artist = artist.id\n" .
          "   WHERE name LIKE '%&%' or name like '% and %'");
print "<pre class='code'>$query</pre>\n";
//$result = mysql_query($query);
if(!$result) { print '' . mysql_error(); }
$row = mysql_fetch_array($result);
printf("Possible Composite Artists: %d\n", $row[0]);
?>

    <p>There is no way 

    <p>The basic algorithm is pretty simple. It takes all entries which have an ampersand (&amp;) and splits them at commas or ampersands in the name, so "Christina Aguilera, Lil&apos; Kim, Mya &amp; Pink" becomes "Christina Aguilera", "Lil' Kim", "Mya" and "Pink". It then checks if those individual artists are present individually in the database. There are three possibilities:</p>

    <ol>
      <li><strong>Each artist present once:</strong> In this situation, the original record is removed and individual records are added for the separate artists. <em>There is a data loss here as the original ordering of the artists is lost.</em></li>
      <li><strong>Artists appear more than once:</strong> Since out of 300,000 artists there are bound to be some with the same name, user intervention is required to pick the appropriate artist.</li>
      <li><strong>Artists aren&apos;t present:</strong> There are some band names that include an ampersand, like "Adolph Hofner &amp; His Orchestra." <em>There are times that bands like this will be accidentally spilt by rule #1 and they need to be reperable.</em> User intervention is needed to know whether a new artist should be added or if the name needs to be combined.</li>
    </ol>

<?php
$query = "INSERT into SELECT id, name FROM atomic_track_artist join artist ON atomic_track_artist WHERE name LIKE '% & %' ORDER BY name";
//$result = mysql_query($query);
if(!$result) { print '' . mysql_error(); }
?>

<ul>
<?php

while($row = mysql_fetch_array($result)) {
  $orig = $row[1];
  $artists = preg_split("/ *[,&] */", $row[1]);
  print "<li>$row[1]:<ul>";
  foreach($artists as $artist) {
    $query = sprintf("SELECT count(*) FROM artist WHERE name LIKE '%s'", mysql_real_escape_string($artist));
    $artistresult = mysql_query($query);
    $artistrow = mysql_fetch_array($artistresult);
    print "<li>$artistrow[0]: $artist</li>\n";
  }
  print "</ul></li>\n";
}
?>
</ul>

<?php
mysql_close();
?>

    <p>The composite artists then have to be <a href="check_distinct_artists.html">hand-checked</a>.</p>

    </div>
  </body>
</html>
