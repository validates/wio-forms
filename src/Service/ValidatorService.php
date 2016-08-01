<?php

namespace WioForms\Service;

use WioForms\Service\Validation\Container as ContainerValidationService;
use WioForms\Service\Validation\Field as FieldValidationService;

class ValidatorService
{
    public $wioForms;
    public $formStruct;

    private $containerValidationService;
    private $fieldValidationService;

    public function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;

        $this->containerValidationService = new ContainerValidationService($this->wioForms);
        $this->fieldValidationService = new FieldValidationService($this->wioForms);

        $this->PHPvalidators = [];
    }

    public function validateFields()
    {
        // these are containers which were sent in this POST request
        $postContainers = (isset($_POST['_wioForms_containers']))
            ? $_POST['_wioForms_containers']
            : [];

        foreach ($this->formStruct['Fields'] as $fieldName => &$field) {
            if (!$field['waitForDefaultValue']
                and !$field['validated']) {
                $this->fieldValidationService->validate($fieldName);
                $field['validated'] = true;
            }

            // if field is not included in the sent container hide the validation
            // message. It solves the problem when user is presented the next
            // site or part of the form without his or her action and gets
            // the error messages without any interaction
            if (!in_array($field['container'], $postContainers)) {
                $field['message'] = false;
            }
        }
    }

    public function validateContainers()
    {
        foreach ($this->formStruct['Containers'] as $containerName => &$container) {
            $this->containerValidationService->validate($container);
        }
    }

    private function checkIfDataInRepository($fieldName)
    {
    }


    public function getAvaliableSitesArray()
    {
        $avaliableSites = [];

        foreach ($this->formStruct['Containers'] as $container) {
            if ($container['container'] == '_site'
              and !(isset($container['hidden']) and $container['hidden'])) {
                $avaliableSites[] = $container['site'];
            }
        }
        sort($avaliableSites);

        return $avaliableSites;
    }

    public function getAvaliableSiteNumber()
    {
        $maxSite = 0;

        foreach ($this->formStruct['Containers'] as $container) {
            if ($container['container'] == '_site'
              and !(isset($container['hidden']) and $container['hidden'])
              and $container['site'] > $maxSite) {
                $maxSite = $container['site'];
            }
        }

        return $maxSite;
    }

    public function getLastEditedSite()
    {
        if (isset($this->wioForms->entryData['_wioFormsSite'])) {
            return (int) ($this->wioForms->entryData['_wioFormsSite']) + 1;
        }

        return 0;
    }
}
