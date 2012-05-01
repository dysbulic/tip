<?php
require('form_settings.php');

$message = "";
while(!is_null($name = key($_POST))) {
  $message .= "$name: " . $_POST[$name] . "\n";
  next($_POST);
}

$from = "web@" . $_SERVER['SERVER_NAME'];

mail($forms_recipient, "Application", $message, "From: $from");
header("Location: ./");
?>