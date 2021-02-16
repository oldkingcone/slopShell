<?php

$config = parse_ini_file('', true);

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

function sys($host, $userA)
{
    if (!empty($host) && !empty($userA)) {
        $chh = curl_init();
        curl_setopt($chh, CURLOPT_USERAGENT, $userA);
        $syst = curl_exec($chh, $host);
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
            echo "Setting custom options: \n";
            echo "Shell: ".$shell."\n";
            echo "Method: ". $method."\n";
        }
        echo "Trying: " . $host . " on " . $usePort . "\n";
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
            echo "r\n";
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

