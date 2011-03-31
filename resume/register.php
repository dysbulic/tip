<?php
session_start();

require_once("util.php");
include "User.php";

$name = getvar('name');
$email = getvar('email');
$username = getvar('username');
$password = getvar('password');

if($name && $username && $password) {
        $user = new User($username,
                         $name,
                         $email,
                         sha1($password));
        try {
                $user->save();
                header('Location: index.php');
        } catch(DuplicateUserException $e) {
                require_once('registration_form.php');
                registration_form(array(
                        "error" => 'That username is already taken.'
                ) );
        }
} else {
        require_once('registration_form.php');
        registration_form();
}
