<?php

class Clive {

    /**
     * Current version
     */
    const VERSION = '0.0.2';

    /**
     * The requested path
     *
     * @var string
     */
    protected $_request = array();

    /**
     * Method of the request
     *
     * @var string
     */
    protected $_method = '';

    /**
     * Response status code
     *
     * @var integer
     */
    protected $_status = 200;

    /**
     * Holds the requests headers
     *
     * @var array
     */
    protected $_headers = array();

    /**
     * Options
     *
     * @var array
     */
    protected $_options = array();

    /**
     * Holds our roots
     *
     * @var array
     */
    protected $_routes = array(
        'GET'    => array(),
        'POST'   => array(),
        'PUT'    => array(),
        'DELETE' => array(),
    );

    /**
     * View data
     */
    protected $_viewOptions = array();

    /**
     * Holds GET, POST and url variables
     * @var array
     */
    protected $_params = array();

    /**
     * Setup our application.
     */
    public function __construct($options = array())
    {
        $this->setOptions($options);

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if($basePath = $this->getOption('basePath')) {
            $uri = str_replace(rtrim($basePath, '/'), '', $uri);
            $uri = empty($uri) ? '/' : $uri;
        }

        $this->setRequest('uri', rtrim($uri, '/'));
        $this->setRequest('found', false);
        $this->setMethod($_SERVER['REQUEST_METHOD']);
        $this->setParams($params = array_merge($_POST, $_GET));
    }

    /**
     * Parses the request and fires the function
     * Some code borrowed off blaines Framework code
     * @url https://github.com/blaines/Framework/blob/master/lib/framework.php
     *
     * @todo fix the regex to match * as well
     * @todo add a notFound method call for when no route is found
     * @todo maybe not run `$function($this)` at this point?
     *
     * @return string
     */
    public function route()
    {
        $method  = $this->getMethod();
        $request = $this->getRequest('uri');

        foreach($this->_routes[$method] as $route => $function) {
            preg_match_all('/:([a-zA-Z0-9]+)/', $route, $paramNames);
            $regex_route = preg_replace('/(:[a-zA-Z0-9]+)/', "([a-zA-Z0-9]+)", $route);
            $paramNames = $paramNames[1];

            if(preg_match("~^$regex_route$~", $request, $paramValues)){
                $this->setRequest('found', true);
                array_shift($paramValues);
                if(isset($paramValues)) {
                    foreach($paramValues as $key => $value) {
                        $this->setParam($paramNames[$key], $value);
                    }
                }

                $this->setRequest('route', $route);
                $function($this);
            }
        }

        if(!$found = $this->getRequest('found')) {
            $this->_notfound();
        }
    }

    protected function _notfound()
    {
        throw new Exception('No route found');
    }

    /**
     * RUN!
     */
    public function run()
    {
        $this->route();
        $route = $this->getRequest('route');

        if($view = $this->getView($route)) {
            $this->setParam('content', $this->render($view));
        }

        if($layout = $this->getOption('layout')) {
            $output = $this->render($layout);
        }

        print $output;
    }

    /**
     * Renders the view
     *
     * @param string $view
     */
    public function render($view)
    {
        $template = $this->getOption('templatePath') . '/' . ltrim($view, '/');

        if(!file_exists($template)) {
            throw new Exception('Couldnt find template: ' . $template);
        }

        extract($this->getAllParams());

        ob_start();
        require $template;

        return ob_get_clean();
    }

    /**
     * Sets the view file for the method
     *
     * @param string $view
     * @return object
     */
    public function setView($view, $route)
    {
        $this->_viewOptions[$route] = $view;
        return $this;
    }

    public function getView($route)
    {
        if(!isset($this->_viewOptions[$route])) {
            return null;
        }
        return $this->_viewOptions[$route];
    }

    /**
     * Add a route to the application
     *
     * @param string $method
     * @param string $route
     * @param function $function
     * @return object
     */
    public function addRoute($method, $route, $function, $view = null)
    {
        $this->_routes[strtoupper($method)][$route] = $function;
        if($view !== null) {
            $this->setView($view, $route);
        }
        return $this;
    }

    /**
     * Sets the options for later
     *
     * @param array $options
     * @return object
     */
    public function setOptions($options = array())
    {
        $this->_options = array_merge($this->_options, $options);
        return $this;
    }

    /**
     * Sets an option and its value
     *
     * @param string $name
     * @param string $value
     * @return object
     */
    public function setOption($name, $value)
    {
        $this->_options[$name] = $value;
        return $this;
    }

    /**
     * returns an option from the options array
     *
     * @param string $option
     * @return mixed
     */
    public function getOption($option)
    {
        if(!isset($this->_options[$option])) {
            return null;
        }
        return $this->_options[$option];
    }

    /**
     * Returns a parameter from the request
     *
     * @param string $param
     * @param string $default
     * @return string
     */
    public function getParam($param, $default = '')
    {
        if(!isset($this->_params[$param])) {
            return $default;
        }
        return $this->_params[$param];
    }

    /**
     * Returns all the params in an array
     *
     * @return array
     */
    public function getAllParams()
    {
        return $this->_params;
    }

    /**
     * Sets a param and its value
     *
     * @param string $paramName
     * @param string $paramValue
     * @return object
     */
    public function setParam($paramName, $paramValue)
    {
        $this->_params[$paramName] = $paramValue;
        return $this;
    }

    /**
     * Same as setParam but takes an array as an argument
     *
     * @param array $params
     * @return object
     */
    public function setParams($params = array())
    {
        foreach($params as $key => $value) {
            $this->setParam($key, $value);
        }
        return $this;
    }

    /**
     * Splits up the request and takes the parts we want to save
     *
     * @param string $request
     * @return object
     */
    public function setRequest($key, $value)
    {
        $this->_request[$key] = $value;
        return $this;
    }

    /**
     * Returns the request
     *
     * @return string
     */
    public function getRequest($option)
    {
        if(!isset($this->_request[$option])) {
            return null;
        }
        return $this->_request[$option];
    }

    /**
     * Sets the requests method
     *
     * @param string $method
     * @return object
     */
    public function setMethod($method)
    {
        $this->_method = strtoupper($method);
        return $this;
    }

    /**
     * Returns the method type. 
     * GET, POST, PUT, DELETE
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * Returns the current version of Clive
     *
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }

}
