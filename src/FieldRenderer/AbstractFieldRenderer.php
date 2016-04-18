<?php
namespace WioForms\FieldRenderer;

abstract class AbstractFieldRenderer
{
    protected $wioForms;
    protected $fieldName;
    protected $fieldInfo;

    function __construct( $fieldName, $wioFormsObject ){
        $this->fieldName = $fieldName;
        $this->wioForms = $wioFormsObject;
        $this->fieldInfo = &$this->wioForms->formStruct['Fields'][ $this->fieldName ];
    }
    abstract function showToEdit();

    abstract function showToView();
}

?>
