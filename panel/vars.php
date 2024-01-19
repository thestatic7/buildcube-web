<?php
header("Content-type: application/json; charset=utf-8");

session_start();
include($_SERVER['DOCUMENT_ROOT'].'/api_connect/getbalance.php');
$response = array("user"=>$_SESSION["user"], "userid"=>$_SESSION["userid"], "balance"=>$balance);

die(json_encode($response));
?>