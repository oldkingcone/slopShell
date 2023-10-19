<?php

namespace config;


/**
 * @property string $slop_home
 * @property string $allowed_chars
 * @property string $pg_host
 * @property string $pg_user
 * @property string $pg_pass
 * @property string $pg_table
 * @property string $sqlite_db
 * @property string $sqlite_table
 * @property boolean $use_tor
 * @property string $tor
 * @property string $alpha
 */
class defaultConfig
{
    /**
     * @throws \Exception
     */
    function __construct()
    {
        $this->slop_home = "/opt/slopshell";
        if (!is_dir($this->slop_home) && is_writable("/opt/")){
            mkdir($this->slop_home);
        }elseif (!is_dir($this->slop_home) && !is_writable("/opt/")){
            throw new \Exception(PHP_EOL."\033[0;31m[ !! ] CANNOT CREATE NEEDED DIRECTORY. PLEASE ENSURE {$this->slop_home} IS CREATED WITH SUDO, AND WRITABLE BY THE CURRENT USER. [ !! ]\033[0m".PHP_EOL);
        }
        $this->allowed_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890-=_+}{]['\"/?.>,<!@#$%^&*()";
        $this->alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $this->pg_host = "127.0.0.1";
        $this->pg_user = "sloppy_c2";
        $this->pg_pass = "";
        $this->pg_table = "sloppy_ctew";
        $this->sqlite_db = "{$this->slop_home}/slopdb.sqlite";
        $this->sqlite_table = "sloppy_ctew";
        $this->use_tor = true;
        if (!is_null(getenv("USE_TOR"))){
            $this->tor = getenv("USE_TOR");
        }else{
            $this->tor = "socks5h://127.0.0.1:9050";
        }
    }

    /**
     * @return string
     */
    public function getAllowedChars(): string
    {
        return $this->allowed_chars;
    }

    public function exportConfigConstants(): array
    {
        return [
            "slop_home" => $this->slop_home,
            "pg_presets" => [
                "pg_host" => $this->pg_host,
                "pg_user" => $this->pg_user,
                "pg_port" => "5432",
                "pg_table" => $this->pg_table,
                "pg_pass" => $this->pg_pass,
            ],
            "sqlite_presets" => [
                "sqlite_table" => $this->sqlite_table,
                "sqlite_db" => $this->sqlite_db,
            ],
            "tor" => $this->tor,
            "alpha_chars" => $this->alpha
        ];
    }

}