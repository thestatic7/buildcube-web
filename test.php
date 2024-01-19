<?php
require($_SERVER['DOCUMENT_ROOT'].'/lib/Rcon.php');

$host = 'f4.joinserver.ru';
$port = 25636;
$password = 'cK2mvmLc1MCKxmkKD205lxX';
$timeout = 20;

use Thedudeguy\Rcon;

$rcon = new Rcon($host, $port, $password, $timeout);

if ($rcon->connect())
{
  $rcon->sendCommand("say Test RCON connection has been issued!");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>rcon test</title>
        <meta charset="utf-8">
    </head>
    <body>
        <h1>RCON connection test</h1>
    </body>
</html>