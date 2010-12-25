<?php

require 'library/Clive.php';

$c = new Clive();

$c->addRoute('GET', '/', function() {
    print 'Get route called';
});

$c->addRoute('DELETE', '/', function() {
    print 'Delete route called';
});

$c->addRoute('PUT', '/', function() {
    print 'Put route called';
});

$c->addRoute('POST', '/', function() {
    print 'Post route called';
});

$c->addRoute('GET', '/blog/:year/:month/:day/', function() {
    print 'Year param is : ' . $c->getParam('year', 'No year found') . '<br>';
    print 'Month param is : ' . $c->getParam('month', 'No month found') . '<br>';
    print 'Day param is : ' . $c->getParam('day', 'No day found');
});

var_dump($c);
