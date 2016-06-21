<?php

namespace WioForms\FieldRenderer;

class TextArea extends AbstractFieldRenderer
{
    public function showToEdit()
    {
        $this->html = '';
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();

        $this->html .= '<textarea name="'.$this->fieldName.'" value="'.$this->value.'" class ="'.$this->getAdditionalInputClasses().'" ></textarea>';

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    public function showToView()
    {
    }
}
