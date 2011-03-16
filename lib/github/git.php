<?php 
$url = 'http://github.com/wholcomb/' . $_GET['url'];
$fp = fopen($url, 'r');
$type = 'text/plain';
$meta = stream_get_meta_data($fp);
foreach($meta['wrapper_data'] as $header) {
  if(substr($header, 0, 13) == 'Content-Type:') {
    $type = substr($header, 14);
    break;
  }
}
header("Content-Type: $type");
fpassthru($fp);
?>