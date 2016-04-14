<?php
namespace WioForms\Service;

class ValidatorService
{
    public $WioForms;
    public $formStruct;

    # Holds data to Validate
    public $entryData;

    function __construct( $WioFormsObiect ){
        $this->WioForms = $WioFormsObiect;
    }

    /*
    runs by preSubmit(), submit(), update()
    checks all fields and all containers for validation errors
    */
    public function validateForm( $entryData ){
        $this->formStruct = $this->WioForms->formStruct;
        $this->entryData = $entryData;

        foreach($this->formStruct['Fields'] as $fieldName => $field)
            $this->validateField( $fieldName );

        foreach($this->formStruct['Containers'] as $containerName => $container)
            $this->validateContainer( $containerName );
    }


    /*
    checks validation of field
    can use Foreign Functions
    can use checkIfDataInRepository()
    */
    private function validateField( $fieldName ){
        $Value = $this->entryData[ $fieldName ];
        $Field = $this->formStruct['Fields'][ $fieldName ];


    }


    /*
    checks if Data are maching Data Repository (We dont want people born 37th of September)
    */
    private function checkIfDataInRepository( $fieldName ){    }


    /*
    checks validation of container
    can use Foreign Functions
    can use solveLogicEquations( )
    */
    private function validateContainer( $containerName ){
        $Container = $this->formStruct['Containers'][ $containerName ];



    }

    /*
    function solving logic equasion in validateContainer
    */
    private function solveLogicEquation( $containerName, $validator ){}

    private function getDataStruct( $formDataStructId ){}




}

?>
