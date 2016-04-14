<?php
namespace WioForms\FieldRenderer;

class PasswordInput extends AbstractFieldRenderer
{

    function __construct( $FieldName, $WioFormsObject ){
        $this->FieldName = $FieldName;
        $this->WioForms = $WioFormsObject;
        $this->FieldInfo = $this->WioForms->formStruct['Fields'][ $this->FieldName ];
    }

    function ShowToEdit(){
        $html = $this->FieldInfo['title']. ': <input type="password" name="'.$this->FieldName.'" value="abc" /><br/>';

        return $html;
    }

    function ShowToView(){
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}

?>
