<?php
namespace WioForms\TemporarySave;

abstract class AbstractTemporarySave
{
    protected $wioForms;
    protected $formStruct;

    function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;
    }

    abstract function saveFormData();

    abstract function getFormData();

    abstract function clearFormData();
}
