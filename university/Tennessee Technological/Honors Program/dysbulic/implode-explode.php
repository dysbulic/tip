<?php
header("Content-type: text/plain");

$a = explode("test.123.456",".");

for ($i=0; $i<count($a); $i++) {
  echo $a[$i]."\n";
}

$a = split("!", "test!123!456!789", 3);
for ($i=0; $i<count($a); $i++) {
  echo $a[$i]."\n";
}

$str = implode($a,":");

echo "$str\n";
?>