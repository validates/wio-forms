<?php
namespace WioForms\FieldRenderer;

class TextInput extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $html = $this->fieldInfo['title']. ': <input type="text" name="'.$this->fieldName.'" value="'.$this->value.'" />';

        $html .= $this->standardErrorDisplay();
        $html .= '<br/>'."\n";

        return $html;
    }

    function showToView()
    {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}

?>
