<?php
$message = '';
$username = strval(trim(htmlspecialchars($_POST['usernickname'])));
$password = strval(($_POST['userpassword']));
require($_SERVER['DOCUMENT_ROOT'].'/auth/session.php');
require($_SERVER['DOCUMENT_ROOT'].'/auth/Auth.php');
require($_SERVER['DOCUMENT_ROOT'].'/auth/authCookieSessionValidate.php');
if (isset($_REQUEST['submit_btn'])) {
    $auth = new Auth();
    
    if ($auth->isNicknameEmpty($username)) {
        $message = "<span style='color:red;'>Введите никнейм.</span>";
    } else if ($auth->isPasswordEmpty($password)) {
        $message = "<span style='color:red;'>Введите пароль.</span>";
    } else if (!($auth->isNicknameValid($username))) {
        $message = "<span style='color:red;'>Данный пользователь не зарегистрирован.</span>";
    } else if (!($auth->isPasswordValid($username, $password))) {
        $message = "<span style='color:red;'>Неверный пароль.</span>";
    } else {
        if (! empty($_POST["remember"])) {
            setcookie("member_login", $username, $cookie_expiration_time, "/");
            
            $random_password = uniqid();
            setcookie("random_password", $random_password, $cookie_expiration_time, "/");
            
            $random_selector = uniqid($more_entropy=true);
            setcookie("random_selector", $random_selector, $cookie_expiration_time, "/");
            
            $random_password_hash = password_hash($random_password, PASSWORD_DEFAULT);
            $random_selector_hash = password_hash($random_selector, PASSWORD_DEFAULT);
            
            $expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);
            
            // mark existing token as expired
            $userToken = $auth->getTokenByUsername($username, 0);
            if (! empty($userToken["id"])) {
                $auth->markAsExpired($userToken["id"]);
            }
            // Insert new token
            $auth->insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date);
        } else {
            $auth->clearCookies();
        }
        header("location: /panel/");
        exit;
    }
}
?>