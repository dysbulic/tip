<?php
function bb2html($text) { 
  $bbcode_regex = '/\[((strong|cite|em|acronym)(?: title="[^"]*")?)\](.*)\[\/\2\]/';
  $text = preg_replace('/&quot;/', '"', $text);
  return preg_replace($bbcode_regex, '<$1>$3</$2>', $text);
}

function bb_strip($text) {
  $bbcode_regex = '/\[((strong|cite|em|acronym)(?: title="[^"]*")?)\](.*)\[\/\2\]/';
  $text = preg_replace('/&quot;/', '"', $text);
  return preg_replace($bbcode_regex, '$3', $text);
}
?>
