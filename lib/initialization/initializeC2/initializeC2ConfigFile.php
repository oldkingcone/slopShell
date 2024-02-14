<?php

namespace initialization\initializeC2;

use AllowDynamicProperties;
use config\defaultConfig;

/**
 * @property array $iniFile
 * @property string $confirm
 * @property bool $debug
 * @property string $home
 * @property string $target_ini
 */
#[AllowDynamicProperties] class initializeC2ConfigFile
{
    /**
     * @throws \Exception
     */
    function __construct(string $home, bool $debug)
    {
        $this->home = $home;
        $this->debug = $debug;
        $this->target_ini = $this->home."/lib/config/sloppyConfig.ini";
        if (is_file($this->target_ini)) {
            $this->iniFile = parse_ini_file($this->target_ini);
        }else {
            throw new \Exception("Unable to locate config file. Please ensure the file exists. at: {$this->target_ini}");
        }
        if ($this->debug){
            var_dump($this->iniFile);
            echo PHP_EOL."Home: ".$this->home.PHP_EOL;
        }
        if ($this->iniFile['use_sql'] === "") {
            echo "are we using \033[0;32msqlite\033[0m or \033[0;32mpgsql\033[0m?" . PHP_EOL;
            $this->confirm = strtolower(trim(readline("-> ")));
            $this->checkSql();
        }else{
            define("SQL_USE", $this->iniFile['use_sql']);
        }
    }

    private function checkSql(): void
    {
        if ($this->iniFile['use_sql'] === ""){
            $writeIni = file($this->target_ini);
            if (!isset($confirm)){
                die("This cannot be null, I need to know. Aborting.");
            }
            if ($this->debug) {
                echo sprintf("use_sql=%s", $this->confirm) . PHP_EOL;
            }
            if (!is_bool($this->confirm) and !is_null($this->confirm)) {
                $writeIni[1] = sprintf("use_sql=%s\n", $this->confirm);
            }
            file_put_contents($this->target_ini, $writeIni);
            define("SQL_USE", $this->iniFile['use_sql']);
        }
    }

    public function resetSql(): void
    {
        $resetIniFile = file($this->target_ini);
        $resetIniFile[1] = "";
        file_put_contents($this->target_ini, $resetIniFile);
    }

}