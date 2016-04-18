<?php
namespace WioForms\FormRenderer;

class FormRenderer extends AbstractFormRenderer
{

    function showHead(){
        $html = '';

        $html .= '<form method="post" action="">';

        return $html;
    }

    function showTail(){
        $html = '';

        $html .= '<input type="hidden" name="wio_forms" value="yes" />';
        $html .= '</form>';

        return $html;
    }

}

?>
