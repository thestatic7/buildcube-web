<?php 
session_start();

require($_SERVER['DOCUMENT_ROOT'].'/auth/Auth.php');
$auth = new Auth();

if (session_destroy()) {
    // redirect to the login page
    $auth->clearCookies();
    header("location: https://buildcube.ru/");
    exit;
}

?>