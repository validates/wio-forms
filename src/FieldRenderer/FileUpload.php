<?php

namespace WioForms\FieldRenderer;

class FileUpload extends AbstractFieldRenderer
{
    public function showToEdit()
    {
        $this->html = '';
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();

        $this->html .= '<input type="hidden" name="'.$this->fieldName.'" value="'.'" />';

        $this->html .= '<input type="file" name="'.$this->fieldName.'_file" />';




        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    public function showToView()
    {
    }
}
