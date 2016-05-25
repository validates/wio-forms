<?php
namespace WioForms\FieldRenderer;

class Select extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $this->html = '';
        $this->prepareDataSet();
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();


        $this->html .= '<select name="'.$this->fieldName.'">';

        $this->html .= '<option value="">wybierz</option>';
        foreach ($this->dataSet as $option => $option_name)
        {
            $selected = '';
            if ($option == $this->value)
            {
                $selected = ' selected="selected"';
            }
            $this->html .= '<option value="'.$option.'"'.$selected.'>'.$option_name.'</option>';
        }
        $this->html .= '</select>';


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
