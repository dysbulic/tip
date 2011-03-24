<?php
$namespace_header = 'X-Accept-Namespace';
$fortune_namespace = 'http://himinbi.org/rest/fortune/1.0';
$rss_namespace = 'http://purl.org/rss/1.0/';

$mimeType = 'unknown/unknown';
$acceptedTypes = array('text/plain', 'text/xml', 'application/rss+xml',
                       'text/*', 'application/*', '*/*');

if(isset($_GET['mimeType'])) {
  $mimeType = $_GET['mimeType'];
} else {
  /* This is not correct. It does not use quality values.
   *  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html
   * This should be fleshed out later.
   */
  $minPos = strlen($_SERVER['HTTP_ACCEPT']);
  foreach($acceptedTypes as $type) {
    if(function_exists("stripos")) { // not defined in php < 5
      $pos = stripos($_SERVER['HTTP_ACCEPT'], $type);
    } else {
      $pos = strpos($_SERVER['HTTP_ACCEPT'], $type);
    }
    if(is_int($pos) && $pos < $minPos) {
      $minPos = $pos;
      $mimeType = $type;
    }
  }
}

$programs = array('/usr/bin/fortune', '/usr/games/fortune', '/opt/local/bin/fortune');
foreach($programs as $program) {
  exec($program, $fortune, $return);
  if($return == 0) break;
}
if($return != 0) {
  include('500 - internal server error.php');
  return;
}
$fortune = join("\n", $fortune);

/* IE caches GETs */
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");

if(function_exists("getallheaders")) { // not defined for IIS
  switch($mimeType) {
  case 'text/xml': case 'text/*': case '*/*':
    foreach(getallheaders() as $header => $value) {
      if($header == $namespace_header) {
        if($value == $fortune_namespace) {
          // default type for text/xml
        } elseif($value == $rss_namespace) {
          $mimeType = "application/rss+xml";
        } else {
          // $mimeType = "unknown/unknown";
        }
      }
    }
  }
}

switch($mimeType) {
 case 'text/xml': case 'text/*': case '*/*':
   $fortune = str_replace("--", "&mdash;", $fortune);   // wrong: should be hex code
   $fortune = str_replace("...", "&hellip;", $fortune); // wrong: should be hex code
   $fortune = str_replace("<", "&lt;", $fortune);
   $fortune = str_replace(">", "&gt;", $fortune);
   header('Content-type: text/xml');
   print "<fortune xmlns=\"$fortune_namespace\"><![CDATA[$fortune]]></fortune>";
   //print "<fortune xmlns=\"$fortune_namespace\"><![CDATA[" . $_SERVER['HTTP_ACCEPT'] . "]]></fortune>";
   break;
 case 'text/plain':
   header('Content-type: text/plain');
   print $fortune;
   //print $_SERVER['HTTP_ACCEPT'];
   break;
 case 'application/rss+xml': case 'application/*':
   header('Content-type: text/xml');
   print '<rss version="2.0" xmlns="' . $rss_namespace . '"><channel>' . "\n";
   print '<title>Fortune</title><link>http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '</link>' . "\n";
   print '<description>Fortune Feed from ' . $_SERVER['HTTP_HOST'] . '</description>' . "\n";
   print '<item>' . "\n";

   $timeFormat = "j F Y H:i:s";
   list($major, $minor, $edit) = split("[/.-]", phpversion());
   if($major >= 5 && $minor >= 1) {
     $timeFormat .= " e";
   } else {
     $timeFormat .= " O";
   }

   print '<title>Fortune for ' . $_SERVER['REMOTE_ADDR'] . ' - ' . date($timeFormat) . '</title>' . "\n";
   print '<link>http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '</link>' . "\n";
   print '<description><![CDATA[' . $fortune . ']]></description>' . "\n";
   print '</item>' . "\n";
   print '</channel></rss>';
   break;
 default:
  include('406 - not acceptable.php');
}
?>
