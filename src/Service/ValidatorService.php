<?php
namespace WioForms\Service;

use \WioForms\Service\Validation\Container as ContainerValidationService;
use \WioForms\Service\Validation\Field as FieldValidationService;

class ValidatorService
{
    public $wioForms;
    public $formStruct;

    # Holds data to Validate
    private $entryData;

    private $containerValidationService;
    private $fieldValidationService;

    function __construct( $wioFormsObiect ){
        $this->wioForms = &$wioFormsObiect;
        $this->formStruct = &$this->wioForms->formStruct;

        $this->containerValidationService = new ContainerValidationService( $this->wioForms );
        $this->fieldValidationService     = new FieldValidationService( $this->wioForms );

        $this->PHPvalidators = [];
    }

    /*
    runs by preSubmit(), submit(), update()
    checks all fields and all containers for validation errors
    */
    public function validateForm( $entryData ){
        $this->entryData = $entryData;
        $formValidity = true;

        foreach ($this->formStruct['Fields'] as $fieldName => $field)
        {
            $validity = $this->fieldValidationService->validate( $fieldName, $this->entryData[ $fieldName ] );
            if ( !$validity )
            {
                $formValidity = false;
            }
        }
        foreach ($this->formStruct['Containers'] as $containerName => &$container)
        {
            $validity = $this->containerValidationService->validate( $container );
            if ( !$validity )
            {
                $formValidity = false;
            }
        }
        return $formValidity;
    }

    /*
    checks if Data are maching Data Repository (We dont want people born 37th of September)
    */
    private function checkIfDataInRepository( $fieldName ){    }

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
        if ( isset($this->entryData['_wioFormsSite']) )
        {
            return (Int)($this->entryData['_wioFormsSite'])+1;
        }
        return 0;
    }

}
?>
