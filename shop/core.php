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

$buy = 
[
    new Page("Premium", strval(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/shop/offers/1.htm'))),
    new Page("Legend", strval(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/shop/offers/2.htm'))),
    new Page("Ultra", strval(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/shop/offers/3.htm'))),
    new Page("Sponsor", strval(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/shop/offers/4.htm'))),
    new Page("Prime", strval(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/shop/offers/5.htm'))),
    new Page("Support", strval(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/shop/offers/6.htm'))),
    new Page("Кубы", strval(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/shop/offers/7.htm'))),
    new Page("Кейсы", strval(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/shop/offers/8.htm'))),
    new Page("Донат-кейсы", strval(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/shop/offers/9.htm')))
];

if(isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = "404";
}

if(isset($_GET["id"])) {
    $id = $_GET["id"];
} else {
    $id = 0;
}

$response = new Page("404", "Страница не найдена. <a href='/'>Назад</a>");

switch($page) {
    case "main":
        $response = new Page("Магазин", strval(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/shop/list.htm')));
        break;
    case "buy":
        if($id > 0) {
            if(isset($buy[$id - 1])) {
                $response=$buy[$id - 1];
            }
        }
        break;
    }

die(json_encode($response));
?>