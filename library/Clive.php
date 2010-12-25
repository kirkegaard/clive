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

    public function call()
    {}
    
    public function route()
    {}

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
        $this->_routes[$method][$route] = $function;
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
        if(!$this->_param[$param]) {
            return $default;
        }
        return $this->_param[$param];
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
        $this->_param[$paramName] = $paramValue;
        return $this;
    }

    /**
     * Sets the requests method
     *
     * @param string $method
     * @return object
     */
    public function setMethod($method)
    {
        $this->_method = strToUpper($method);
        return $this;
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
