<?php
# i am still working this. but will show its intent none the less.

class postgres_checker
{
    public $connectionString;
    public $er;

    public function createDB(){
        $this->connectionString = pg_connect("host=localhost port=5432 user=postgres dbname=sloppy_bots");
        try {
            pg_exec($this->connectionString, "CREATE DATABASE sloppy_bots");
        }catch (Exception $ex){

        }
        try {
            # for obvious security, removing select role from sloppy_main.
            # will create a function in this class to handle query of records and to return that record.
            pg_exec($this->connectionString, "CREATE TABLE IF NOT EXISTS sloppy_bots_main(id SERIAL NOT NULL constraint sloppy_bots_main_pkey primary key,datetime TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, rhost TEXT, uri TEXT, os_flavor TEXT NOT NULL DEFAULT '-', check_in INTEGER NOT NULL default 0)");
            pg_exec($this->connectionString, "GRANT INSERT,UPDATE ON TABLE sloppy_bots_main TO sloppy_bot");
            pg_exec($this->connectionString, "GRANT ALL ON ALL TABLES IN SCHEMA public TO postgres");
            return true;
        }catch (Exception $e){
            $this->er = $e;
            echo("Exception occured: ". $this->er);
            return false;
        }
    }

    public function checkDB(){
        # the idea for this, is to check to see if the db returns 1 record. if it does not, we will call create db, or start db.
        $this->connectionString = pg_connect("host=localhost port=5432 user=postgres dbname=sloppy_bot");
        try {
            pg_exec($this->connectionString, 'SELECT id FROM sloppy_bots_main WHERE id IS 1');
            return true;
        }catch (Exception $ee) {
            return false;
        }
    }

    public function getRecord($ip){
        if (!empty($ip) && is_string($ip)){
            $con = pg_connect("host=localhost port=5432 user=postgres dbname=sloppy_bot");
            $row = pg_exec($con, sprintf("SELECT rhost FROM sloppy_bots_main WHERE rhost like '%s'", pg_escape_string($ip)));
            if (!empty($row)) {
                return pg_fetch_row($row);
            }
        }
    }
}