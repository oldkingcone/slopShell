<?php


 function menu($clear){
     if (!empty($clear)) {
         shell_exec($clear);
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

function initer_curl(){
    $default_opts = [
        "UA" => "sp/1.1"
    ];
     $chh = curl_init();
     curl_setopt($chh, CURLOPT_USERAGENT, $default_opts['UA']);
     return $chh;
}

function sys($host)
{
    if (!empty($host))
    {
        $curlHandle = initer_curl();
        $syst = curl_exec($curlHandle, $host);
    }else{
        print("[ !! ] Host was empty... [ !! ]");
    }
}
function rev($host, $shell, $port, $method)
{

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


 $run = true;
 while ($run) {
     print("\033[33;40mPlease select your choice: \n->");
     echo ("\033[0m");
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
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            {
                menu('cls');
            }else
            {
                menu('clear');
            }
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

