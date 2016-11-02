<?php

namespace WioForms\DatabaseConnection;

class Main extends AbstractDatabaseConnection
{
    private $connectionData;

    // Pixie\QueryBuildr
    private $QB;

    public function __construct($connectionData)
    {
        global $queryBuilder;
        $this->QB = $queryBuilder;

        $this->connectionData = $connectionData;
    }

    public function insert($queryData)
    {
        $query = $this->QB->table($queryData['table']);
        return $query->insert($queryData['insert']);
    }

    public function update($queryData)
    {
        $query = $this->QB->table($queryData['table']);
        foreach ($queryData['where'] as $column => $value) {
            $query->where($column, $value);
        }

        return $query->update($queryData['update']);
    }

    public function upsert($queryData)
    {
        $query = $this->QB->table($queryData['table']);
        foreach ($queryData['matchers'] as $column => $value) {
            $query->where($column, $value);
        }

        $result = $query->first();

        if ($result !== null) {
            $query = $this->QB->table($queryData['table']);
            foreach ($queryData['matchers'] as $column => $value) {
                $query->where($column, $value);
            }
            $res = $query->update($queryData['upsert']);

            return isset($result->id) ? $result->id : null;
        } else {
            foreach ($queryData['matchers'] as $column => $value) {
                $queryData['upsert'][$column] = $value;
            }
            $query = $this->QB->table($queryData['table']);
            return $query->insert($queryData['upsert']);
        }
    }

    public function select($queryData)
    {
    }

    public function selectOne($queryData)
    {
        if ($queryData['table'] == 'wio_form_struct') {
            if (is_resource($queryData['where']['formStructId'])) {
                $example_file = stream_get_contents($queryData['where']['formStructId']);
                fclose($queryData['where']['formStructId']);
            } else {
                $example_file = file_get_contents(__DIR__.'/example'.$queryData['where']['formStructId'].'.json');
            }

            return ['dataStruct' => $example_file];
        }
    }

    /**
     * TODO
     * trzeba zrobic analogiczny mechanizm jak w przypadku metody upsert(),
     * czyli dodać matchery po których najpierw będzie wyciągany rekord w celu 
     * zwrócenia ID rekordu (update/onDuplicateKeyUpdate zwraca nulla)
     */
    public function onDuplicateKeyUpdate($queryData)
    {
        $this->QB->table($queryData['table'])
            ->onDuplicateKeyUpdate($queryData['updateData'])
            ->insert($queryData['insertData']);
    }
}
