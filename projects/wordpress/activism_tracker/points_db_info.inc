<?php
if($_SERVER['REMOTE_ADDR'] == "127.0.0.1" || $_SERVER["HTTP_HOST"] != "odin.himinbi.org") {
  $user = "fp";
  $password = "mpWordp";
  $database = "activism_wordpress";
  $host = "localhost";
} else {
  $user = "wholcomb";
  $password = "";
  $database = "points";
  $host = "mysql.himinbi.org";
}
?>
