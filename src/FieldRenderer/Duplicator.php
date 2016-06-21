<?php

namespace WioForms\FieldRenderer;

class Duplicator extends AbstractFieldRenderer
{
    public function showToEdit()
    {
        $this->prepareDataSet();

        $this->html = '';
        $this->inputContainerHead('wioForms_Container_ToCenter');
        $this->standardErrorDisplay();
        $this->manageFieldsToDuplicate();

        $this->html .= ' <a id ="duplicate" class="'.$this->getAdditionalInputClasses().'">'.$this->fieldInfo['title'].'</a>'."\n";

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    public function showToView()
    {
        $this->prepareDataSet();

        $html = 'TextInput: '.$this->dataSet[$this->value].'<br/>';

        return $html;
    }

    private function manageFieldsToDuplicate()
    {
        $this->html .= "<script type='text/javascript'>";
        $this->html .= file_get_contents(__DIR__.'/js/duplicator.js');
        $this->html .= "</script>";
    }
}
