<?php
$test = "This is a test";
function print_test() {
  $test = "This is a local test";
  print $test . "\n";
  global $test;
  print $test . "\n";
}
print_test();
?>