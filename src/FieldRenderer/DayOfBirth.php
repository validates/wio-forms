<?php
namespace WioForms\FieldRenderer;

class DayOfBirth extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $html = '';
        $html .= $this->fieldInfo['title'].': ';

        $html .= '<input type="text" name="'.$this->fieldName.'" value="'.$this->value.'"/>';

        $html .= $this->standardErrorDisplay();
        $html .= '<br/>';

        return $html;
    }

    function showToView()
    {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}

?>
