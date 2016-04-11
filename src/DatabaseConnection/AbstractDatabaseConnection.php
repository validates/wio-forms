<?php
namespace WioForms\DatabaseConnection;


abstract class AbstractDatabaseConnection
{
    /*
    functions used by DatabaseStore connection
    every connection can have own set of functions
    */
    abstract function __construct( $connectionData );

    abstract function Connect();
    abstract function Save( $queryTable );
    abstract function Update( $queryTable );
    abstract function Select( $queryTable );
    abstract function SelectOne( $queryTable );


}

?>
