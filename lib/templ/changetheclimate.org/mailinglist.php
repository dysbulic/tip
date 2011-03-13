<? 
	$subscribe = trim($subscribe);
	$email_address = trim($email_address);
	$first_name = trim($first_name);
	$last_name = trim($last_name);
	$address_1 = trim($address_1);
	$address_2 = trim($address_2);
	$city = trim($city);
	$zip = trim($zip);
	$phone = trim($phone);

	
	if (isset($subscribe) && $email_address != ""){
	
		if($first_name != '' || $last_name != '' || $address_1 != '' || $address_2 != '' || $city != '' || $states != '' || $zip != ''){
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
		}
				
			
			
		include ("admin/lib/valid_email.php");
		if (!check_email($email_address)){
			$errorMessage .= '<li>' . StripSlashes($email_address) . ' is an invalid email address.</li>';
		}
		
		else{
			
			include ("admin/lib/db_query.php");
			
			$results = db_query("SELECT email_address from mailinglist where email_address = '$email_address'");
			$num_results = mysql_num_rows($results);
			
			if ($num_results > 0){
				$errorMessage .= '<li>' . $email_address . ' is already on our mailing list.</li>';
			}
			else{
				if($errorMessage == ''){
				db_query("INSERT INTO mailinglist (email_address, first_name, last_name, address_1, address_2, city, state, zip, phone) VALUES('$email_address', '$first_name', '$last_name', '$address_1', '$address_2', '$city', '$states', '$zip', '$phone')");
				$errorMessage = 1;
				}
				
			}
		}
		
	} 

if($_SERVER['SCRIPT_NAME'] != '/sendurl.php'){	
	$page_title = "Marijuana Reform, Education, and the War on Drugs : Join our Mailing List";			
	 include ("top.php");
	
	if ($errorMessage == 1){
	
		echo '<font face="verdana, geneva, tahoma, arial" size="+1"><b>Thank You!</b></font>';
		echo '<p><font face="verdana, geneva, tahoma, arial" size="-1">' . $email_address . ' has been added to our mailing list.</font>';
	
	}
	
	else{ 
		
	
		echo '<font face="verdana, geneva, tahoma, arial" size="+1"><b>Join Our Mailing List</b></font><p><font face="verdana, geneva, tahoma, arial" size="-1">Sign up for our mailing list and receive updates about Change the Climate and our effort to tell the truth about marijuana.</font></p>';
		if($errorMessage != '')
			echo '<font face="verdana, geneva, tahoma, arial" size="-1"><ul>' . $errorMessage . '</ul></font>';
			include ("mailinglist_form.php");
	
	 } 
		$errorMessage = "";
		include ("right.php"); 
 
 }
?>
