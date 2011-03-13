<?
	function update_form(){
		echo '<form action="' . $PHP_SELF . '" method="post"><input type="text" name="email_address" size="35"><input type="submit" name="update" value="update"></form>';
	}
	include ("admin/db_query.php");
	include ("top.php");
	include ("admin/valid_email.php");
	$email_address = trim($email_address);
	$first_name = trim($first_name);
	$last_name = trim($last_name);
	$address_1 = trim($address_1);
	$address_2 = trim($address_2);
	$city = trim($city);
	$zip = trim($zip);
	$phone = trim($phone);
	$update = trim($update);
	
	if($id != '' && isset($update_listing)){
		if($first_name == '')
			$errorMessage = '<li>You must provide your first name if you would like to receive snail mailings.</li>';
							
		if($last_name == '')
			$errorMessage .= '<li>You must provide your last name if you would like to receive snail mailings.</li>';
			
		if($address_1 == '')
			$errorMessage .= '<li>You must provide your mailing address to receive snail mailings.</li>';
			
		if($city == '')
			$errorMessage .= '<li>You must provide your city if you would like to receive snail mailings.</li>';
				
		if($states == '')
			$errorMessage .= '<li>You must provide your state if you would like to receive snail mailings.</li>';
		
		if($zip == '')
			$errorMessage .= '<li>You must provide your zip code if you would like to receive snail mailings.</li>';
			
		if($errorMessage == ''){
		
			db_query("UPDATE mailinglist SET first_name = '$first_name', last_name = '$last_name', address_1 = '$address_1', address_2 = '$address_2', city = '$city', state = '$states', zip = '$zip', phone = '$phone' WHERE id = $id");
		}
				
	}
	
	
	if(isset($update) && (CheckEmail($email_address))){

		$result = db_query("SELECT email_address, id FROM mailinglist WHERE email_address = '$email_address'");
		$result_num = mysql_numrows($result);
		
		if($result_num > 0 || ($errorMessage != '' && isset($update_listing))){
			if(isset($update_listing) && $errorMessage == ''){
				echo '<font face="verdana, geneva, tahoma, arial"><B>Thank You!</b></font><p><font face="verdana, geneva, tahoma, arial" size="-1">Your listing on our mailing list has been updated.</font></p>';
			}
			
			else{
				$row = mysql_fetch_object($result);
				echo '<font face="verdana, geneva, tahoma, arial"><B>Update Your Listing</b></font><p><font face="verdana, geneva, tahoma, arial" size="-1">Enter your contact information below to personalize your newsletters and to receive occasional snail mailings from Change the Climate.</font></p>';
				if($errorMessage != '')
				echo '<font face="verdana, geneva, tahoma, arial" size="-1"><ul>' . $errorMessage . '</ul></font>';
				
				include ("mailinglist_form.php");
			}
		}
		
		else{
			echo '<font face="verdana, geneva, tahoma, arial" size="-1">' . StripSlashes($email_address) . ' was not found on our mailing list.  Click <a href="mailinglist.php">here</a> to sign up or click <a href="listupdate.php">here</a> to try again.</font>';
		}
	
	}
		
	else{
		echo '<font face="verdana, geneva, tahoma, arial"><B>Update Your Listing</b></font><p><font face="verdana, geneva, tahoma, arial" size="-1">Enter your email address below to update your mailing list listing.</font></p>';
	
		update_form();
	
	}
	
	
	
	include ("right.php");
?>