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

    function save($queryTable)
    {
        if (!$this->isConnected)
        {
            $this->connect();
        }
    }

    function update($queryTable)
    {
        if (!$this->isConnected)
        {
            $this->connect();
        }
    }

    function select($queryTable)
    {
        if (!$this->isConnected)
        {
            $this->connect();
        }
    }

    function selectOne($queryTable)
    {
        if (!$this->isConnected)
        {
            $this->connect();
        }
    }


}
