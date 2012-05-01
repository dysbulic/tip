<?php
function get_db($type) {
  $db = dbx_connect($type, "localhost", "users", "will", "my");
  return $db;
}

class User {
  var $username;
  function User($username) {
    $this->username = $username;
  }
}
?>