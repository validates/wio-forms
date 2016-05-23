<?php
namespace WioForms\FieldRenderer;

class ChooseOneButton extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $this->prepareDataSet();

        $this->html = '';
        $this->inputContainerHead('wioForms_Container_ToCenter');
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();

        foreach ($this->dataSet as $option => $option_name)
        {
            $addClass = '';
            if ($option=='new_account')
            {
                $addClass = ' colorRed';
            }

            $this->html .= '<button type="submit" class="wioForms_Button width250 '.$addClass.'" name="'.$this->fieldName.'" value="'.$option.'">'.$option_name.'</button>';
        }

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    function showToView()
    {
        $this->prepareDataSet();

        $html = 'TextInput: '.$this->dataSet[ $this->value ].'<br/>';

        return $html;
    }
}
