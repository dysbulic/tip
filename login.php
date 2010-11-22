<?php
session_start();

include "util.php";
include "User.php";

$username = getvar('username');
if($username) { // A login attempt
        try {
                $user = User::fromUsername($username);
                $password = sha1(getvar('password'));
                if($password == $user->getPasswordHash()) {
                        $_SESSION['user'] = $user;
                        header('Location: index.php');
                } else {
                        require_once('login_form.php');
                        login_form(array(
                                "error => 'Invalid Login'
                        ) );
                }
        } catch(UserNotFoundException $e) {
                require_once('login_form.php');
                login_form();
        }
} else {
        $redirect = getvar('redirect');
        $host = $_SERVER['HTTP_HOST'];

        if($_SESSION['user'] && $redirect) {
                header("Location: http://$host/$redirect");
        } else {
                require_once('login_form.php');
                login_form();
        }
}

