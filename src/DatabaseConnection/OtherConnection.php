<?php

namespace WioForms\DatabaseConnection;

class OtherConnection extends AbstractDatabaseConnection
{
    private $isConnected;
    private $connectionData;

    public function __construct($connectionData)
    {
        $this->isConnected = false;
        $this->connectionData = $connectionData;
    }

    public function connect()
    {
        $this->isConnected = true;
    }

    public function insert($queryData)
    {
        if (!$this->isConnected) {
            $this->connect();
        }
    }

    public function update($queryData)
    {
        if (!$this->isConnected) {
            $this->connect();
        }
    }

    public function select($queryData)
    {
        if (!$this->isConnected) {
            $this->connect();
        }
    }

    public function selectOne($queryData)
    {
        if (!$this->isConnected) {
            $this->connect();
        }
    }
}
