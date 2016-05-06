<?php
namespace WioForms\Service;

class DatabaseService
{
    public $wioForms;
    public $settings;
    public $formStruct;

    public $connections;

    function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;

        $this->connections = [];
    }



    /*
    this function get FormSettings and bind in to out object
    its used by all public method
    its used directly in showForm()
    in showEntry() and getEntryAsArray() we get $formDataStructID from database
    in preSubmit(), submit() and update() we get $formDataStuctID from POST
    */
    public function getFormDataStruct($formDataStructId)
    {

        $databaseQuery =
        [
            'table' => 'wio_form_struct',
            'where' => ['formStructId' => $formDataStructId ]
        ];

        $queryResult = $this->connections['Main']->selectOne($databaseQuery);

        if ($queryResult == 'false')
        {
            $this->wioForms->errorLog->errorLog('Cannot get DataStruct from Database.');
            return false;
        }

        $this->wioForms->formStruct = json_decode($queryResult['dataStruct'], true);
        if (json_last_error()!= JSON_ERROR_NONE)
        {
            $this->wioForms->errorLog->errorLog('Problem with JSON validation of formStruct file.');
            return false;
        }
        return true;
    }


    public function setConnections()
    {
        foreach ($this->wioForms->settings['DatabaseConnections'] as $DBconn_Name => $DBconn_Data)
        {
            $className = $this->wioForms->classFinderService->checkName('DatabaseConnection',$DBconn_Name);
            if ($className)
            {
                $this->connections[ $DBconn_Name ] = new $className($DBconn_Data);
            }
            else
            {
                return false;
            }
        }
        return true;
    }

}
