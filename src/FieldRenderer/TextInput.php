<?php
namespace WioForms\FieldRenderer;

class TextInput extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $this->html = '';
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();

        $this->html .= '<input type="text" name="'.$this->fieldName.'" value="'.$this->value.'" />';

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    function showToView()
    {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}
