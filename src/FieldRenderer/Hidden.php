<?php
namespace WioForms\FieldRenderer;

class Hidden extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $html = '';
        $html .= '<input type ="hidden" name="'.$this->fieldName.'" value="'.$this->value.'"/>';

        $this->standardErrorDisplay('<br/>');

        return $html;
    }

    function showToView(){
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}

?>
