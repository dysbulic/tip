<?php print '<?xml version="1.0" encoding="UTF-8" standalone="no" ?' . ">\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Loading Indiefeed Data</title>
    <link rel="stylesheet" type="text/css" href="../styles/main.css" />
    <style type="text/css">
      table { border-collapse: collapse; max-width: 100%; margin-bottom: 2em; }
      td, th { border: 1px solid; padding: .25em 8px; height: 30px; }
      th { font-size: 120%; }
      .missing, .nourl { background-color: #BFCFFF; }
      .missing a, .nourl a { color: black; }
      .image { position: relative; }
      img { height: 20px; position: absolute; top: -5px; }
      img:hover { height: 250px; margin-bottom: -230px; margin-left: -230px; z-index: 5; }
      body { max-idth: auto; }
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
    <script type="text/javascript" src="audio-player/audio-player.js"></script>
  </head>
  <body>
    <h1>Loading Indiefeed Data</h1>
<?php
function get_prop($node, $name) {
  $nodes = $node->getElementsByTagName($name);
  $val = undefined;
  if($nodes->length > 0 && $nodes->item(0)->childNodes->length > 0) {
    $val = $nodes->item(0)->childNodes->item(0)->data;
  }
  return $val;
}

$props = array('artist', 'title', 'album', 'label', 'show', 'graphic');
$fields = array();

function http_exists($uri) {
  $crlf = "\r\n";
  $url = parse_url($uri);
  if($url['host'] == '') { return false; }
  $req = ('HEAD ' . $url['path'] . ($url['query'] == '' ? '' : ('?' . $url['query'])) . ' HTTP/1.0' . $crlf .
          'Host: ' . $url['host'] . $crlf . $crlf);
  $url['port'] = $url['port'] == '' ? 80 : $url['port'];
  $fp = fsockopen($url['protocol'] == 'https' ? 'ssl://' : '' . $url['host'], $url['port']);
  fwrite($fp, $req);
  while($fp && is_resource($fp) && !feof($fp)) {
    $response .= fread($fp, 1024);
  }
  fclose($fp);
  $headers = array();
  $returnCode = undefined;
  foreach(explode($crlf, $response) as $line) {
    if(($pos = strpos($line, ':')) !== false) {
      $headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos + 1));
    } else if(substr($line, 0, 4) == "HTTP") {
      $returnCode = substr($line, 9, 3);
    }
  }
  if($returnCode == "302") {
    return http_exists($headers['location']);
  } else {
    $returnClass = substr($returnCode, 0, 1);
    return $returnClass == "2" || $returnClass == "3";
  }
}

function load_podcast($doc) {
  global $props;
  $nodes = $doc->getElementsByTagName('podcast');
  $channel = $doc->documentElement->getAttribute('channel');
  for($i = 0; $i < $nodes->length; $i++) {
    $podcast = $nodes->item($i);

    $propVals = array();
    $propVals['channel'] = $channel;
    foreach($props as $prop) {
      $propVals[$prop] = get_prop($podcast, $prop);
      switch($prop) {
      case 'artist':
      case 'label':
      case 'album':
        $propVals[$prop . '_url'] = get_prop($podcast, $prop . '_url');
        break;
      case 'show':
        //$propVals['exists'] = http_exists(get_prop($podcast, 'show'));
        break;
      }
    }
    print_proprow($propVals);
    insert_row($propVals);
  }
}

function print_proprow($propVals) {
  global $props;
  print '<tr>' . "\n";
  foreach($props as $prop) {
    $tag = undefined;
    $val = $propVals[$prop];
    if($val == undefined) { $val = ''; }
    switch($prop) {
    case 'show':
      if($propVals['exists'] == undefined) {
        $code = 'Not Tested';
      } elseif($propVals['exists']) {
        $code = 'HTTP 200';
      } else {
        $code = 'HTTP 404';
      }
      $tag = sprintf('<td class="%s"><a href="%s" title="%s">%s</a></td>',
                     $urlExists ? 'present' : 'missing', $val, $val, $code);
      break;
    case 'graphic':
      if($val != '') { $val = sprintf('<img class="disc" src="%s" alt="%s" />', $val, $val); }
      $tag = sprintf('<td class="%s"><div class="image">%s</div></td>', $val == '' ? 'missing' : 'present', $val);
      break;
    case 'artist':
    case 'label':
    case 'album':
      $url = $propVals[$prop . '_url'];
      if($url != undefined) {
        $val = sprintf('<a href="%s">%s</a>', $url, $val);
      }
      $tag = sprintf('<td class="%s">%s</td>', $url == undefined ? 'nourl' : 'hasurl', $val);
      break;
    default:
      $tag = sprintf('<td>%s</td>', $val);
      break;
    }
    print "   $tag\n";
  }
  print '</tr>' . "\n";
}

function insert_row($propVals) {
  global $props, $table_prefix;
  $propStr = '';
  $valStr = '';

  $result = mysql_query(sprintf('select id from %spodcasts where show_url = "%s"',
                                $table_prefix, mysql_real_escape_string($propVals['show'])));
  if(mysql_fetch_object($result) != FALSE) {
    printf('<tr><td colspan="%d">Skipping Existing Show: %s</td></tr>', count($props), $propVals['show']);
    mysql_free_result($result);
    return;
  }
  mysql_free_result($result);

  $artist_id = undefined;
  $result = mysql_query(sprintf('select id, url from %sartists where name = "%s"',
                                $table_prefix, mysql_real_escape_string($propVals['artist'])));
  if(($artist = mysql_fetch_object($result)) == FALSE) {
    $sql = sprintf('insert into %sartists (name, url) values ("%s", %s)',
                   $table_prefix,
                   mysql_real_escape_string($propVals['artist']),
                   $propVals['artist_url'] == undefined ? "NULL" : ('"' . mysql_real_escape_string($propVals['artist_url']) . '"'));
    //printf('<tr><td colspan="%d">%s</td></tr>', count($props), $sql);
    mysql_query($sql);
    $artist_id = mysql_insert_id();
    if(mysql_error() != '') printf('<tr><td colspan="%d">%s</td></tr>', count($props), mysql_error());
    else printf('<tr><td colspan="%d">Added Artist: %s</td></tr>', count($props), $propVals['artist']);
  } else {
    $artist_id = $artist->id;
    if($propVals['artist_url'] != undefined) {
      if($artist->url == NULL) {
        mysql_query(sprintf('update artists set url = "%s" where id = "%d"',
                            mysql_real_escape_string($propVals['artist_url']), mysql_real_escape_string($artist->id)));
        if(mysql_error() != '') printf('<tr><td colspan="%d">%s</td></tr>', count($props), mysql_error());
        else printf('<tr><td colspan="%d">Set Artist URL: %s</td></tr>', count($props), $propVals['artist_url']);
      } elseif($artist->url != $propVals['artist_url']) {
        printf('<tr><td colspan="%d">%s != %s</td></tr>', count($props), $artist->url, $propVals['artist_url']);
      }
    }
  }
  mysql_free_result($result);

  $label_id = undefined;
  $result = mysql_query(sprintf('select id, url from %slabels where name = "%s"',
                                $table_prefix, mysql_real_escape_string($propVals['label'])));
  if(($label = mysql_fetch_object($result)) == FALSE) {
    $sql = sprintf('insert into %slabels (name, url) values ("%s", %s)',
                   $table_prefix,
                   mysql_real_escape_string($propVals['label']),
                   $propVals['label_url'] == undefined ? "NULL" : ('"' . mysql_real_escape_string($propVals['label_url']) . '"'));
    //printf('<tr><td colspan="%d">%s</td></tr>', count($props), $sql);
    mysql_query($sql);
    $label_id = mysql_insert_id();
    if(mysql_error() != '') printf('<tr><td colspan="%d">%s</td></tr>', count($props), mysql_error());
    else printf('<tr><td colspan="%d">Added Label: %s</td></tr>', count($props), $propVals['label']);
  } else {
    $label_id = $label->id;
    if($propVals['label_url'] != undefined) {
      if($label->url == NULL) {
        mysql_query(sprintf('update labels set url = "%s" where id = "%d"',
                            mysql_real_escape_string($propVals['label_url']), mysql_real_escape_string($label->id)));
        if(mysql_error() != '') printf('<tr><td colspan="%d">%s</td></tr>', count($props), mysql_error());
        else printf('<tr><td colspan="%d">Set Label URL: %s</td></tr>', count($props), $propVals['label_url']);
      } elseif($label->url != $propVals['label_url']) {
        printf('<tr><td colspan="%d">%s != %s</td></tr>', count($props), $label->url, $propVals['label_url']);
      }
    }
  }
  mysql_free_result($result);

  $sql = sprintf('insert into %spodcasts (title, graphic, album, show_url, artist_id, label_id, description)' .
                 ' values ("%s", %s, "%s", "%s", %s, %s, %s)',
                 $table_prefix,
                 mysql_real_escape_string($propVals['title']),
                 $propVals['graphic'] == undefined ? 'NULL' : '"' . mysql_real_escape_string($propVals['graphic']) . '"',
                 mysql_real_escape_string($propVals['album']),
                 mysql_real_escape_string($propVals['show']),
                 $artist_id, $label_id,
                 $propVals['description'] == undefined ? 'NULL' : '"' . mysql_real_escape_string($propVals['description']) . '"');
  mysql_query($sql);
  if(mysql_error() != '') printf('<tr><td colspan="%d">%s</td></tr>', count($props), mysql_error());
  $podcast_id = mysql_insert_id();
  $sql = sprintf('insert into %spodcast_channel (podcast_id, channel_abbr) values ("%s", "%s")',
                 $table_prefix, mysql_real_escape_string($podcast_id), mysql_real_escape_string($propVals['channel']));
  mysql_query($sql);
  if(mysql_error() != '') printf('<tr><td colspan="%d">%s</td></tr>', count($props), mysql_error());
  //printf('<tr><td colspan="%d">%s</td></tr>', count($props), $sql);
  printf('<tr><td colspan="%d">Added Podcast: %s</td></tr>', count($props), $propVals['title']);
}
?>

<?php
require 'db_auth_info.php';
mysql_connect($host, $user, $password);
@mysql_select_db($database) or die('Unable to select database: ' . $database);

$dom = new DomDocument('1.0', 'utf-8');
@$dom->load('channels/channels.xml');
$channels = $dom->firstChild->getElementsByTagName('channel');
print '<ul>';
for($i = 0; $i < $channels->length; $i++) {
  $channel = $channels->item($i);
  $sql = sprintf('select name from %schannels where abbreviation = "%s"',
                 $table_prefix, mysql_real_escape_string($channel->getAttribute('abbr')));
  $result = mysql_query($sql);

  if(($channelRow = mysql_fetch_object($result)) != FALSE) {
    printf('<li>Skipping: %s : %s</li>' . "\n", $abbr, $channelRow->name);
  } else {
    $abbr = $channel->getAttribute('abbr');
    $title = $channel->getElementsByTagName('title')->item(0)->textContent;
    $description = "";
    printf('<li>Adding: %s : %s</li>' . "\n", $abbr, $title);
    $sql = sprintf('insert into %schannels (abbreviation, name, description) values ("%s", "%s", "%s")',
                   $table_prefix, mysql_real_escape_string($abbr),
                   mysql_real_escape_string($title), mysql_real_escape_string($description));
    print $sql;
    mysql_query($sql);
  }
  mysql_free_result($result);
}
print '</ul>';

$dir = opendir('channels');
$files = array();
while($filename = readdir($dir)) {
  array_push($files, $filename);
}
closedir($dir);
sort($files);
foreach($files as $filename) {
  if(ereg('indiefeed.*\\.xml$', $filename, $match)) {
    printf('<!-- Processing: %s -->' . "\n", $filename);
    $dom = new DomDocument("1.0","utf-8");
    @$dom->load('channels/' . $filename);
    $rootName = $dom->firstChild->nodeName;
    switch($rootName) {
    case 'podcasts':
      print '<table>' . "\n";
      printf('<tr><th colspan="%d"><a href="%s">%s</a></th></tr>' . "\n",
             count($props), $filename, $dom->firstChild->getAttribute('channel'));
      print '<tr>';
      foreach($props as $prop) {
        print '<th>' . ucfirst($prop) . '</th>';
      }
      print '</tr>' . "\n";
      print load_podcast($dom);
      print '</table>' . "\n";
      break;
    default:
    }
  }
}
mysql_close();
?>
  </body>
</html>
