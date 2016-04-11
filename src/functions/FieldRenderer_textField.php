<?php
namespace WioForms;

abstract class FieldRenderer_textField
{

    function ShowToEdit(){
    $html = 'textField [toEdit]: '.'eeee'.'<br/>';

    return $html;


    }

    function ShowToView(  ){
        $html = 'textField: '.'eeee'.'<br/>';

        return $html;
    }
}

?>
