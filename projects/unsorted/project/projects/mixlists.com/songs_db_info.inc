<?php
if($_SERVER['REMOTE_ADDR'] == "127.0.0.1" || $_SERVER["HTTP_HOST"] != "mixlists.com") {
  $user = "will";
  $password = "echodog";
  $database = "musicbrainz";
  $host = "localhost";
} else {
  $user = "wholcomb";
  $password = "echodog";
  $database = "songs";
  $host = "mysql.madstones.com";
}
?>
