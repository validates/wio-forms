<?php

namespace WioForms\FieldRenderer;

class TextInput extends AbstractFieldRenderer
{
    public function showToEdit()
    {
        $this->html = '';
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();

        $this->html .= '<input type="text" name="'.$this->fieldName.'" value="'.$this->value.'" class ="'.$this->getAdditionalInputClasses().'" '.$this->setDisabledStatus().' />';

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    public function showToView()
    {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}
