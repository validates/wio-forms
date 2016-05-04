<?php
namespace WioForms\Service;

use \WioForms\Service\Validation\Container as ContainerValidationService;
use \WioForms\Service\Validation\Field as FieldValidationService;

class ValidatorService
{
    public $wioForms;
    public $formStruct;

    # Holds data to Validate
    public $entryData;

    private $containerValidationService;
    private $fieldValidationService;

    function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;

        $this->containerValidationService = new ContainerValidationService($this->wioForms);
        $this->fieldValidationService     = new FieldValidationService($this->wioForms);

        $this->PHPvalidators = [];
    }

    public function checkFormValidity()
    {
        $formValidity = true;

        foreach ($this->formStruct['Fields'] as $fieldName => $field)
        {
            if (!$field['valid'])
            {
                $formValidity = false;
                break;
            }
        }

        foreach ($this->formStruct['Containers'] as $containerName => $container)
        {
            if (!$container['valid'])
            {
                $formValidity = false;
                break;
            }
        }
        return $formValidity;
    }

    public function validateFields()
    {
        foreach ($this->formStruct['Fields'] as $fieldName => $field)
        {
            $this->fieldValidationService->validate($fieldName);
        }
    }

    public function validateContainers()
    {
        foreach ($this->formStruct['Containers'] as $containerName => &$container)
        {
            $this->containerValidationService->validate($container);
        }
    }


    private function checkIfDataInRepository($fieldName){}


    public function getAvaliableSiteNumber()
    {
        $maxSite = 0;

        foreach ($this->formStruct['Containers'] as $container)
        {
            if ($container['container'] == '_site'
              and !(isset($container['hidden']) and $container['hidden'])
              and $container['site'] > $maxSite)
            {
                $maxSite = $container['site'];
            }
        }

        return $maxSite;
    }

    public function getLastEditedSite()
    {
        if (isset($this->entryData['_wioFormsSite']))
        {
            return (Int)($this->entryData['_wioFormsSite'])+1;
        }
        return 0;
    }

}
