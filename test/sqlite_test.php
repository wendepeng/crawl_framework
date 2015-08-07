<?php
include(__DIR__.'/../libs/sqlite.php');

$sqlite = new SQLite('/tmp/test.sqlite');
//$sqlite->query('create table zh_option(id integer PRIMARY KEY AUTOINCREMENT,name TEXT,value TEXT);');
$sqlite->query('insert into zh_option(name,value)values("hah","quba");');


