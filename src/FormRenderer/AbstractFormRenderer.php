<?php
namespace WioForms\FormRenderer;


abstract class AbstractFormRenderer
{
    protected $wioForms;

    function __construct($wioFormObject)
    {
        $this->wioForms = $wioFormObject;
    }

    abstract function showHead();
    abstract function showTail();

}
