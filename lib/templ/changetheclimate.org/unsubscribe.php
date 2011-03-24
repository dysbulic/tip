<? 
	$unsubscribed = 0;
	$unsubscribe = trim($unsubscribe);
	$email_address = trim($email_address);
	if(isset($unsubscribe) && $email_address != ""){
		include ("admin/lib/db_query.php");
		$result = db_query("DELETE from mailinglist WHERE email_address = '$email_address' LIMIT 1");
		$unsubscribed = 1;
	}	
?>
<? include ("top.php"); ?>
<font face="verdana, geneva, tahoma, arial">
<b>Unsubscribe</b>
</font>

<? 
	if($unsubscribed){
		echo '<p><font face="verdana, geneva, tahoma, arial" size="-1">' . $email_address . ' has been removed from our mailing list.</font></p>';
	}
	//else{
?>
<p> <font face="verdana, geneva, tahoma, arial" size="-1">Enter your email address 
  below to unsubscribe from our mailing list.</font>
  
  <form method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="4">
    <tr valign="top"> 
      <td align="right" width="160"><font face="Verdana, Geneva, Tahoma, Arial" size="-1">email 
        address:</font></td>
      <td width="676"> 
        <input type="text" name="email_address" size="30">
        <input type="submit" name="unsubscribe" value="unsubscribe">
      </td>
    </tr>
  </table>
</form>


<? 
	//}
include ("right.php"); ?>
