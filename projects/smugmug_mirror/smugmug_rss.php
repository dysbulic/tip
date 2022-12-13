<?php
if(!$_GET[site]) {
  $mimetype = "text/html";
} else {
  $mimetype = "text/xml";
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
        <div><input type="submit" value="Get RSS" /></div>
      </form>
    </div>
  </body>
</html>

<?php else:
/* There are three things a user can request:
 *  1. The title page: print categories (getCategories)
 *  2. A category: print subcategories (getSubCategories) or print
 *      contained albums (getAlbums) if there are no subcategories
 *  3. An album: print contained images (getImages)
 */

$GLOBALS[gallery][galleries] = array();

$GLOBALS[gallery][title] = $data;
$GLOBALS[gallery][description] = $data;
$GLOBALS[gallery][thumbnail];
$GLOBALS[current_entry][update] =
array_push($GLOBALS[gallery][galleries], $GLOBALS[current_entry]);
$GLOBALS[current_entry][title] = $data;
$GLOBALS[current_entry][description] =
$GLOBALS[current_entry][thumbnail] = $attrs[src];
$GLOBALS[current_entry][description] = $data;

if(isset($_GET[debug])) {
  print "<!-- Getting $_GET[site]\n";
}

include("xmlrpc-1.0.99.2/xmlrpc.inc");
 
print "Logging in\n";

$rpclient = new xmlrpc_client("/xmlrpc/", "upload.smugmug.com", 80);
$rpclient->setDebug(1);
$result = $rpclient->send(new xmlrpcmsg('loginAnonymously',
                                        array(new xmlrpcval("1.0", "string"))));
if($result == 0) {
  print "Login failed: (" . $rpclient->errno . ") \"" . $rpclient->errstring . "\"\n";
} elseif($result->faultCode() != 0) {
  print "Login failed: (" . $result->faultCode() . ") \"" . $result->faultString() . "\"\n";
} else {
  // PHP4 doesn't allow chained dereferencing of returned objects
  $result = $result->value();
  $result = $result->structmem("SessionID");
  $sessionid = $result->scalarval();
  print "Login suceeded with session id: " . $sessionid . "\n";

  $vars = new xmlrpcval(array(SessionID => new xmlrpcval($sessionid, "string"),
                              NickName => new xmlrpcval($_GET[site], "string")),
                        "struct");
  $vars = array(new xmlrpcval($sessionid, "string"),
                new xmlrpcval($_GET[site], "string"));
  $result = $rpclient->send(new xmlrpcmsg("getCategories", $vars));
  if($result == 0) {
    print "GetCategories failed: (" . $rpclient->errno . ") \"" . $rpclient->errstring . "\"\n";
  } elseif($result->faultCode() != 0) {
    print "GetCategories failed: (" . $result->faultCode() . ") \"" . $result->faultString() . "\"\n";
  } else {
    $result = $result->value();
    for($index = 0; $index < $result->arraysize(); $i++) {
      $category = $result->arraymem($i);
      $catid = $category->structmem("CategoryID");
      $cattitle = $category->structmem("Title");
      print "Category: " . $catid->scalarval() . ": \"" . $cattitle->scalarval() . "\"\n";
    }
  }

  $result = $rpclient->send(new xmlrpcmsg('logout',
                                          array(new xmlrpcval($sessionid, "string"))));
  if($result == 0) {
    print "Logout failed: (" . $rpclient->errno . ") \"" . $rpclient->errstring . "\"\n";
  } elseif($result->faultCode() != 0) {
    print "Logout failed: (" . $result->faultCode() . ") \"" . $result->faultString() . "\"\n";
  } else {
  }
}

if(isset($_GET[debug])) {
  print "-->\n";
}
?>
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
