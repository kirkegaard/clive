<?php

require '../../library/Clive.php';
require 'library/Lists.php';

$clive = new Clive(array(
    'basePath'     => '/examples/lists/',
    'layout'       => 'layout.phtml',
    'templatePath' => 'templates',
));

$lists = new Lists(array(
    'dsn' => 'sqlite:data/database.db'
));

$clive->addRoute('GET', '/', function($req) use ($lists) {
    $req->setParam('lists', $lists->getLists());
}, 'index.phtml');

$clive->addRoute('GET', '/show/:id', function($req) {
    print $req->getParam('id');
}, 'show.phtml');





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

    $db = new PDO();
    $db->exec($schema);
});

$clive->run();
