<?php
namespace WioForms\DatabaseConnection;


abstract class AbstractDatabaseConnection
{
    /*
    functions used by DatabaseStore connection
    every connection can have own set of functions
    */
    abstract function __construct($connectionData);

    abstract function insert($queryData);
    abstract function update($queryData);
    abstract function select($queryData);
    abstract function selectOne($queryData);


}
