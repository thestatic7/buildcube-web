<?php
// Connections
require __DIR__ . '/lib/lib/autoload.php';
use YooKassa\Client;
require($_SERVER['DOCUMENT_ROOT'].'/lib/Rcon.php');
require($_SERVER['DOCUMENT_ROOT'].'/shop/payment-config.php');

// RCON
$host = 'f4.joinserver.ru';
$port = 25636;
$password = 'cK2mvmLc1MCKxmkKD205lxX';
$timeout = 20;

use Thedudeguy\Rcon;

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

// YooKassa data
$client = new Client();
$client->setAuth('915483', 'live_f_5YJZNm596S28k-4Ngmdw1uKjylFf4Z0A8j_IgzwZA');

// Fixing the empty pstatus fields
$db->query("SELECT * FROM `payments` WHERE `pstatus` = ''");
if ($result->num_rows > 0) {
    // Gathering row info
    $uniqid = $row["uniqid"];
    $uuid = $row["uuid"];
    while($row = $result->fetch_assoc()) {
        $db->query("UPDATE `payments` SET pstatus = 'succeeded'");
    }
    echo 'Платёж '.$uuid.' :: '.$uniqid.' обработан и его статус теперь succeeded.';
}

// Main SQL query
$sql = "SELECT * FROM `payments` WHERE `pstatus` = 'unconfirmed'";
$result = $db->query($sql);

// Setting up the loop
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Gathering row info
        $uniqid = $row["uniqid"];
        $uuid = $row["uuid"];
        $paymentId = $uuid;
        $offerid = $row["offerid"];
        $username = $row["username"];
        $amount = $row["amount"];
        // Checking actual payment status
        $payment = $client->getPaymentInfo($uuid);
        $status = $payment->status;
        if ($status !== 'waiting_for_capture') {
            echo 'Платёж '.$uuid.' :: '.$uniqid.' не пройден.<br>';
            $db->query("UPDATE `payments` SET pstatus = 'canceled'");
        } else {
            // Actions if the payment is currently waiting for capture
            // Executing the orders
            if ($offerid < 7) {   
                // Managing groups
                $group = identify_group($offerid);
                try {
                    $setgroup = @file_get_contents('http://95.217.92.207:25670/api/method/vault.setgroup?access_key=DES60ehAaUZbvn533aSGEnzS9D2g14nA3erT34yFzV4xADJf15xEhD2fdasdfdaSDBer2IzAnvB1&nickname=' . $username . '&group=' . $group);
                    if ($setgroup === false) {
                        throw new Exception("HTTP-запрос не удался! Свяжитесь с нами по адресу admin@buildcube.ru");
                    } else {
                        echo '<br>Успешно приобретена группа '.$group.' на никнейм '.$username.'. ';
                    }
                }
                catch(Exception $e) {
                    echo 'Ошибка: '.$e->getMessage();
                }
            } else if ($offerid = 7) {
                // Managing crypto purchases
                try {
                    $cryptoaddition = @file_get_contents('http://95.217.92.207:25670/api/method/crypto.addtobalance?access_key=DES60ehAaUZbvn533aSGEnzS9D2g14nA3erT34yFzV4xADJf15xEhD2fdasdfdaSDBer2IzAnvB1&nickname=' . $username . '&amount=' . $amount);
                    if ($cryptoaddition === false) {
                        throw new Exception("HTTP-запрос не удался! Свяжитесь с нами по адресу admin@buildcube.ru");
                    } else {
                        echo '<br>Успешно приобретено '.$amount.' кубов на баланс игрока '.$username.'. ';
                    }
                }
                catch(Exception $e) {
                    echo 'Ошибка: '.$e->getMessage();
                }
            } else if ($offerid = 9) {
                // Managing BuildCases (Donate)
                $rcon = new Rcon($host, $port, $password, $timeout);        
                if ($rcon->connect()) {
                    $rcon->sendCommand("bcase give donate ".$username." ".$amount);
                    echo '<br>Успешно приобретено '.$amount.' донат-кейсов на аккаунт '.$username.'. ';
                } else {
                    throw new Exception("Не удалось установить соединение с сервером. Свяжитесь с нами по адресу admin@buildcube.ru");
                }
            } else {
                echo 'Отсутствует строчка offerid в базе данных.';
            }
            $idempotenceKey = uniqid('', true);            
            $response = $client->capturePayment(array(), $paymentId, $idempotenceKey);
            $payment = $client->getPaymentInfo($uuid);
            $status = $payment->status;
            echo  '<br> Платёж '.$uuid.' :: '.$uniqid.' переведён в статус '.$status.'.';
            $db->query("UPDATE `payments` SET pstatus = 'succeeded' WHERE `uniqid` = '".$uniqid."'");
        }
    }
    echo "<br>";
} else {
    echo("Нет неподтверждённых платежей.");
}

?>