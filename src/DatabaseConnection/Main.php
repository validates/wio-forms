<?php
namespace WioForms\DatabaseConnection;

use WioForms\DatabaseConnection\AbstractDatabaseConnection;

class Main extends AbstractDatabaseConnection
{
    private $isConnected;
    private $connectionData;


    function __construct( $connectionData ){
        $this->isConnected = false;
        $this->connectionData = $connectionData;

    }

    function __desctruct(  ){
        if ($this->isConnected)
        {
            $this->Disconnect();
        }
    }

    function Connect(){

        $this->isConnected = true;
    }

    function Disconnect(){

    }

    function Save( $queryTable )
    {
        if ( !$this->isConnected)
        {
            $this->Connect();
        }
    }

    function Update( $queryTable )
    {
        if ( !$this->isConnected)
        {
            $this->Connect();
        }

    }

    function Select( $queryTable )
    {
        if ( !$this->isConnected)
        {
            $this->Connect();
        }


    }

    function SelectOne( $queryTable )
    {
        if ( !$this->isConnected)
        {
            $this->Connect();
        }

        if ( $queryTable['table'] == 'WioForms_formStruct' )
        {
            $e = file_get_contents('exampleFormStruct.json');
            return ['dataStruct'=> $e];
        }

    }


}

?>
