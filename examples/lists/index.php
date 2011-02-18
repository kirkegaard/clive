<?php

require '../../library/Clive.php';

$database = 'data/db.sdb';

$clive = new Clive(array(
    'basePath'     => '/examples/lists/',
    'layout'       => 'layout.phtml',
    'templatePath' => 'templates',
));


$clive->addRoute('GET', '/', function($req) {
    print 'slash!';
});


$clive->addRoute('GET', '/list', function($req) {
    var_dump('view lists');
});

$clive->addRoute('POST', '/list/add', function($req) {
    var_dump('add list');
});

$clive->addRoute('GET', '/list/edit/:id', function($req) {
    var_dump('edit list');
});

$clive->addRoute('PUT', '/list/update/:id', function($req) {
    var_dump('updating list...');
});

$clive->addRoute('DELETE', '/list/delete/:id', function($req) {
    var_dump('delete list');
});






$clive->addRoute('GET', '/install', function($req) {
    $schema = <<<SQL
CREATE TABLE "lists" (
"id" INTEGER PRIMARY KEY AUTOINCREMENT ,
"name" TEXT ,
"slug" TEXT UNIQUE ON CONFLICT ABORT );
CREATE TABLE "items" (
"id" INTEGER PRIMARY KEY AUTOINCREMENT ,
"list_id" INTEGER NOT NULL ,
"value" INTEGER ,
"added" INTEGER );
SQL;

    $db = new PDO('sqlite:data/database.db');
    $db->exec($schema);
});

$clive->run();
