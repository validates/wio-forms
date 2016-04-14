<?php
namespace WioForms\Service;

class DataRepositoryService
{
    public $WioForms;
    public $formStruct;


    function __construct( $WioFormsObiect ){
        $this->WioForms = $WioFormsObiect;
    }


    /*
    this function collect methods of getting Foreign Data Repository
    */
    private function prepareDataRepositories( ){}

    /*
    this function runs mothod of getting Foreign Data Repository and fills "Data" field
    */
    private function getForeignDataRepository( $dataRepositoryName ){}

    /*
    this function collect Foreign Functions
    */
    private function prepareFunctionRepositories( ){}





}

?>
