<?php

namespace WioForms\TemporarySave;

abstract class AbstractTemporarySave
{
    protected $wioForms;
    protected $formStruct;

    public function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;
    }

    abstract public function saveFormData();

    abstract public function getFormData();

    abstract public function clearFormData();
}
