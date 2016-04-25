<?php
namespace WioForms\FormRenderer;

class FormRenderer extends AbstractFormRenderer
{

    function showHead()
    {
        $html = '';
        $html .= '<form method="post" action="">';

        return $html;
    }

    function showTail()
    {
        $html = '';

        $html .= '<input type="hidden" name="_wioForms" value="yes" />';
        $html .= '<input type="hidden" name="_wioFormsSite" value="'.$this->wioForms->rendererService->siteNumber.'" />';
        $html .= '</form>';

        return $html;
    }

}

?>
