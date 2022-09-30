<?php
error_reporting(E_ERROR | E_PARSE | E_WARNING);
set_include_path(get_include_path() . PATH_SEPARATOR . getcwd() . "/includes");
if (!defined("config")){
    define("config", parse_ini_file('includes/config/sloppy_config.ini', true, 2));
}
if (!isset($_SERVER['HTTP_HOST'])){
set_include_path(get_include_path() . PATH_SEPARATOR . getcwd() . "/includes");
if (!defined("_SERVER")){
    print("\n\033[0;31mYou appear to be using the CLI version of PHP, meaning that most of the global variables needed to access information needed by this framework will not be accessible.\033[0m\nPlease take a second look at your PHP env and make sure you have the correct version.\n");
}
if (!function_exists("openssl_decrypt") && !function_exists("openssl_encrypt")){
    print("\033[0;33mOPENSSL appears to be missing.\033[0m\n");
}
if (!defined("SQL_SELECTION")){
    print("Would you prefer to use SQlite3 or PostgreSQL?\n");
    $selection = readline("(sqlite3/pgsql)->");
    switch ($selection){
        case strpos($selection, "pgsql") !== false:
            if (extension_loaded("pgsql")){
                print("\033[0;34mNeeded extentions are loaded, selecting PGSQL\033[0m\n");
                define("SQL_SELECTION", "pgsql");
            }else{
                print("\033[0;31mIt appears as though PGSQL is not enabled/installed. Please verify the needed libs are installed, and the php build is built with pgsql extentions.\033[0m\n");
            }
            break;
        case strpos($selection, "sqlite3") !== false:
        default:
            echo "\033[0;33mNo selection was made or the default was selected, using SQLITE3\033[0m\n";
            if (extension_loaded("sqlite3")){
                define("SQL_SELECTION", "sqlite3");
            }else{
                print("\033[0;31mSQLITE3 is not loaded. Please check your build of php and ensure that SQLITE3 flag is enabled at build time.\033[0m\n");
            }
            break;
    }

