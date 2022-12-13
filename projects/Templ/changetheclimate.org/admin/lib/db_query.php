<?
	function db_query($sql_call,$user = 'ctclimate',$pw = 
'M4ryJ4n3$!',$database='changetheclimate'){

		global $db_inserted_id;
		
		$database_error = "<center><font face='Verdana, Tahoma, Arial, sans-serif' size='-1'><b>Our database is currently unavailable.  Please check back at a later time.</b></font></center>";

		$db = @ mysql_connect("localhost", "$user", "$pw") OR die("$database_error");
		if(!$db){
			echo $database_error;
			exit;
		}

		mysql_select_db("$database");
		//echo "sql: " . $sql_call . "<BR>";
		$query_result = mysql_query($sql_call);
		$db_inserted_id = mysql_insert_id();
		mysql_close();
		
		return $query_result;

	}

?>
