<?php

namespace WioForms\FieldRenderer;

class CheckboxList extends AbstractFieldRenderer
{
    public function showToEdit()
    {
        $this->html = '';
        $this->prepareDataSet();
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();
        $this->prepareValueArray();

        $this->html .= '<ul class="wioForms_CheckboxList">';
        foreach ($this->dataSet as $option => $option_name) {
            $checked = '';
            if (isset($this->valueArray[$option])) {
                $checked = ' checked="checked"';
            }
            $this->html .= '<li><input type="checkbox" value="'.$option.'"'.$checked.' name="'.$this->fieldName.'[]">'.$option_name.'</li>';
        }
        $this->html .= '</ul>';

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    public function showToView()
    {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }

    private function prepareValueArray()
    {
        $valArray = array_flip(explode('|', $this->value));
        $this->valueArray = $valArray;
    }
}
