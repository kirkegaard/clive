#Clive, the douchebag framework
![Clive, the douchebag framework](https://github.com/ranza/clive/raw/master/clive.jpg)

Yet another Sinatra.rb clone for PHP5.3

##Usage

All you need to do is include the Clive library and add routes via the `addRoute` method. The method takes a request type, a route, and a function. Last you need to run the library and thats it.

###Howto

    require 'library/Clive.php';
    
    $clive = new Clive();
    
    $clive->addRoute('GET', '/', function() {
        print 'A GET request for / was called';
    });
    
    $clive->addRoute('GET', '/:name', function($request) {
        print '/ was called with ' . $request->getParam('name');
    });
    
    $clive->run();

##Todo

 * Add templating like phtml, mustache and so on.
 * Better param handeling
 * Better routing to support regex, *, and defaults (http://kenai.com/projects/cms-codeigniter-rds/sources/cms-in-codeigniter/content/system/libraries/Router.php?rev=36)

##Changelog

###Version 0.0.3 - 8th February

 * Added support for basePaths
 * Some basic templating stuff
 * Throws exception on 404 (will be changed to a template later on)

###Version 0.0.2 - 3th January

 * Added proper routing. It works! YEY

###Version 0.0.1 - 25th December

 * Initial import. Nothing works yet
