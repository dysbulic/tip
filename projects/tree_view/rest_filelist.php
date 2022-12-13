<?php
$namespace_header = 'X-Accept-Namespace';
$filelist_namespace = 'http://himinbi.org/rest/filelist/1.0';
$file_prefix = "../";

$skipPatterns = array("/^\./", "/~$/");

$mimeType = 'unknown/unknown';
$acceptedTypes = array('text/xml', 'text/*', '*/*');

if(!isset($_GET['path'])) {
  include('400 - missing parameter.php');
  return;
}

if(preg_match("'(^../)|(/../)|(/..$)'", $_GET['path'])) {
  include('403 - forbidden.php');
  return;
}

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

/* IE caches GETs */
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");

if(function_exists("getallheaders")) { // not defined for IIS
  switch($mimeType) {
  case 'text/xml': case 'text/*': case '*/*':
    foreach(getallheaders() as $header => $value) {
      if($header == $namespace_header) {
        if($value == $filelist_namespace) {
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
   header('Content-Type: text/xml');
   print "<filelist xmlns=\"$filelist_namespace\" path=\"" . $_GET['path'] . "\">\n";
   $fullpath = $file_prefix . $_GET['path'];
   if(is_dir($fullpath)) {
     $dir = opendir($fullpath);
     while($file = readdir($dir)) {
       $send = true;
       for($i = 0; $i < count($skipPatterns) && $send; $i++) {
         $send = !preg_match($skipPatterns[$i], $file);
       }
       if($send) {
         $tag = is_dir($fullpath . '/' . $file) ? 'directory' : 'file';
         print "<$tag><![CDATA[$file]]></$tag>";
       }
     }
     closedir($dir);
   } else if(is_file($fullpath)) {
     print '<file><![CDATA[' . $_GET['path'] . ']]>';
   } else {
     // Can't send an error here since content has already been sent
   }
   print "</filelist>";
   break;
 default:
  include('406 - not acceptable.php');
}
?>
