<?php

namespace WioForms\FieldRenderer;

class ContinueButton extends AbstractFieldRenderer
{
    public function showToEdit()
    {
        $this->html = '';
        $this->inputContainerHead();
        $this->inputFieldContainerHead();

        $this->html = '<button class="button-big-red" name="'.$this->fieldName.'" value="true">'.$this->fieldInfo['title'].'</button>';

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    public function showToView()
    {
        $html = 'PasswordInput: '.'********'.'<br/>'."\n";

        return $html;
    }
}
