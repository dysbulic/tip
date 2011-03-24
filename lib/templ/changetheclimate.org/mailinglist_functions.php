<?

function mailinglist_form($email_address, $first_name, $last_name){

	echo '<form method="POST" action="' . $PHP_SELF . '">
	
	<table cellpadding="4" border="1" width="500" cellspacing="0">
		<tr>
			<td align="right"><font face="verdana, geneva, tahoma, arial" size="-1">email address:</font></td><td><input type="text" name="email_address" value="' . $email_address . '" size="35"></td></tr>
		<tr>
			<td colspan="2"><font face="verdana, geneva, tahoma, arial" size="-1">To personalize your newsletter and receive occasional snail mailings from us, enter your contact information below:</font></td></tr>
		
		<tr>
			<td align="right"><font face="verdana, geneva, tahoma, arial" size="-1">first name:</font></td><td><input type="text" name="first_name" value="' . $first_name . '" size="35"></td></tr>
		<tr>
			<td align="right"><font face="verdana, geneva, tahoma, arial" size="-1">last name:</font></td><td><input type="text" name="last_name" value="' . $last_name . '" size="35"></td></tr>
		<tr>
			<td align="right"><font face="verdana, geneva, tahoma, arial" size="-1">address:</font></td><td><input type="text" name="address_1" value="' . $address_1 . '" size="35"></td></tr>
		<tr>
			<td align="right">&nbsp;</td><td><input type="text" name="address_2" value="' . $address_2 . '" size="35"></td></tr>
		<tr>
			<td align="right"><font face="verdana, geneva, tahoma, arial" size="-1">city:</font></td><td><input type="text" name="city" value="' . $city . '" size="35"></td></tr>
		<tr>
			<td align="right"><font face="verdana, geneva, tahoma, arial" size="-1">state:</font></td><td>'; 
			
			include ("states_popup.php");
			
			echo '</td></tr><tr><td align="right"><font face="verdana, geneva, tahoma, arial" size="-1">phone:</font></td><td><input type="text" name="phone" size="20" value="' . $phone . '"></td></tr><tr><td align="right" colspan="2">';

		if($HTTP_GET_VARS["function"] == 'update' && $HTTP_GET_VARS["id"] != ''){
			echo '<input type="hidden" name="id" value="' . $HTTP_GET_VARS["id"] . '"><input type"submit" name="update" value="update">';
		}
		
		else{
			echo '<input type="submit" name="subscribe" value="subscribe">';
		}

echo '<p><font face="verdana, geneva, tahoma, arial" size="-2">Please read our <a href="privacy.php" target="_blank">privacy policy</a></font></td></tr></table></form>';

}