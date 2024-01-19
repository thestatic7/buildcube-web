<?php
require($_SERVER['DOCUMENT_ROOT'].'/auth/logout.php');
session_destroy();
$auth->clearCookies();
?>