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
        $this->setParams($params = array_merge($_POST, $_GET));
    }

    public function call()
    {}
    
    /**
     * Parses the request and fires the function
     * Some code borrowed off blaines Framework code
     * @url https://github.com/blaines/Framework/blob/master/lib/framework.php
     *
     * @todo fix the regex to match * as well
     * @todo add a notFound method call for when no route is found
     */
    public function run()
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
    public function getParam($param, $default = '')
    {
        if(!isset($this->_params[$param])) {
            return $default;
        }
        return $this->_params[$param];
    }

    public function getAllParams()
    {
        return $this->_params;
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

    public function setRequest($request)
    {
        // Remove GET params from the url
        // We might want to use parse_url() instead
        $req = preg_split('/[?]/', $request);
        $this->_request = $req[0];
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
