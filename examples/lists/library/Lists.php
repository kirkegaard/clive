<?php

class Lists {

    protected $_db = null;

    public function __construct(array $options)
    {
        if(isset($options['dsn'])) {
            $this->_db = $this->_connect($options['dsn']);
        }
    }

    protected function _connect($dsn)
    {
        return new PDO($dsn);
    }

    public function getDb()
    {
        if($this->_db === null) {
            return false;
        }
        return $this->_db;
    }

    public function getLists()
    {
        $db    = $this->getDb();
        $query = 'SELECT id, name, slug FROM lists';
        $sth = $db->prepare($query);
        if(!$sth) {
            return false;
        }

        $sth->execute();
        return $sth->fetchAll();
    }



}
