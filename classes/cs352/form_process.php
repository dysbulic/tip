<?php
session_start();

$message = "";
while(!is_null($name = key($_POST))) {
  $message .= "$name: " . $_POST[$name] . "\n";
  next($_POST);
}
$myFile = 'ques/' . $_SESSION['uid'] . ' - ' . $_POST['form_name'] . ' - ' . date('Y_m_d H_i_s') . '.txt';
$fh = fopen($myFile, 'w') or die("can't open: " . $myFile);
fwrite($fh, $message);
fclose($fh);

$_SESSION[$_POST['form_name'] . ' done'] = true;
header('Location: form_list.php');
?>
