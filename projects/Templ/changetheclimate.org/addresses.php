<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<?
require_once("admin/lib/db_query.php");

function write_page($fp, $write_string){
	
	if(file_exists($fp))
		unlink("$fp");
	
	$fp = fopen($fp, 'w+');
	if($fp == null) {
		print "Create a file failed\n";
		exit;
	}
	
	fwrite($fp,$write_string);
	
	fclose($fp);
}


$row_q = db_query("desc mailinglist");

$cnt = 0;
while($row = mysql_fetch_array($row_q)){
	print $row[0] . "\n";
}
print "<hr>\n";

$addresses_q = db_query("SELECT * from mailinglist where zip <> 'null' and zip <> ''");
$addresses = 'ID,EMAIL,FIRST NAME,LAST NAME,ADDRESS 1,ADDRESS 2,CITY,STATE,ZIP\n';
while($row = mysql_fetch_array($addresses_q)){

	$addresses .= $row['id'] . ',"' . 
		stripslashes($row['email_address']) . '","' .
		stripslashes($row['first_name']) . '","' . stripslashes($row['last_name']) . '","' . stripslashes($row['address_1']) . '","' . stripslashes($row['address_2']) . '","' . stripslashes($row['city']) . '","' . $row['state'] . '","' . $row['zip'] . "\"\n";	

}

//$fp = "/www/changetheclimate.com/htdocs/addresses.csv";

 $fp = "data/addresses.csv";

write_page($fp,$addresses);
echo "all done!";

?>
</body>
</html>
