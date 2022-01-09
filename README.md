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


Current VT Detection ratio (obfuscated version): 0/59 (should be final version of the obfuscated version. Might make more changes to the routine later on, but for now, behold.)

[![Virus Total](https://www.virustotal.com/gui/images/VT_search_hash.svg)](https://www.virustotal.com/gui/file/3f1c5bc158c57d1d065b02760cf7c67d271641176bfad9051e183446b328435d/detection)

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
