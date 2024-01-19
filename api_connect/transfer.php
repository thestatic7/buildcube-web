<?php 
    header("Content-type: application/json; charset=utf-8");
    $receiver = $_POST['receiver'];
    $amount = $_POST['amount'];
    $balance = 1;
    $response = "";

    class Message
    {
    public $key;
    public $msg;

    public function __construct($key, $msg) {
        $this->key = $key;
        $this->msg = $msg;
    }
    }

    if(isset($receiver) && !empty($receiver)) {
        require($_SERVER['DOCUMENT_ROOT'].'/auth/config.php');

        $sql = "SELECT * FROM `table` WHERE `NAME` = '" . $receiver . "'";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            if(isset($amount) && !empty($amount) && $amount !== 0) {
                session_start();
                require($_SERVER['DOCUMENT_ROOT'].'/api_connect/getbalance.php');
                
                if($balance < $amount) {
                    $response = new Message("message", 'Недостаточно средств!');
                } else if ($_SESSION["user"] == strtolower($receiver)) {
                    $response = new Message("message", 'Зачем переводить кубы самому себе?');
                } else if ($amount < 0) {
                    $response = new Message("message", 'Так нельзя…');
                } else{
                    $response = new Message("message", "<span style='color: green;'>Успешный перевод! C вашего счёта списано " . $amount . " кубов.</span>");
                    transferCrypto();
                };
            } else {
                $response = new Message("message", 'Введите количество криптовалюты.');
            };
        } else {
            $response = new Message("message", 'Такого ника нет на сервере!');
        }
    } else {
        $response = new Message("message", 'Введите ник.');
    };

    function transferCrypto() {
        $removelink = 'http://95.217.92.207:25670/api/method/crypto.removefrombalance?access_key=DES60ehAaUZbvn533aSGEnzS9D2g14nA3erT34yFzV4xADJf15xEhD2fdasdfdaSDBer2IzAnvB1&nickname=' . $_SESSION['user'] . '&amount=' . $_POST['amount'];
        $addlink = 'http://95.217.92.207:25670/api/method/crypto.addtobalance?access_key=DES60ehAaUZbvn533aSGEnzS9D2g14nA3erT34yFzV4xADJf15xEhD2fdasdfdaSDBer2IzAnvB1&nickname=' . $_POST['receiver'] . '&amount=' . $_POST['amount'];
        $remove = json_decode(file_get_contents($removelink));
        $add = json_decode(file_get_contents($addlink));
        if ($remove->response->error == "request is invalid") {
            $response = new Message("message", 'Deletion query is invalid! (02)');
        }
        if ($add->response->error == "request is invalid") {
            $response = new Message("message", 'Addition query is invalid! (01)');
        }
    }

    die(json_encode($response));
    $conn->close();
?>