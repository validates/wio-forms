<?php
namespace WioForms\FieldRenderer;

class PasswordInput extends AbstractFieldRenderer
{

    function showToEdit(){

        $html = $this->fieldInfo['title']. ': <input type="password" name="'.$this->fieldName.'" value="'.$this->value.'" />';

        $html .= $this->standardErrorDisplay();
        $html .= '<br/>';

        return $html;
    }

    function showToView(){
        $html = 'PasswordInput: '.'********'.'<br/>';

        return $html;
    }
}

?>
