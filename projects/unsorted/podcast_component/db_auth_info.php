<?php
if($_SERVER["HTTP_HOST"] != "odin.himinbi.org") {
  $database = "joomla";
  $host = "localhost";
  $user = "will";
  $password = "echodog";
  $table_prefix = "jos_";
} else {
  $user = "wholcomb";
  $password = "echodog";
  $host = "mysql.madstones.com";
  $database = "indiefeed";
  $table_prefix = "jos_";
}
?>
