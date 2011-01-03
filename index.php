<?php

require 'library/Clive.php';

$c = new Clive();

$c->addRoute('GET', '/', function($request) {
    var_dump($request->getAllParams());
});

$c->addRoute('GET', '/:name', function($request) {
    print 'Name is: ' . $request->getParam('name');
});

$c->addRoute('DELETE', '/', function() {
    print 'Delete route called';
});

$c->addRoute('PUT', '/', function() {
    print 'Put route called';
});

$c->addRoute('POST', '/', function($request) {
    print 'Post route called';
    var_dump($request->getAllParams());
});

$c->addRoute('GET', '/blog/:year/:month/:day/', function($request) {
    var_dump($request->getAllParams());
});

$c->run();
