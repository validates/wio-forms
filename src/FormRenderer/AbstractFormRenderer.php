<?php
namespace WioForms\FormRenderer;


abstract class AbstractFormRenderer
{
    protected $WioForms;

    function __construct( $WioFormObject ){
        $this->WioForms = $WioFormObject;
    }

    abstract function ShowHead();
    abstract function ShowTail();

}

?>
