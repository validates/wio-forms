<?php
namespace WioForms\DatabaseConnection;

class Main extends AbstractDatabaseConnection
{

    private $connectionData;


    function __construct( $connectionData ){

        $this->connectionData = $connectionData;
    }

    function connect(){}


    function save( $queryTable )
    {
    }

    function update( $queryTable )
    {

    }

    function select( $queryTable )
    {


    }

    function selectOne( $queryTable )
    {

        if ( $queryTable['table'] == 'wio_form_struct' )
        {
            $example_file = file_get_contents('exampleFormStruct.json');
            return ['dataStruct'=> $example_file];
        }

    }


}

?>
