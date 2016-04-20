<?php
namespace WioForms\FieldRenderer;

class Select extends AbstractFieldRenderer
{

    function showToEdit(){
        $this->prepareDataSet();

        $html = '';
        $html .= $this->fieldInfo['title'].': ';

        $html .= '<select name="'.$this->fieldName.'">';

        $html .= '<option value="">wybierz</option>';
        foreach ( $this->dataSet as $option => $option_name)
        {
            $selected = '';
            if ($option == $this->value)
            {
                $selected = ' selected="selected"';
            }
            $html .= '<option value="'.$option.'"'.$selected.'>'.$option_name.'</option>';
        }
        $html .= '</select>';

        $html .= $this->standardErrorDisplay();
        $html .= '<br/>';

        return $html;
    }

    function showToView(){
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}

?>
