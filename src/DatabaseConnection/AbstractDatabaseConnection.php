<?php
namespace WioForms\DatabaseConnection;


abstract class AbstractDatabaseConnection
{
    /*
    functions used by DatabaseStore connection
    every connection can have own set of functions
    */
    abstract function __construct($connectionData);

    abstract function connect();
    abstract function save($queryTable);
    abstract function update($queryTable);
    abstract function select($queryTable);
    abstract function selectOne($queryTable);


}
