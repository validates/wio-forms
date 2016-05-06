<?php
namespace WioForms\DatabaseConnection;

class OtherConnection extends AbstractDatabaseConnection
{
    private $isConnected;
    private $connectionData;


    function __construct($connectionData)
    {
        $this->isConnected = false;
        $this->connectionData = $connectionData;
    }

    function connect()
    {
        $this->isConnected = true;
    }

    function insert($queryData)
    {
        if (!$this->isConnected)
        {
            $this->connect();
        }
    }

    function update($queryData)
    {
        if (!$this->isConnected)
        {
            $this->connect();
        }
    }

    function select($queryData)
    {
        if (!$this->isConnected)
        {
            $this->connect();
        }
    }

    function selectOne($queryData)
    {
        if (!$this->isConnected)
        {
            $this->connect();
        }
    }


}
