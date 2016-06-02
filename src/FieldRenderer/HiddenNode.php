<?php
namespace WioForms\FieldRenderer;

class HiddenNode extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $this->html = '';
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputFieldContainerHead();


        $this->html .='<input type="hidden" name="'.$this->fieldName.'" value="'.$this->value.'" />';

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
