<?php

define("config", parse_ini_file('', true));
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
        (U)pdates                                                                   
        (A)dd new host                                                              
        (CH)eck if hosts are still pwned                                            
        (M)ain menu                                                                 
        (Q)uit                                                                      
_MENU;
    echo "\n\n\033[0m\n";

}

function writeINI($location)
{
    if (empty($location)) {
        throw new Exception("Missing ini file to write to.");
    } else {

    }

}

function sys($host)
{
    if (!empty($host) && !empty($userA)) {
        $syst = curl_exec(CHH, $host);
    } else {
        print("[ !! ] Host was empty... [ !! ]");
    }
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

function co($command, $host)
{

}

function clo($host, $repo)
{

}

function up()
{

}

function aHo($host, $db)
{

}

function check($host, $path)
{

}

function opts()
{

}

function queryDB($host){
    # place holder for the query db function. this will check what information we have about a host, and which options to set.
    # so Example would be a windows based host, it will preset windows options for you when you execute rev, or you can set your own.
    # work in progress.
    # @todo
    if (!empty($host)){
        try {
            $dbC = pg_connect("host=" . config['host'] . " port=" . config['port'] . " user=" . config['username'] . " password=" . config['password']);
            $row = pg_fetch_row($dbC, sprintf("SELECT os_flavor FROM sloppy_bots_main WHERE rhost = '%s'"), pg_escape_string($host));
            if (!empty($row)) {
                return $row[0];
            }else{
                return 0;
            }
        }catch (Exception $e){
            return $e;
        }

    }
    return 0;
}


menu($clear = "clear");
$run = true;
while ($run) {
    print("\033[33;40mPlease select your choice: \n->");
    echo("\033[0m");
    $pw = trim(fgets(STDIN));
    switch (strtolower($pw)) {
        case "s":
            echo "S\n";
            break;
        case "r":
            $h = readline("Please tell me the host.\n->");
            $p = readline("Which port shall we use?\n->");
            if (queryDB($h) ? "win":"lin")
                echo queryDB($h);
            else
                echo "Host is not in the db quite yet. Looks like you need to pwn more.";
            if (!empty($h) && !empty($p)){
                rev($host=$h,"default", $port=$p, $os=queryDB($h));
            }
            break;
        case "c":
            echo "c\n";
            break;
        case "cl":
            echo "cl\n";
            break;
        case "u":
            echo "u\n";
            break;
        case "a":
            echo "a\n";
            break;
        case "ch":
            echo "ch\n";
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

