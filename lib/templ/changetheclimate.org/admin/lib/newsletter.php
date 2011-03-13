<?

function validate_newsletter(){

	global $to, $subject, $message, $error_message;
	require_once("valid_email.php");

	$subject = trim($subject);
	$message = trim($message);
	$to = trim($to);

	if($subject == "")
		$error_message = "<li>You must enter a subject for the newsletter.</li>";
	
	if($message == "")
		$error_message .= "<li>You must enter a message for the newsletter.</li>";

	if($to == "")
		$error_message .= "<li>You must enter an email address to send the newsletter to.</li>";

	if(!check_email($to))
		$error_message .= "<li>The email address you entered in the 'to' field is not a valid email address.</li>";
	
	if($error_message != "")
		$error_message = "<ul class='formerror'>$error_message</ul>";
	
	else
		$error_message = "";
		

	return $error_message;

}


function display_newsletter(){

	global $to, $subject, $message, $form_submitted, $error_message;

	$header = "Send a Newsletter";
	$text = "To send out the newsletter to the entire list, send the newsletter to 'list@changetheclimate.org'.  To send a test newsletter, just change the email address in the 'to' field.";
	
	if($form_submitted != 1)
		$to = "list@changetheclimate.org";
	
$form = "$error_message<table border='0' cellspacing='0' cellpadding='3' width='475' align='center'><form name='newsletter' method='post' action='index.php?function=mailinglist' onSubmit=\"popup('index.php?function=mailinglist','newsletter','width=650,height=300,scrollbars=yes')\" target='newsletter'><tr><td align='right' valign='top' class='formlabel' width='200'>To:</td><td width='275'><input name='to' type='text' id='to' size='45' value='$to'></td></tr><tr><td align='right' valign='top' class='formlabel'>Subject:</td><td><input name='subject' type='text' id='subject' size='45' value='$subject'></td></tr><tr><td align='right' valign='top' class='formlabel'>Message:</td><td><textarea name='message' cols='45' rows='16' id='message'>$message</textarea></td></tr><tr align='right' valign='top'><td colspan='2'><input name='send_newsletter' type='submit' id='send_newsletter' value='Send Newsletter' class='buttons'><input name='form_submitted' type='hidden' value='1'></td></tr></form></table>";


	return $form;

}


function send_newsletter(){

	global $to, $subject, $message;

	$message .= "\n\n------------------------------------------\nGo to <a href='http://www.changetheclimate.org/unsubscribe.php'>www.changetheclimate.org/unsubscribe.php</a> to remove yourself from this mailing list.";	
	$original_message = stripslashes($message);
	$subject = stripslashes(trim($subject));
	$to = trim($to);
	$headers = "MIME-Version: 1.0\nX-Mailer: phpMail\nContent-type: text/html; charset=iso-8859-1\nFrom: Joseph White <joe@changetheclimate.org>\n";
	
	if($to == "list@changetheclimate.org"){
		$result = db_query("SELECT email_address, first_name from mailinglist",db_user,db_pw,database);
		echo "getting read to send out the emails. . . <BR>";
		while($row = mysql_fetch_array($result)){
			$message = '';	
			if($row["first_name"] != '')
				$message = "Dear " . StripSlashes($row["first_name"]) . ",\n\n" . $original_message;
			
			else
				$message = "Dear Friend,\n\n" . $original_message;


			mail($row["email_address"],$subject,nl2br($message),$headers,"-fjoe@changetheclimate.org");
			echo "email was sent to " . $row["email_address"] . "<BR>";
			//ob_flush();
			flush();				

		}
	}
	
	else{
		$mailsent = mail($to,$subject,nl2br($original_message),$headers,"-fjoe@changetheclimate.org");
		//$mailsent = mail($to,$subject,nl2br($original_message),$headers);
		echo "$mailsent <font size='-1' face='Verdana, Tahoma, Arial, sans-serif' color='545050'>mail sent to $to</font>";
	}

}


?>