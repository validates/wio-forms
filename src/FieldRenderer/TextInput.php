<?php
namespace WioForms\FieldRenderer;

class TextInput extends AbstractFieldRenderer
{

    function ShowToEdit(){
        $html = 'TextInput [toEdit]: '.'abc'.'<br/>';

        return $html;
    }

    function ShowToView(  ){
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}

?>
