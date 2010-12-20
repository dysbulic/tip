<?php print("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>PHP Global Variables List</title>
    <style type="text/css">
      body * {
        display: inline;
      }
      body li, body ul {
        display: block;
      }
    </style>
    <script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
    <script type="text/javascript">
      _uacct = "UA-939849-1";
      urchinTracker();
    </script>
  </head>
  <body>
<?php
$_SERVER;
$used = array();
function printTree($id, $root) {
  global $used;
  print  "<ul>\n";
  while(!is_null($name = key($root))) {
    print("<li>\$$id" . "[$name]");
    $array = current($root);
    if(is_array($array)) {
      print(": [" . count($array) . "]\n");
      if(!isset($used[$name]) && count($array) > 0) {
        $used[$id] = true;
        printTree($name, $array);
      }
    } else {
      print(" = $array");
    }
    print("</li>\n");
    next($root);
  }
  print("</ul>\n");
}
printTree("GLOBALS", $GLOBALS);
?>
  </body>
</html>
