<?php

require 'library/Clive.php';

$clive = new Clive(array(
    'layout'     => 'layout.phtml',
    'layoutPath' => 'templates',
    'viewPath'   => 'templates',
));

$clive->addRoute('GET', '/', function($request) {
    print 'hello world';
}, 'index.phtml');

$clive->addRoute('GET', '/:name', function($request) {
    $request->render(array(
        'name' => $request->getParam('name'),
        'dataset' => array(
            'foo', 'bar', 'flaf', 'giraf'
        )
    ));
}, 'name.phtml');

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
