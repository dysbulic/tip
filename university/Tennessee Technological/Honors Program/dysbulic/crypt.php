<?php
header("Content-type: text/plain");
echo "Testing the crypt() function...\n";
if(!function_exists("crypt")) {
  echo "Crypt not defined";
} else {
  $seed = "az";
  $string = "gazonk";
  
  echo ('Without specifying a seed: ' . crypt($string) . "\n");
  echo ('Specifying a seed: ' . ($crypt2 = crypt($string, $seed)) . "\n");
  echo ('Decrypting: ' . crypt($crypt2, $seed) . "\n");
}
?>
