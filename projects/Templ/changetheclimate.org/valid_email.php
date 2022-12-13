<?
	function checkemail($email_address){
	
		if (!eregi("^[a-zA-Z0-9_\.-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $email_address)){
			return 0;
		}
		
		else{
			return 1;
		}
		
	}