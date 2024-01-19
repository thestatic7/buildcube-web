<?php 
header("Content-type: application/json; charset=utf-8");
require($_SERVER['DOCUMENT_ROOT'].'/lib/lib/autoload.php');
use YooKassa\Client;
$client = new Client();
$client->setAuth('915483', 'live_f_5YJZNm596S28k-4Ngmdw1uKjylFf4Z0A8j_IgzwZA');

$username = $_POST['username'];
$amount = $_POST['amount'];
$balance = 1;
$phpmsg = "";

class Message
{
    public $key;
    public $msg;

    public function __construct($key, $msg) {
        $this->key = $key;
        $this->msg = $msg;
    }
}

if(isset($username) && !empty($username)) {
    require($_SERVER['DOCUMENT_ROOT'].'/auth/config.php');

    $sql = "SELECT * FROM `table` WHERE `NAME` = '" . $receiver . "'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        if(isset($amount) && !empty($amount) && $amount !== 0) {

        } else {
            $phpmsg = new Message("message", 'Введите количество.');
        };
    } else {
        $phpmsg = new Message("message", 'Такого ника нет на сервере!');
    }
} else {
    $phpmsg = new Message("message", 'Введите ник.');
};

$username = strtolower($username);
$boughtamount = $amount;
$offerid = 7;
$idempotenceKey = uniqid('', true);

require($_SERVER['DOCUMENT_ROOT'].'/api_connect/getprice.php');
$cryptoprice = file_get_contents("cached_conversion.txt");

$response = $client->createPayment(
    array(
        'amount' => array(
            'value' => $boughtamount * $cryptoprice,
            'currency' => 'RUB',
        ),
        'confirmation' => array(
            'type' => 'redirect',
            'locale' => 'ru_RU',
            'return_url' => 'https://buildcube.ru/payment.php?id='.$idempotenceKey,
        ),
        'description' => 'Покупка товаров в магазине BuildCube',
    ),
    $idempotenceKey
);


$confirmationUrl = $response->getConfirmation()->getConfirmationUrl();
$uuid = $response->id;
$pstatus = "unconfirmed";

require($_SERVER['DOCUMENT_ROOT'].'/shop/payment-config.php');
$sql = "SELECT * FROM `payments` WHERE `uniqid` = '" . $idempotenceKey . "'";
$result = $db->query($sql);
if ($result->num_rows == 0) {
    $insert = "INSERT INTO `payments` (uniqid, uuid, username, pstatus, offerid, amount) VALUES ('".$idempotenceKey."', '".$uuid."', '".$username."', 'unconfirmed', '".$offerid."', '".$boughtamount."')";
    $db->query($insert);
    
    $phpmsg = new Message("message", 'Нужен редирект.'); 
} else {
    $phpmsg = new Message("message", 'Этот ID платежа уже использовался.'); 
}
$db->close();

die(json_encode($phpmsg));
?>