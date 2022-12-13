<?php
if(count($_POST) > 0) {
  $message = "";
  while(!is_null($name = key($_POST))) {
    if(!is_array($_POST[$name])) {
      $message .= "$name: " . $_POST[$name] . "\n";
    } elseif(count($_POST[$name]) <= 1) {
      $message .= "$name: " . $_POST[$name][0] . "\n";
    } else {
      $message .= "$name:\n";
      for($i = 0; $i < count($_POST[$name]); $i++) {
        $message .= "\t" . $_POST[$name][$i] . "\n";
      }
    }    
    next($_POST);
  }
  mail("web@mpp.org,info@granitestaters.com", $_POST['subject'], $message, "From: info@granitestaters.com");
}
header("Location: " . $_POST['redirect']);
?>