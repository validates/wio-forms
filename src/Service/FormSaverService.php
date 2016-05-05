<?php
namespace WioForms\Service;

class FormSaverService
{
    private $wioForms;
    private $formStruct;

    private $clearTemporarySave;


    function __construct($wioFormsObject){
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;

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
            $this->validateFormSaver($FormSaver);
        }
    }


    private function validateFormSaver(&$FormSaver)
    {


    }

}
