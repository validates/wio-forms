<?php
namespace WioForms\Service;

class DataRepositoryService
{
    public $wioForms;
    public $formStruct;


    function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;
    }


    /*
    this function collect methods of getting Foreign Data Repository
    */
    private function prepareDataRepositories(){}

    /*
    this function runs mothod of getting Foreign Data Repository and fills "Data" field
    */
    private function getForeignDataRepository($dataRepositoryName){}

    /*
    this function collect Foreign Functions
    */
    private function prepareFunctionRepositories(){}


}
