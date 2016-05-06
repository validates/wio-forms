<?php
namespace WioForms\Service;

use \WioForms\Service\Validation\Container as ContainerValidationService;

class FormSaverService
{
    private $wioForms;
    private $formStruct;

    private $clearTemporarySave;


    function __construct($wioFormsObject){
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;

        $this->containerValidationService = new ContainerValidationService($wioFormsObject);

        $this->clearTemporarySave = false;
    }

    public function getClearTemporarySave()
    {
      return $this->clearTemporarySave;
    }

    public function tryFormSavers()
    {
        $FormSavers = &$this->formStruct['FormSavers'];

        foreach ($FormSavers as $FormSaverName => &$FormSaver)
        {
            $valid = $this->validateFormSaver($FormSaver);

            if ($valid)
            {
                $this->saveForm($FormSaver);

            }
        }
    }

    private function validateFormSaver(&$FormSaver)
    {
        return $this->containerValidationService->validate($FormSaver);
    }


    private function saveForm(&$FormSaver)
    {

          var_dump($FormSaver);
    }
}
