<?php

require 'library/Clive.php';

$clive = new Clive(array(
    'template' => 'mustache'
));

$clive->addRoute('GET', '/', function($request) {
    var_dump($request->getAllParams());
});

$clive->addRoute('GET', '/:name', function($request) {
    print 'Name is: ' . $request->getParam('name');
});

$clive->addRoute('DELETE', '/', function() {
    print 'Delete route called';
});

$clive->addRoute('PUT', '/', function() {
    print 'Put route called';
});

$clive->addRoute('POST', '/', function($request) {
    print 'Post route called';
    var_dump($request->getAllParams());
});

$clive->addRoute('GET', '/blog/:year/:month/:day/', 
    function($request) {
        var_dump($request->getAllParams());
    }
);

$clive->run();
