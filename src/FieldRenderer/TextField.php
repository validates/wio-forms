<?php
namespace WioForms\FieldRenderer;

class TextField extends FieldRenderer
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
