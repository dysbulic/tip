<?php
$GLOBALS[url] = "http://$_GET[site].smugmug.com";
if(!$_GET[site]) {
  $mimetype = "text/html";
} else {
  if(is_file("$_GET[site].smugmug.com.html")) {
    $site = fopen("$_GET[site].smugmug.com.html", "r");
  }
  if(!$site) {
    @ $site = fopen($GLOBALS[url], "r");
  }
  if($site) {
  //if($site = fopen($GLOBALS[url], "r")) {
    if(isset($_GET[dump])) {
      $mimetype = "text/plain";
    } else {
      $mimetype = "text/xml";
    }
  } else {
    $mimetype = "text/html";
    $error = TRUE;
    header("HTTP/1.1 400 Bad Request");
  }
  # $mimetype = "application/xhtml+xml";
}

header("Content-Type: $mimetype;charset=UTF-8");
header("Vary: Accept");

if($mimetype == "text/xml" || $mimetype == "text/html") {
  print "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
}
?>

<?php if($mimetype == "text/html"): ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>RSS Feed for a Smugmug Site</title>
    <link rel="stylesheet" href="../styles/main.css" type="text/css" />
  </head>
  <body>
    <div style="text-align: center;">
      <p>This page provides
      <a href="http://blogs.law.harvard.edu/tech/rss">RSS</a> feeds for
      <a href="http://www.smugmug.com">smugmug</a> sites.</p>
      <?php if($error): ?>
        <h1 style="border: solid black; color: red">Error: <?php print $GLOBALS[url] ?> is not valid</h1>
      <?php endif; ?>
      <form method="get">
        <div>http://<input type="input" name="site"
                  >.smugmug.com/<input type="input" name="gallery"
                  ></div>
        <div><input type="checkbox" name="debug" />Include Debugging Comments</div>
        <div><input type="checkbox" name="dump" />Dump Site Format</div>
        <div><input type="submit" value="Get RSS" /></div>
      </form>
    </div>
  </body>
</html>

<?php else:

if($mimetype == "text/plain") {
  function startElement($parser, $name, $attrs) {
    $GLOBALS[element_count][$name]++;
    $GLOBALS[current_element] = $name;
    $GLOBALS[current_element_count] = $GLOBALS[element_count][$name];
    $GLOBALS[depth]++;
    printf("%" . (($GLOBALS[depth] - 1) * 2) . "sStarted: %s (#%d) (>%d) \n",
           "", $name, $GLOBALS[element_count][$name], $GLOBALS[depth]);
    foreach($attrs as $attr => $val) {
      printf("%" . (($GLOBALS[depth] - 1) * 2 + 1) . "s%s = \"%s\"\n",
             "", $attr, $val);
    }
  }

  function endElement($parser, $name) {
    $GLOBALS[depth]--;
  }

  function characterData($parser, $data) {
    if(!preg_match("/^\s*$/", $data)) {
      $data = preg_replace("/^\s+/", "", $data);
      $data = preg_replace("/\s+$/", "", $data);
      $data = preg_replace("/\s\s+/", " ", $data);
      printf("%" . (($GLOBALS[depth] + 1) * 2) . "sData: \"%s\"\n",
             "", $data);
    }
  }
} else {
  $GLOBALS[gallery][galleries] = array();
  $GLOBALS[elements] = array();

  /* State machine used in the handling of the SAX events.
   * For more info, see:
   *  http://odin.himinbi.org/smugmug_mirror/
   */
  $GLOBALS[transitions][start] = array(array(tag => "table", depth => 1, to => "header"));
  $GLOBALS[transitions][header] = array(array(tag => "table", depth => 1, to => "title"));
  $GLOBALS[transitions][title] = array(array(tag => "table", depth => 1, to => "first table"));
  $GLOBALS[transitions]["first table"] = array(array(tag => "img", to => "gallery header"),
                                             array(tag => "table", depth => 1, to => "data table"));
  $GLOBALS[transitions]["gallery header"] = array(array(tag => "td", to => "gallery description"));
  $GLOBALS[transitions]["gallery description"] = array(array(tag => "table", depth => 1, to => "data table"));
  $GLOBALS[transitions]["data table"] = array(array(tag => "table", to => "data description"));
  $GLOBALS[transitions]["data description"] = array(array(tag => "a", to => "entry thumbnail"),
                                                    array(tag => "table", depth => 1, to => "data table"));
  $GLOBALS[transitions]["entry thumbnail"] = array(array(tag => "a", to => "entry link"));
  $GLOBALS[transitions]["entry link"] = array(array(tag => "td", to => "entry description"));
  $GLOBALS[transitions]["entry description"] = array(array(tag => "td", to => "entry timestamp"));
  $GLOBALS[transitions]["entry timestamp"] = array(array(tag => "table", depth => 1, to => "data table"),
                                                   array(tag => "a", to => "entry thumbnail"));
  
  $GLOBALS[current_state] = "start";

  function startElement($parser, $name, $attrs) {
    array_push($GLOBALS[elements], $name);
    $GLOBALS[element_depth][$name]++;
    /* Anchors used for relative linking (nondisplaying #something)
     *  are ignored by the state machine. This makes it easier to
     *  operate only on material that changes the onscreen appearance
     * By the same token, transparent spacing gifs are skipped as
     *  well
     */
    if(!($name == "a" && !isset($attrs[href]))
       && !($name == "img" && preg_match("/spacer.gif$/", $attrs[src]))) {
      foreach($GLOBALS[transitions]["$GLOBALS[current_state]"] as $state) {
        if($state[tag] == $name && (!isset($state[depth]) || $state[depth] == $GLOBALS[element_depth][$name])) {
          if(isset($_GET[debug])) {
            print "Gathered: $GLOBALS[current_state]: \"$GLOBALS[data_buffer]\"";
            if($name == "img") {
              print " (src=$attrs[src])";
            } elseif($name == "a") {
              print " (href=$attrs[href])";
            }
            print "\n";
            if(count($GLOBALS[transitions]["$GLOBALS[current_state]"]) > 1) {
              print ("  Transitioning to: $state[to]\n");
            }
          }
          $transitioned = TRUE;
          $fromstate = $GLOBALS[current_state];
          $GLOBALS[current_state] = $state[to];
          $data = $GLOBALS[data_buffer];
          $GLOBALS[data_buffer] = "";
        }
      }
    } else {
      $transitioned = FALSE;
    }
    if($transitioned) {
      if($fromstate == "title") {
        $GLOBALS[gallery][title] = $data;
      } elseif($fromstate == "entry timestamp") {
        // Ex: Updated: Dec 21, 2004 7:48am PST
        $GLOBALS[current_entry][update] =
          preg_replace("/\s*Updated:\s+/", "", $data);
        array_push($GLOBALS[gallery][galleries], $GLOBALS[current_entry]);
        unset($GLOBALS[current_entry]);
      } elseif($fromstate == "entry link") {
        $GLOBALS[current_entry][title] = $data;
        $GLOBALS[current_entry][description] =
          preg_replace("/" . preg_quote($data, "/") . " :\s+/", "", $GLOBALS[current_entry][description]);
      } elseif($fromstate == "gallery description") {
        $GLOBALS[gallery][description] = $data;
      }
      if($GLOBALS[current_state] == "entry thumbnail") {
        assert($name == "a");
        assert(!isset($GLOBALS[current_entry]));
        if(preg_replace("/\/([^\/]+)\/.*/", "\$1", $attrs[href]) == "keyword") {
          $GLOBALS[current_state] = "data table";
          if(isset($GLOBALS[debug])) {
            print "Short circuit to \"$GLOBALS[current_state]\" to avoid processing keyword tables\n";
          }
        } else {
          $url = $attrs[href];
          if(!preg_match("'://'", $url)) {
            $url = $GLOBALS[url] . $url;
          }
          $GLOBALS[current_entry] = array(link => $url);
        }
      } elseif($GLOBALS[current_state] == "gallery header") {
        assert($name == "img");
        $GLOBALS[gallery][thumbnail] = $GLOBALS[url] . $attrs[src];
      }
    } elseif($GLOBALS[current_state] == "entry thumbnail" && $name == "img") {
      $GLOBALS[current_entry][thumbnail] = $attrs[src];
      $GLOBALS[current_entry][description] = $data;
    }
  }

  function endElement($parser, $name) {
    assert(array_pop($GLOBALS[elements]) == $name);
    $GLOBALS[element_depth][$name]--;
  }

  function characterData($parser, $data) {
    // Normal whitespace doesn't include &nbsp;
    $data = preg_replace("/" . chr(0xA0) . "+/", " ", $data);
    if(!preg_match("/^\s*$/", $data)) {
      $data = preg_replace("/^\s+/", "", $data);
      $data = preg_replace("/\s+$/", "", $data);
      $data = preg_replace("/\s\s+/", " ", $data);
      if($GLOBALS[data_buffer] != "") {
        $GLOBALS[data_buffer] .= " ";
      }
      $GLOBALS[data_buffer] .= $data;
    }
  }
}

/* When the files come from smugmug they are in HTML 4. This is
 *  not parseable by the standard xml parser, so it is necessary
 *  to save it to disk and run it though the xmllint processor
 *  from the libxml project.
 */

$xmlfile = tempnam("/tmp", "$_GET[site].smugmug.");
$xmlhandle = fopen($xmlfile, "w");
while(!feof($site) && ($data = fread($site, 4096))) {
  fwrite($xmlhandle, $data);
}
fclose($xmlhandle);
fclose($site);

$descriptorspec = array(1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
                        2 => array("pipe", "w")); // stderr is a pipe that the child will write to
$process = proc_open("/usr/bin/xmllint --html $xmlfile", $descriptorspec, $xmlpipes);
if(!is_resource($process)) {
  die("Process is not a resource\n");
} else {
#  stream_set_blocking($xmlpipes[1], FALSE);
  stream_set_blocking($xmlpipes[2], FALSE);
}

if(isset($_GET[debug])) {
  print "<!-- Output from xmllint:\n";
  while(!feof($xmlpipes[2]) && ($line = fgets($xmlpipes[2]))) {
    print $line;
  }
  print "-->\n";
}
fclose($xmlpipes[2]);

$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "characterData");
xml_parser_set_option ($xml_parser, XML_OPTION_CASE_FOLDING, FALSE);

if(isset($_GET[debug])) {
  print "<!-- Running parse:\n";
}

while(($data = fread($xmlpipes[1], 4096))) {
  if(!xml_parse($xml_parser, $data, feof($xmlpipes[1]))) {
    die(sprintf("<!-- XML error at line %d column %d -->\n",
                xml_get_current_line_number($xml_parser),
                xml_get_current_column_number($xml_parser)));
  }
}

if(isset($_GET[debug])) {
  print "-->\n";
}
 
xml_parser_free($xml_parser);
fclose($xmlpipes[1]);
$return_value = proc_close($process);
if($return_value != 0) {
  print "<!-- xmllint returned $return_value -->\n";
} else {
  unlink($xmlfile);
}
?>
<?php if($mimetype == "text/xml"): ?>
<rss version="2.0">
  <channel>
    <title><?php print $GLOBALS[gallery][title] ?></title>
    <link><?php print $GLOBALS[url] ?></link>
    <description><?php print $GLOBALS[gallery][description] ?></description>
    <language>en-us</language>
<?php if($GLOBALS[gallery][thumbnail]): ?>
    <image>
      <title><?php print $GLOBALS[gallery][title] ?></title>
      <url><?php print $GLOBALS[gallery][thumbnail] ?></url>
      <description><?php print $GLOBALS[gallery][description] ?></description>
    </image>
<?php endif; ?>
    <generator>Smugmug RSS Generator</generator>
<?php
foreach($GLOBALS[gallery][galleries] as $gallery) {
?>
    <item>
      <title><?php print $gallery[title] ?></title>
      <link><?php print $gallery[link] ?></link>
<?php if($gallery[description]): ?>
      <description><?php print $gallery[description] ?></description>
<?php endif; ?>
<?php if(isset($gallery[update])): ?>
      <pubDate><?php print $gallery[update] ?></pubDate>
<?php endif; ?>
    </item>
<?php } ?>
  </channel>
</rss>
<?php endif; ?>

<?php endif; ?>
