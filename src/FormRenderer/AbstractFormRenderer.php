<?php

namespace WioForms\FormRenderer;

abstract class AbstractFormRenderer
{
    protected $wioForms;

    public function __construct($wioFormObject)
    {
        $this->wioForms = $wioFormObject;
    }

    abstract public function showHead();

    abstract public function showTail();
}
