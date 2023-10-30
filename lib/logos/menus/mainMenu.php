<?php

namespace logos\menus;

class mainMenu
{

    function __construct()
    {
    }

    public function menu(): void
    {
        echo <<< _MENU
        \e[0;32m(O)ptions
        (R)eset proxy                                                                   
        (Sys)tem enumeration                                                        
        (Rev)erse shell                                                             
        (Com)mand Execution                                                         
        (CR)eate Dropper\e[0m                                                                                                                               
        \e[0;32m(A)dd new host                                                              
        (CH)eck if hosts are still pwned
        (AT) Add tool\e[0m
        \e[0;32m(L)ist (T)ools
        \e[0;32m(G)rab (P)roxy\e[0m
        \033[0;36m(Ac)tivate Dropper\033[0m
        (G)enerate (C)ert                                            
        (M)ain menu                                                                 
        (Q)uit\e[0m                                                                      
_MENU;
        echo "\n\n\033[0m\n";
    }

    function dropperMenu(): void
    {
        echo <<<DRI
Here you can choose to make a slim dropper, or a wordpress trojanized plugin. No themes yet for wordpress however. That may come in later versions. 
Basically, the dropper is a stub to write slopshell to the system. 
Choices:
    - chonker: package the whole slop.php script into a wordpress package. (best option since often times a wordpress plugin cannot write to its current directory.)
    - press: in the event you are able to write to a wp-content directory, this would be pretty smart to use.
    - dr: regular dropper, if you are able to write to the servable directory.
    
    Required options:
        - small
                Activation keyword(can be anything you want.)
                
                The script will handle the rest. (this is a small dropper.) 
        - chonker
                Activation Keyword(can be anything...)
                This will package the slop.php trojan into a wordpress plugin, with the add_action('init', [random_name]) to activate the trojan on wordpress init.
                This will be a large file. like yuge.
        - dr
                Activation keyword(can also be anything)
DRI.PHP_EOL;
    }

    function addHostMenu(): void
    {
        echo <<<H
This option allows you to add a new host to the database. This can be automated, and likely will in future versions.

Planned:
    - Have a handler script on a controlled server, that takes in preset data(defined by you.)
    - Lint information to ensure its valid, and not BS.

Currently, you need 4 things to add a new host:
    - The URI the dropper/slopshell itself resides.
    - a unique UUID, to ensure the shells are genuine and not some SOC/nuisance trying to add new info to your database.
    - The operating system.
    - Last checkin
H.PHP_EOL;


    }

    function addToolMenu(): void
    {
        echo <<<T
Add shiny new tools to the database, for use against controlled bots. These files will be added into the folder we define.
Required:
    - Tool name: what it is that you call it, or what its actual name is if you cloned it from github.
    - Target: OS the tool works on.
    - Language: programming language.
    handled by this script:
        - Base64 encoded data.
        - Encryption key
        - Encryption Tag
        - Initialization Vector
        - Cipher
        - HMAC (if applicable)
        - If the tool is encrypted.
T.PHP_EOL;


    }

    function generateCertMenu(): void
    {
        echo <<<CR
Still working the kinks out on this.
CR.PHP_EOL;

    }

    function validateHost(): void
    {
        echo <<<VH
Validate host is still owned by us. Either using all hosts entered in the database, or by specific hosts.
Params:
    - Batch (boolean)
        If batch is false:
            - Target Host
VH;


    }

    function grabToolsMenu(): void
    {
        echo <<<GT
Grab tools stored in the database.
GT;

    }

    function grabProxyMenu(): void
    {
        echo <<<GPM
Grab all proxies in the database, rotate through each one.

defaults to tor, and will only set to tor.
GPM;

    }

    function resetProxyMenu(): void
    {
        echo <<<RP
Reset the proxy, defaults to tor.
RP;


    }

    function commandMenu(): void
    {
        echo <<<CMM
execute commands on the target host.
CMM;

        
    }

    function reverseConnectionsMenu(): void
    {
        echo <<<RCM
Establish a reverse shell on the target host.
RCM;

    }

    function enumSystemMenu(): void
    {
        echo <<<ESM
Enumerate the system and available commands on the target host. 

Use the genereic tool supplied with this C2, or develop your own.
ESM;

    }

    function goodBye(): array
    {
        $a = explode("\n", "
                            ░░▄███▄███▄
                            ░░█████████
                            ░░▒▀█████▀░
                            ░░▒░░▀█▀
                            ░░▒░░█░
                            ░░▒░█
                            ░░░█
                            ░░█░░░░███████
                            ░██░░░██▓▓███▓██▒
                            ██░░░█▓▓▓▓▓▓▓█▓████
                            ██░░██▓▓▓(◐)▓█▓█▓█
                            ███▓▓▓█▓▓▓▓▓█▓█▓▓▓▓█
                            ▀██▓▓█░██▓▓▓▓██▓▓▓▓▓█
                            ░▀██▀░░█▓▓▓▓▓▓▓▓▓▓▓▓▓█
                            ░░░░▒░░░█▓▓▓▓▓█▓▓▓▓▓▓█
                            ░░░░▒░░░█▓▓▓▓█▓█▓▓▓▓▓█
                            ░▒░░▒░░░█▓▓▓█▓▓▓█▓▓▓▓█
                            ░▒░░▒░░░█▓▓▓█░░░█▓▓▓█
                            ░▒░░▒░░██▓██░░░██▓▓██
                            ████████████████████████
                            █▄─▄███─▄▄─█▄─█─▄█▄─▄▄─█
                            ██─██▀█─██─██─█─███─▄█▀█
                            ▀▄▄▄▄▄▀▄▄▄▄▀▀▄▄▄▀▀▄▄▄▄▄▀

");
        return $a;
    }
}