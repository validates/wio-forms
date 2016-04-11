<?php
namespace WioForms\FieldRenderer;

abstract class AbstractFieldRenderer
{
    private $WioForms;
    private $FieldName;
    private $FieldInfo;

    abstract function __construct( $FieldName, $WioFormsObject );

    abstract function ShowToEdit();

    abstract function ShowToView();
}

?>
