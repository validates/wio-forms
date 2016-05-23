<?php
namespace WioForms\FieldRenderer;

class Hidden extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $this->standardErrorDisplay();

        $this->html = '<input type ="hidden" name="'.$this->fieldName.'" value="'.$this->value.'"/>';

        return $this->html;
    }

    function showToView()
    {
        $html = 'TextInput: '.'abc'.'<br/>'."\n";

        return $html;
    }
}
