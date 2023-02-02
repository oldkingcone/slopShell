<?php

namespace menu_items;
class logo
{
    public function opts(bool $proxy_set)
    {
        system(clears);
        print("\n\n" . str_repeat("-", 35) . "\n");
        echo "\033[4;33;40mIs tor running?\033[0m" . PHP_EOL;
        echo system("systemctl status tor") . PHP_EOL;
        print("\n\n" . str_repeat("-", 35) . "\n");
        curl_setopt(CHH, CURLOPT_HEADER, 0);
        curl_setopt(CHH, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(CHH, CURLOPT_URL, "https://icanhazip.com");
        if ($proxy_set === true) {
            echo "\e[0;32;40m[ ** ] Good job, proxy has been set. Please wait, checking what our IP address is. [ ** ]\e[0m" . PHP_EOL;
        } else {
            echo "\e[0;31;40m[ !! ] No proxy has been set... [ !! ]\e[0m" . PHP_EOL;
        }
        echo "[ ++ ] Our IP Address is: " . trim(curl_exec(CHH)) . " [ ++ ]" . PHP_EOL;
        curl_reset(CHH);
        echo PHP_EOL;
    }

    private function menu()
    {
        echo <<< _MENU
        \e[0;32m(O)ptions
        (R)eset proxy                                                                   
        (Sys)tem enumeration                                                        
        (Rev)erse shell                                                             
        (Com)mand Execution                                                         
        (CL)oner
        (CR)eate Dropper\e[0m                                                                                                                               
        \e[0;32m(A)dd new host                                                              
        (CH)eck if hosts are still pwned
        (AT) Add tool\e[0m
        \e[0;32m(L)ist (T)ools
        \e[0;32m(G)rab (P)roxy\e[0m
        (G)enerate (C)ert                                            
        (M)ain menu                                                                 
        (Q)uit\e[0m                                                                      
_MENU;
        echo "\n\n\033[0m\n";

    }

    function display_logo(array $d)
    {
        system($d['cl']);
        if ($d['last'] === "q") {
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
        } else if (is_null($d['last'])) {
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
            $this->menu();
        } else {
            echo("\033[33;40m                                                                                    \033[0m\n");
            echo("\033[33;40m    ▄▄▄▄▄   █    ████▄ █ ▄▄  █ ▄▄ ▀▄    ▄     ▄█▄    █    ▄█ ▄███▄      ▄     ▄▄▄▄▀ \033[0m\n");
            echo("\033[33;40m   █     ▀▄ █    █   █ █   █ █   █  █  █      █▀ ▀▄  █    ██ █▀   ▀      █ ▀▀▀ █    \033[0m\n");
            echo("\033[33;40m ▄  ▀▀▀▀▄   █    █   █ █▀▀▀  █▀▀▀    ▀█       █   ▀  █    ██ ██▄▄    ██   █    █    \033[0m\n");
            echo("\033[33;40m  ▀▄▄▄▄▀    ███▄ ▀████ █     █       █        █▄  ▄▀ ███▄ ▐█ █▄   ▄▀ █ █  █   █     \033[0m\n");
            echo("\033[33;40m                ▀       █     █    ▄▀         ▀███▀      ▀ ▐ ▀███▀   █  █ █  ▀      \033[0m\n");
            echo("\033[33;40m                         ▀     ▀                                     █   ██         \033[0m\n");
            echo("\033[33;40m                                                                                    \033[0m\n");
            echo("\033[33;40m  Last command: {$d['last']}                                                               \033[0m\n");
            print(sprintf("\033[33;40m  Last Host -> %s", $d['previous_host']) . "\033[0m\n");
            if ($d['error'] === true && !empty($d['error_value'])) {
                echo("\033[1;37;44m  What was the error: {$d['error_value']}                                  \033[0m" . PHP_EOL);
            }
            $this->menu();
        }

    }

}