<?php
namespace WioForms\FieldRenderer;

class PasswordInput extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $this->html = '';
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();

        $this->html .= '<input type="password" name="'.$this->fieldName.'" value="'.$this->value.'" />';

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    function showToView()
    {
        $html = 'PasswordInput: '.'********'.'<br/>'."\n";

        return $html;
    }
}
