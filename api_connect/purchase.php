<?php 
header("Content-type: application/json; charset=utf-8");
require($_SERVER['DOCUMENT_ROOT'] . '/lib/lib/autoload.php');
require($_SERVER['DOCUMENT_ROOT'].'/auth/config.php');
require($_SERVER['DOCUMENT_ROOT'].'/shop/payment-config.php');
use YooKassa\Client;
$client = new Client();
$client->setAuth('915483', 'live_f_5YJZNm596S28k-4Ngmdw1uKjylFf4Z0A8j_IgzwZA');
$conn = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);
if($conn === false){
    die("Error: connection error. " . mysqli_connect_error());
}

$username = $_POST['username'];
$amount = $_POST['amount'];
$cryptoprice = $_POST['cryptoprice'];
$offerid = $_POST['offerid'];
$balance = 1;
$phpmsg = "";

function identify_id($o) {
    switch ($o) {
        case "premium":
            return 1;
            break;
        case "legend":
            return 2;
            break;
        case "ultra":
            return 3;
            break;
        case "sponsor":
            return 4;
            break;
        case "prime":
            return 5;
            break;
        case "support":
            return 6;
            break;
        case "assistant": case "helper": case "youtube": case "builder": case "staff": case "admin": case "dev":
            return 500;
    }
}

function identify_price($o) {
    switch ($o) {
        case 1: case "premium":
            return 100;
            break;
        case 2: case "legend":
            return 200;
            break;
        case 3: case "ultra":
            return 300;
            break;
        case 4: case "sponsor":
            return 650;
            break;
        case 5: case "prime":
            return 1200;
            break;
        case 6: case "support":
            return 1500;
            break;
    }
}

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


    $sql = "SELECT * FROM `AUTH` WHERE `NICKNAME` = '" . $username . "'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        $username = strtolower($username);
        $boughtamount = $amount;
        $idempotenceKey = uniqid('', true);
        switch ($offerid) {  
            case 1: case 2: case 3: case 4: case 5: case 6:
                require($_SERVER['DOCUMENT_ROOT'].'/api_connect/groups.php');
                try {
                    $getgroup = @file_get_contents('http://95.217.92.207:25670/api/method/vault.getgroup?access_key=DES60ehAaUZbvn533aSGEnzS9D2g14nA3erT34yFzV4xADJf15xEhD2fdasdfdaSDBer2IzAnvB1&nickname=' . $username);
                    if ($getgroup === false) {
                        throw new Exception("HTTP-запрос не удался! Свяжитесь с нами по адресу admin@buildcube.ru");
                    }
                    $apiresponse = json_decode($getgroup);
                    $activegroup = $apiresponse->response->groups[0];
                    $activegroupid = identify_id($activegroup);
                    if ($offerid <= $activegroupid) {
                        $phpmsg = new Message("message", 'У вас уже есть звание, равное этому или выше.');
                        $conn->close();
                        exit(json_encode($phpmsg));
                    } else {
                        $fullprice = identify_price($offerid);
                        $subtractprice = identify_price($activegroup);
                        $finalpricevalue = $fullprice - $subtractprice;
                    }
                }
                catch(Exception $e) {
                    $phpmsg = new Message('Ошибка: '.$e->getMessage());
                }
                break;
            case 7:
                if(!isset($amount) || empty($amount) || $amount == 0) {
                    $phpmsg = new Message("message", 'Введите количество.');
                    $conn->close();
                } else {
                $finalpricevalue = $boughtamount * $cryptoprice;
                }
                break;
            case 9:
                if(!isset($amount) || empty($amount) || $amount == 0) {
                    $phpmsg = new Message("message", 'Введите количество.');
                    $conn->close();
                } else {
                $finalpricevalue = $boughtamount * 150;
                }
                break;
        };

        $response = $client->createPayment(
            array(
                'amount' => array(
                    'value' => $finalpricevalue,
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
        $conn->close();

        $sqlq = "SELECT * FROM `payments` WHERE `uniqid` = '" . $idempotenceKey . "'";
        $result = $db->query($sqlq);
        if ($result->num_rows == 0) {
            $insert = "INSERT INTO `payments` (uniqid, uuid, username, pstatus, offerid, amount) VALUES ('".$idempotenceKey."', '".$uuid."', '".$username."', 'unconfirmed', '".$offerid."', '".$boughtamount."')";
            $db->query($insert);
            $phpmsg = new Message("message", $confirmationUrl);
        } else {
            $phpmsg = new Message("message", "Этот ID платежа уже существует. (01)");  
        }
        $db->close();
    } else {
        $phpmsg = new Message("message", 'Такого ника нет на сервере!');
        $conn->close();
    }
} else {
    $phpmsg = new Message("message", 'Введите ник.');
};

die(json_encode($phpmsg));
?>