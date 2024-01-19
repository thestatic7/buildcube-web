<?php
    header("Content-type: application/json; charset=utf-8");
    $cooldown = 60;

    $cachetime = fopen("cache_time.txt", "w");
    fwrite($cachetime, time() - (time() % $cooldown));
    fclose($cachetime);

    if ((time() - file_get_contents("cache_time.txt")) >= $cooldown) {
        updateCrypto();
    }

    function updateCrypto() {
        $cachedconversion = fopen("cached_conversion.txt", "w");
        $txt = file_get_contents("http://95.217.92.207:25670/api/method/crypto.getprice");
        fwrite($cachedconversion, $txt);
        fclose($cachedconversion);
    };

    $response = file_get_contents("cached_conversion.txt");
    die($response);
?>