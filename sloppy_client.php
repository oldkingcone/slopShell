<?php

$config = parse_ini_file('', true);
define("CHH", curl_init());
define("UAGENT", curl_setopt(CHH, CURLOPT_USERAGENT, $config['useragent']));

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

function rev($host, $shell, $port, $method)
{
    $usePort = "";
    if (isset($host) and isset($port)) {
        if ($port === "default") {
            $usePort = "1634";
        } else {
            $usePort = $port;
        }
        if (isset($shell) or $method){
            echo "[ !! ] Setting custom options: \n";
            echo "Shell: ".$shell."\n";
            echo "Method: ". $method."\n[ !! ]";
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
        return 1;
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
            echo queryDB($h);
            if (!empty($h) && !empty($p)){
                rev($h, $p, );
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

