<?php
namespace WioForms\FieldRenderer;

abstract class AbstractFieldRenderer
{
    protected $WioForms;
    protected $FieldName;
    protected $FieldInfo;

    function __construct( $FieldName, $WioFormsObject ){
        $this->FieldName = $FieldName;
        $this->WioForms = $WioFormsObject;
        $this->FieldInfo = &$this->WioForms->formStruct['Fields'][ $this->FieldName ];
    }
    abstract function ShowToEdit();

    abstract function ShowToView();
}

?>
