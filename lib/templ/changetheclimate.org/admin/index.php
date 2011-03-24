<?
header("Expires: Mon, 26 Jul 1900 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");  

require_once("lib/conf.php");
require_once("lib/admin_html.php");
require_once("lib/db_query.php");
$do_html = 1;
$function = trim($function);

/*here is where we process everything*/
	switch($function){

		case "mailinglist" :
			require_once("lib/newsletter.php");
			
			if($form_submitted == 1){
			
				if(validate_newsletter() == ""){
					send_newsletter();
					$do_html = 0;
				}				
				
				else
					$display = display_newsletter();
			
			}
			
			else
				$display = display_newsletter();
			
			break;
		
		
		default :
			$display = file_get_contents("lib/adminhome.html");
			break;

	}
	
if($do_html == 1){
 	echo display_top();
	echo $display;
	echo $bottom;
}

?>