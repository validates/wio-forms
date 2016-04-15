<?php
namespace WioForms\Service;


class ValidatorService
{
    public $WioForms;
    public $formStruct;

    # Holds data to Validate
    private $entryData;

    # Holds PHP Validators
    private $PHPvalidators;

    function __construct( $WioFormsObiect ){
        $this->WioForms = $WioFormsObiect;

        $this->PHPvalidators = [];
    }

    /*
    runs by preSubmit(), submit(), update()
    checks all fields and all containers for validation errors
    */
    public function validateForm( $entryData ){
        $this->formStruct = &$this->WioForms->formStruct;
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

        $Value = '';
        if (isset( $this->entryData[ $fieldName ] )){
            $Value = $this->entryData[ $fieldName ];
        }
        $Field = &$this->formStruct['Fields'][ $fieldName ];

        $Field['value'] = $Value;

        if ( isset($Field['validationPHP']) and is_array($Field['validationPHP']) )
        {
            foreach ($Field['validationPHP'] as $validatorInfo)
            {
                if ( isset( $this->formStruct['ValidatorsPHP'][ $validatorInfo['method'] ]['class'] ))
                {
                      $className = $this->formStruct['ValidatorsPHP'][ $validatorInfo['method'] ]['class'];
                }
                else {
                    $this->WioForms->ErrorLog->ErrorLog('ValidatorsPHP class name for '.$validatorInfo['method'].'  not found. ');
                    continue;
                }

                if ( !( $ValidatorClass = $this->getPHPvalidator( $className ) ))
                {
                    continue;
                }

                $Settings = [];
                if (isset( $validatorInfo['settings'] ))
                {
                    $Settings = $validatorInfo['settings'];
                }

                $ValidationResult = $ValidatorClass->validatePHP( $Value, $Settings );

                $Field['state'] = $ValidationResult['state'];
                $Field['valid'] = $ValidationResult['valid'];
                $Field['message'] = $ValidationResult['message'];

            }
        }
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


    private function getPHPvalidator( $validatorName ){
        $className = '\WioForms\FieldValidator\\'.$validatorName;
        if ( class_exists($className) ) {
            return new $className();
        }
        else
        {
            $this->WioForms->ErrorLog->ErrorLog('Class '.$className.' not found.');
            return false;
        }
    }


}

?>
