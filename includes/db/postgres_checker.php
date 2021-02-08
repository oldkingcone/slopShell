<?php
# i am still working this. but will show its intent none the less.

class postgres_checker
{
    public $connectionString;
    public $er;

    function createDB(){
        $this->connectionString = pg_connect("host=localhost port=5432 user=postgres dbname=sloppy_bots");
        try {
            pg_exec($this->connectionString, "CREATE DATABASE sloppy_bots");
            pg_exec($this->connectionString, "GRANT SELECT, INSERT ON ALL TABLES IN SCHEMA \"public\" TO sloppy_main");
        }catch (Exception $ex){
            return false;
        }
        try {
            pg_exec($this->connectionString, "CREATE TABLE IF NOT EXISTS sloppy_bots(id SERIAL NOT NULL constraint sloppy_bots_main_pkey primary key,datetime TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, rhost TEXT, uri TEXT, os_flavor TEXT NOT NULL DEFAULT '-')");
            return true;
        }catch (Exception $e){
            $this->er = $e;
            echo("Exception occured: ". $this->er);
            return false;
        }
    }

    function checkDB(){
        # the idea for this, is to check to see if the db returns 1 record. if it does not, we will call create db, or start db.
        $this->connectionString = pg_connect("host=localhost port=5432 user=sloppy_main dbname=sloppy_bots");
        try {
            pg_exec($this->connectionString, 'SELECT id FROM sloppy_bots WHERE id IS 1');
        }catch (Exception $ee) {

        }
    }
}