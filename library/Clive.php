<?php

class Clive {

    /**
     * Current version
     */
    const VERSION = '0.0.1';

    /**
     * The requested path
     *
     * @var string
     */
    protected $_request = '';

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
     * Hold our params
     * @var array
     */
    protected $_params = array();

    public function __construct($options = array())
    {
        $this->setRequest($_SERVER['REQUEST_URI']);
        $this->setMethod($_SERVER['REQUEST_METHOD']);
    }

    public function call()
    {}
    
    public function route()
    {
        $method  = $this->getMethod();
        $request = $this->getRequest();

        foreach($this->_routes[$method] as $route => $function) {
            preg_match_all('/:([a-zA-Z0-9]+)/', $route, $paramNames);
            $regex_route = preg_replace('/(:[a-zA-Z0-9]+)/', "([a-zA-Z0-9]+)", $route);
            $paramNames = $paramNames[1];

            if(preg_match("~^$regex_route$~", $request, $paramValues)){
                array_shift($paramValues);
                if(isset($paramValues)) {
                    foreach($paramValues as $key => $value) {
                        $this->setParam($paramNames[$key], $value);
                    }
                }

                $function($this);
            }
        }
    }

    public function render()
    {}

    /**
     * Add a route to the application
     * 
     * @param string $method
     * @param string $route
     * @param function $function
     * @return object
     */
    public function addRoute($method, $route, $function)
    {
        $this->_routes[strtoupper($method)][$route] = $function;
        return $this;
    }

    /**
     * Returns a parameter from the request
     *
     * @param string $param
     * @param string $default
     * @return string
     */
    public function getParam($param, $default)
    {
        if(!isset($this->_params[$param])) {
            return $default;
        }
        return $this->_params[$param];
    }

    /**
     * Sets a parameter value
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

    public function setRequest($request)
    {
        $this->_request = $request;
        return $this;
    }

    public function getRequest()
    {
        return $this->_request;
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

    public function getMethod()
    {
        return $this->_method;
    }

    public function run()
    {
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
