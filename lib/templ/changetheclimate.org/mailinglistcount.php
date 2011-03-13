<?
include ("admin/lib/db_query.php");

$results = db_query("SELECT email_address from mailinglist");
$num_results = mysql_num_rows($results);

echo "$num_results email addresses on the mailing list.";
?>