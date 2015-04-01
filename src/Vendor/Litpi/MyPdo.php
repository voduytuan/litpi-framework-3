<?php

namespace Litpi;

use \PDO as PDO;

class MyPdo extends PDO
{
    private $storedSQL = array();

    public function __construct($dsn, $username, $password)
    {
        parent::__construct($dsn, $username, $password);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function prepare($sql, $driverOptions = array())
    {
        $stmt = parent::prepare($sql);
        return $stmt;
    }

    public function query($sql, $params = array())
    {
        //profiling the query
        if (!is_null($GLOBALS['pqpProfiler'])) {
            $start = $GLOBALS['pqpProfiler']->querydebug->getTime();
        }

        $stmt = $this->prepare($sql);
        $stmt->execute($params);

        //profiling the query
        if (!is_null($GLOBALS['pqpProfiler'])) {
            $GLOBALS['pqpProfiler']->querydebug->logQuery($sql, $params, $start);
        }

        return $stmt;
    }
}
