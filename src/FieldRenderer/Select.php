<?php

namespace WioForms\FieldRenderer;

class Select extends AbstractFieldRenderer
{
    public function showToEdit()
    {
        $this->html = '';
        $this->prepareDataSet();
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();

        $name = isset($this->fieldInfo['isGroup']) ? $this->fieldName."[]" : $this->fieldName;
        $this->manageDynamicOptions();
        $this->html .= '<select name="'.$name.'" class ="'.$this->getAdditionalInputClasses().'" >';

        $this->html .= '<option value="">wybierz</option>';
        foreach ($this->dataSet as $option => $option_name) {
            $selected = '';
            if ($option == $this->value) {
                $selected = ' selected="selected"';
            }
            $this->html .= '<option value="'.$option.'"'.$selected.'>'.$option_name.'</option>';
        }
        $this->html .= '</select>';


        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    public function showToView()
    {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }

    private function manageDynamicOptions()
    {
        if (!isset($this->fieldInfo['dynamicOptions'])) {
            return;
        }
        $this->html .= "<script type='text/javascript'>";
        $this->html .= "var parentSelector = '".$this->fieldInfo['dynamicOptions']."';";
        $this->html .= "var childSelector = '".$this->fieldName."';";
        $data = json_encode($this->dataSet);
        $this->html .= "var lists = '".$data."';";
        $this->html .= PHP_EOL;
        $this->html .= file_get_contents(__DIR__.'/js/select.js');
        $this->html .= "</script>";
        $this->dataSet = [];
    }
}
