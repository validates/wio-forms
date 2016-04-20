<?php
namespace WioForms\FieldRenderer;

class Hidden extends AbstractFieldRenderer
{

    function showToEdit(){

        $html = '';

        $html .= '<input type ="hidden" name="'.$this->fieldName.'" value="'.$this->value.'"/>';

        if ($this->message !== false)
        {
            $html .= '<b style="color: red;"> &nbsp; '.$this->message.'</b>';
            $html .= '<br/>';
        }

        return $html;
    }

    function showToView(){
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}

?>
