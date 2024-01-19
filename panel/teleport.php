<?php
header("Content-type: application/json; charset=utf-8");
$rcon = new Rcon($host, $port, $password, $timeout);
require($_SERVER['DOCUMENT_ROOT'].'/lib/Rcon.php');

// Creative credentials
$host = 'n1.joinserver.ru';
$port = 25686;
$password = '2ckrCDL0cA7SDFL215VSFj5';
$timeout = 20;

// Lobby credentials (if neccessary)
/*
$host = 'f4.joinserver.ru';
$port = 25636;
$password = 'cK2mvmLc1MCKxmkKD205lxX';
$timeout = 20;
*/

use Thedudeguy\Rcon;
if ($rcon->connect())
{
$rcon->sendCommand("spawn ".$_SESSION["user"]);
$response = array("response" => "Успешно телепортировали ".$_SESSION["user"]." на спаун!");
} else {
    $response = throw new Exception("Не удалось установить соединение с сервером. Свяжитесь с нами по адресу admin@buildcube.ru");
}
die(json_encode($response));
?>