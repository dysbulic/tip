<?php
if($_SERVER['REMOTE_ADDR'] == "127.0.0.1" || $_SERVER["HTTP_HOST"] != "stoparrestingpatients.org") {
  $user = "will";
  $password = "";
  $host = "localhost";
  $database = "websites";
} else {
  $user = "mpp";
  $password = "mptechp";
  $host = "db.mpp.org";
  $database = "websites";
}
?>