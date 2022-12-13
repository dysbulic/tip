<?php
/**
 * Takes a specially formatted HTML form and uses the form definition
 * to verify that the required fields have been filled in.
 */
header('Content-type: text/plain');

$basename = basename($_SERVER['HTTP_REFERER']);
$dom = new DomDocument("1.0","utf-8");
if(file_exists($basename)) {
  @$dom->load($basename);
} else if(file_exists(".templ.cache/" . $basename)) {
  @$dom->load(".templ.cache/" . $basename);
} else {
  die("Could not locate: " . $basename);
}
$xpath = new DOMXPath($dom);
$xpath->registerNamespace("html", "http://www.w3.org/1999/xhtml");
/*
$names = $xpath->query("//html:li[@class='required']/html:input/@name|//html:li[@class='required']/html:textarea/@name");
foreach($names as $name) {
  $name = $name->textContent;
  print $name . ': ' . $_POST[$name] . "\n";
}
*/
$names = $xpath->query("//html:li[@id='message']/html:blockquote/html:p");
$message = "";
foreach($names as $name) {
  $para = str_replace("\xE2\x80\x94", "--", $name->textContent); // replace em dash
  $message .= wordwrap($para, 70) . "\n\n";
}
if($_POST['message']) {
  $message .= wordwrap(trim($_POST['message']), 70) . "\n\n\n";
}
$message .= str_repeat(" ", 70 - strlen($_POST['name']) - 5) . $_POST['name'];

$to = str_replace("\n", "", $_POST['recipients']);
//$to = str_replace(" ", ",", $to);
$from = str_replace("\n", "", $_POST['sender']);

$eol = "\r\n";
$headers = "From: $from" . $eol;
$headers .= "Content-Type: text/plain; charset=UTF-8" . $eol;
$headers .= "Message-ID: <WebMailer@" . $_SERVER['SERVER_NAME'] . ">" . $eol;
$headers .= "X-Mailer: PHP v" . phpversion() . $eol;

ini_set("sendmail_from", $from);
mail($to, "Come visit the Michigan Coalition for Compassionate Care", $message, $headers);

header("Location: ./");

/*
$titles = $dom->getElementsByTagName("p");
foreach($titles as $node) {
   print $node->textContent . "\n";
}
*/
?>