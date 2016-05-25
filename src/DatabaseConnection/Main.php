<?php
namespace WioForms\DatabaseConnection;

class Main extends AbstractDatabaseConnection
{


    private $connectionData;

    // Pixie\QueryBuildr
    private $QB;

    function __construct($connectionData)
    {
        global $queryBuilder;
        $this->QB = $queryBuilder;

        $this->connectionData = $connectionData;
    }



    function insert($queryData)
    {
        $query = $this->QB->table( $queryData['table'] );

        return $query->insert( $queryData['insert'] );
    }

    function update($queryData)
    {
        $query = $this->QB->table( $queryData['table'] );
        foreach ($queryData['where'] as $column => $value)
        {
            $query->where($column, $value);
        }

        return $query->update( $queryData['update'] );
    }

    function select($queryData)
    {


    }

    function selectOne($queryData)
    {
        if ($queryData['table'] == 'wio_form_struct')
        {
            $example_file = file_get_contents(__DIR__.'/example'.$queryData['where']['formStructId'].'.json');
            return ['dataStruct'=> $example_file];
        }
    }


}
