<?php
namespace WioForms\FormRenderer;

class FormRenderer extends AbstractFormRenderer
{

    function ShowHead(){
        $html = '';

        $html .= '<form method="post" action="">';

        return $html;
    }

    function ShowTail(){
        $html = '';

        $html .= '<input type="hidden" name="wio_forms" value="yes" />';
        $html .= '</form>';

        return $html;
    }

}

?>
