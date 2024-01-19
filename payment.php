<?php
require __DIR__ . '/lib/lib/autoload.php';
use YooKassa\Client;
require($_SERVER['DOCUMENT_ROOT'].'/lib/Rcon.php');

$host = 'f4.joinserver.ru';
$port = 25636;
$password = 'cK2mvmLc1MCKxmkKD205lxX';
$timeout = 20;

use Thedudeguy\Rcon;

$client = new Client();
$client->setAuth('915483', 'live_f_5YJZNm596S28k-4Ngmdw1uKjylFf4Z0A8j_IgzwZA');

$url = $_SERVER['REQUEST_URI'];
$urlarray = explode("=", $url);
$uniqid = strval($urlarray[1]);

$STATUS = "";
$DonateFeedback = "";

function identify_group($o) {
    switch ($o) {
        case 1:
            return "premium";
            break;
        case 2:
            return "legend";
            break;
        case 3:
            return "ultra";
            break;
        case 4:
            return "sponsor";
            break;
        case 5:
            return "prime";
            break;
        case 6:
            return "support";
            break;
    }
}

require($_SERVER['DOCUMENT_ROOT'].'/shop/payment-config.php');

$sql = "SELECT * FROM `payments` WHERE `uniqid` = '".$uniqid."'";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $uuid = $row["uuid"];
        $paymentId = $uuid;
        $offerid = $row["offerid"];
        $username = $row["username"];
        $amount = $row["amount"];

        $payment = $client->getPaymentInfo($uuid);
        $status = $payment->status;
    };

    $insert = "UPDATE `payments` SET pstatus = '".$status."' WHERE `uniqid` = '".$uniqid."'";
    $db->query($insert);

    switch ($status) {
    case "unconfirmed":
        $STATUS = "<br> Этот платёж не был обработан.";
        break;
    case "pending":
        $STATUS = "<br> Этот платёж ещё не проведён. <a href='https://yoomoney.ru/checkout/payments/v2/contract?orderId=".$uuid."'>Продолжить здесь</a>";
        break;
    case "succeeded":    
        $STATUS = "<br> Этот платёж уже подтверждён.";
        $db->query("UPDATE `payments` SET pstatus = 'succeeded' WHERE `uniqid` = '".$uniqid."'");
        break;
    case "waiting_for_capture":
        switch ($offerid) {
            case 1: case 2: case 3: case 4: case 5: case 6:     
                $group = identify_group($offerid);
                try {
                    $setgroup = @file_get_contents('http://95.217.92.207:25670/api/method/vault.setgroup?access_key=DES60ehAaUZbvn533aSGEnzS9D2g14nA3erT34yFzV4xADJf15xEhD2fdasdfdaSDBer2IzAnvB1&nickname=' . $username . '&group=' . $group);
                    if ($setgroup === false) {
                        throw new Exception("HTTP-запрос не удался! Свяжитесь с нами по адресу admin@buildcube.ru");
                    } else {
                        $DonateFeedback = '<br>Успешно приобретена группа '.$group.' на никнейм '.$username.'. ';
                    }
                }
                catch(Exception $e) {
                    $STATUS = 'Ошибка: '.$e->getMessage();
                }
                break;
            case 7:
                try {
                    $cryptoaddition = @file_get_contents('http://95.217.92.207:25670/api/method/crypto.addtobalance?access_key=DES60ehAaUZbvn533aSGEnzS9D2g14nA3erT34yFzV4xADJf15xEhD2fdasdfdaSDBer2IzAnvB1&nickname=' . $username . '&amount=' . $amount);
                    if ($cryptoaddition === false) {
                        throw new Exception("HTTP-запрос не удался! Свяжитесь с нами по адресу admin@buildcube.ru");
                    } else {
                        $DonateFeedback = '<br>Успешно приобретено '.$amount.' кубов на баланс игрока '.$username.'. ';
                    }
                }
                catch(Exception $e) {
                    $STATUS = 'Ошибка: '.$e->getMessage();
                }
                break;
            case 9:
                $rcon = new Rcon($host, $port, $password, $timeout);

                if ($rcon->connect())
                {
                $rcon->sendCommand("bcase give donate ".$username." ".$amount);
                $DonateFeedback = '<br>Успешно приобретено '.$amount.' донат-кейсов на аккаунт '.$username.'. ';
                } else {
                    throw new Exception("Не удалось установить соединение с сервером. Свяжитесь с нами по адресу admin@buildcube.ru");
                }
                break;
        }
        
        $idempotenceKey = uniqid('', true);            
        $response = $client->capturePayment(array(), $paymentId, $idempotenceKey);
        $payment = $client->getPaymentInfo($uuid);
        $status = $payment->status;
        $STATUS =  '<br> Платёж переведён в статус '.$status.'.';
        break;

    case "canceled":
        $STATUS = "<br> Платёж был отменён в связи с тайм-аутом.";
        break;
    default:
        $STATUS = "<br> Неверный статус платежа.";
        break;
    }

} else {
    $STATUS = 'Нет платежа с ID '.$uniqid.' в базе данных.';  
}

$db->close();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>BuildCube</title>
		<title id="title">index</title>
        <?php include($_SERVER['DOCUMENT_ROOT'].'/includes/head.php') ?>
			<!-- grid body -->
			<div class="griMB" id="griMB">
                <div class="mainpage">
                    <div class="vkwidnoflex">
                            <h1>Платёж <?php echo $paymentId ?></h1>
                            <?php echo $STATUS; ?>
                            <?php echo $DonateFeedback; ?>
                            <br><br><br><a href="/">Назад</a>
                    </div>
                </div>
			</div>
		<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php') ?>
	</body>
</html>