<?

$to = "lowell@jcdanczak.com";
$subject = "testing";
$message = "testing mail";

echo "sending? " . mail($to,$subject,$message);

echo "<BR>message sent to " . $to;

?>
