<?php
namespace WioForms\Service;


class ValidatorService
{
    public $wioForms;
    public $formStruct;

    # Holds data to Validate
    private $entryData;

    # Holds PHP Validators
    private $PHPvalidators;

    function __construct( $wioFormsObiect ){
        $this->wioForms = $wioFormsObiect;

        $this->PHPvalidators = [];
    }

    /*
    runs by preSubmit(), submit(), update()
    checks all fields and all containers for validation errors
    */
    public function validateForm( $entryData ){
        $this->formStruct = &$this->wioForms->formStruct;
        $this->entryData = $entryData;

        $formValidity = true;

        foreach ($this->formStruct['Fields'] as $fieldName => $field)
        {
            $validity = $this->validateField( $fieldName );
            if ( !$validity )
            {
                $formValidity = false;
            }
        }
        foreach ($this->formStruct['Containers'] as $containerName => $container)
        {
            $validity = $this->validateContainer( $containerName );
            if ( !$validity )
            {
                $formValidity = false;
            }
        }
        return $formValidity;
    }


    /*
    checks validation of field
    can use Foreign Functions
    can use checkIfDataInRepository()
    */
    private function validateField( $fieldName ){

        $value = '';
        if (isset( $this->entryData[ $fieldName ] )){
            $value = $this->entryData[ $fieldName ];
        }
        $field = &$this->formStruct['Fields'][ $fieldName ];

        $field['valid'] = true;
        $field['state'] = 1;
        $field['message'] = false;

        $field['value'] = $value;

        if ( isset($field['validationPHP']) and is_array($field['validationPHP']) )
        {
            foreach ($field['validationPHP'] as $validatorInfo)
            {
                if ( isset( $this->formStruct['FieldValidatorsPHP'][ $validatorInfo['method'] ]['class'] ))
                {
                      $className = $this->formStruct['FieldValidatorsPHP'][ $validatorInfo['method'] ]['class'];
                }
                else {
                    $this->wioForms->errorLog->errorLog('FieldValidatorsPHP class name for '.$validatorInfo['method'].'  not found. ');
                    continue;
                }

                if ( !( $validatorClass = $this->getPHPfieldValidator( $className ) ))
                {
                    continue;
                }

                $settings = [];
                if (isset( $validatorInfo['settings'] ))
                {
                    $settings = $validatorInfo['settings'];
                }

                $validationResult = $validatorClass->validatePHP( $value, $settings );

                $field['state'] = $validationResult['state'];
                $field['valid'] = $validationResult['valid'];
                if ( $validationResult['valid']===false and isset( $validatorInfo['newErrorMessage'] ) )
                {
                    $field['message'] = $validatorInfo['newErrorMessage'];
                }
                else
                {
                    $field['message'] = $validationResult['message'];
                }
            }
        }

        return $field['valid'];
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
        $container = &$this->formStruct['Containers'][ $containerName ];

        if ( isset($container['validationPHP']) and is_array($container['validationPHP']) )
        {
            foreach ($container['validationPHP'] as $validatorInfo)
            {
                if ($validatorInfo['type'] == 'method' )
                {
                    if ( isset( $this->formStruct['ContainerValidatorsPHP'][ $validatorInfo['method'] ]['class'] ))
                    {
                          $className = $this->formStruct['ContainerValidatorsPHP'][ $validatorInfo['method'] ]['class'];
                    }
                    else
                    {
                        $this->wioForms->errorLog->errorLog('ContainerValidatorsPHP class name for '.$validatorInfo['method'].'  not found. ');
                        continue;
                    }

                    if ( !( $ValidatorClass = $this->getPHPcontainerValidator( $className ) ))
                    {
                        continue;
                    }

                    $settings = [];
                    if (isset( $validatorInfo['settings'] ))
                    {
                        $settings = $validatorInfo['settings'];
                    }

                    $validationResult = $ValidatorClass->validatePHP( $containerName, $settings );

                    $this->containerChangeState( $containerName, $validationResult['state'] );
                    $container['valid'] = $validationResult['valid'];
                    $container['message'] = $validationResult['message'];
                }
                elseif ( $validatorInfo['type'] == 'logic' )
                {
                    $result = $this->wioForms->logicEquasionService->solveEquasion($validatorInfo['logicEquasion']);

                    if ( $result === true and isset($validatorInfo['newState']))
                    {
                        $this->containerChangeState( $containerName, $validatorInfo['newState'] );
                    }
                    if ( $result === false and isset($validatorInfo['newErrorState']))
                    {
                        $this->containerChangeState( $containerName, $validatorInfo['newErrorState'] );
                    }

                    $container['valid'] = $result;
                }
            }

            return $container['valid'];
        }
        else
        {
            # Container with no validation rules is valid with state = 1
            $container['valid'] = true;
            $this->containerChangeState( $containerName, 1 );
            $container['message'] = false;
        }

    }

    /*
    function solving logic equasion in validateContainer
    */
    private function solveLogicEquation( $containerName, $validator ){}

    private function getDataStruct( $formDataStructId ){}


    private function containerChangeState( $containerName, $newState  )
    {
        $container = &$this->formStruct['Containers'][ $containerName ];

        if ( isset($container['state']) )
        {
            $oldState = $container['state'];
        }
        else
        {
            $oldState = 0;
        }

        $contaienr['state'] = $newState;

        if ( !isset($container['stateActions']) )
        {
            return true;
        }

        foreach ( $container['stateActions'] as $action )
        {
            if ( $action['state'] == $newState )
            {
                $doIt = true;
                if ( isset($action['previousAllowedStates']) )
                {
                    $doIt = false;
                    foreach ($action['previousAllowedStates'] as $wantedState)
                    {
                        if ( $wantedState == $oldState )
                        {
                            $doIt = true;
                            break;
                        }
                    }
                }

                if ($doIt)
                {
                    foreach ($action['actions'] as $action)
                    {
                        $this->containerMakeAction( $containerName, $action );
                    }

                }
            }
        }
    }

    private function containerMakeAction( $containerName, $action )
    {
        $container = &$this->formStruct['Containers'][ $containerName ];

        if ( $action == 'hide' )
        {
            $container['hidden'] = true;
        }
        elseif ( $action == 'show' )
        {
            unset( $container['hidden'] );
        }
        else
        {
            if ( !isset($container['styleOptions']) )
            {
                $container['styleOptions'] = [];
            }
            $container['styleOptions'][ $action ] = true;
        }
    }

    private function getPHPfieldValidator( $validatorName )
    {
        $className = $this->wioForms->classFinderService->checkName( 'FieldValidator' , $validatorName );
        if ( $className )
        {
            return new $className();
        }
        return false;
    }

    private function getPHPcontainerValidator( $validatorName )
    {
        $className = $this->wioForms->classFinderService->checkName( 'ContainerValidator', $validatorName );
        if ( $className )
        {
            return new $className( $this->wioForms );
        }
        return false;
    }

    /*
    Function search for highest "site" number in any container that is not set on "hide"

    */
    public function getAvaliableSiteNumber()
    {
        $maxSite = 0;

        foreach ($this->formStruct['Containers'] as $container)
        {
            if ( $container['container'] == '_site'
              and !( isset($container['hidden']) and $container['hidden'] )
              and $container['site'] > $maxSite )
            {
                $maxSite = $container['site'];
            }
        }

        return $maxSite;
    }

    public function getLastEditedSite()
    {
        if ( isset($_POST['_wioFormsSite']) )
        {
            return (Int)($_POST['_wioFormsSite'])+1;
        }
        return 0;
    }

}
?>
