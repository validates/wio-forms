<?php

namespace WioForms\DatabaseConnection;

abstract class AbstractDatabaseConnection
{
    /*
    functions used by DatabaseStore connection
    every connection can have own set of functions
    */
    abstract public function __construct($connectionData);

    abstract public function insert($queryData);

    abstract public function update($queryData);

    abstract public function select($queryData);

    abstract public function selectOne($queryData);
}
