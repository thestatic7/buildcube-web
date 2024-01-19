<?php
header("Content-type: application/json; charset=utf-8");
class Page
{
    public $title;
    public $desc;

    public function __construct($title, $desc) {
        $this->title = $title;
        $this->desc = $desc;
    }
}

if(isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = "404";
}

/* if(isset($_GET["id"])) {
    $id = $_GET["id"];
} else {
    $id = 0;
} */

$response = new Page("404", "How tf did you end up here. <a class='link_internal' href='/main'>Назад</a>");

switch($page) {
    case "main":
        $response = new Page("Личный кабинет", strval(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/panel/main.htm')));
        break;
    case "crypto":
        $response = new Page("Панель управления криптовалютой", strval(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/panel/crypto.htm')));
        break;
    case "2fa":
        $response = new Page("Панель управления криптовалютой", strval(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/panel/2fa.htm')));
        break;
    case "vote":
        $response = new Page("Панель управления криптовалютой", strval(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/panel/vote.htm')));
        break;
    case "help":
        $response = new Page("Панель управления криптовалютой", strval(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/panel/help.htm')));
        break;
    default:
    $response = new Page("404", "К сожалению, такой страницы нет. <a class='link_internal' href='/main'>Назад</a>");
    }

die(json_encode($response));
?>