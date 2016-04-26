<?php
namespace WioForms\FieldRenderer;

class ChooseOneButton extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $this->prepareDataSet();

        $html = '';
        $html .= $this->fieldInfo['title'].': ';

        foreach ($this->dataSet as $option => $option_name)
        {
            $html .= '<button type="submit" name="'.$this->fieldName.'" value="'.$option.'">'.$option_name.'</button>';
        }

        $html .= $this->standardErrorDisplay();
        $html .= '<br/>'."\n";

        return $html;
    }

    function showToView()
    {
        $this->prepareDataSet();

        $html = 'TextInput: '.$this->dataSet[ $this->value ].'<br/>';

        return $html;
    }
}
