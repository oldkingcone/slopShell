<?php

include "./slopSqlite.php";

$sqliteDB = new slopSqlite("./sqlite3_repo/slop.sqlite", $encryptionKey = 'test123');
$sqliteDB->checkDataBase("local");
$sqliteDB->verifySchema();