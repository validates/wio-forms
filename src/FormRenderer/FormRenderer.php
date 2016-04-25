<?php
namespace WioForms\FormRenderer;

class FormRenderer extends AbstractFormRenderer
{

    function showHead()
    {
        $html = '';
        $html .= '<form method="post" action="" class="wioForms">'."\n";

        return $html;
    }

    function showTail()
    {
        $html = '';

        $html .= '<input type="hidden" name="_wioForms" value="yes" />'."\n";
        $html .= '<input type="hidden" name="_wioFormsSite" value="'.$this->wioForms->rendererService->siteNumber.'" />'."\n";
        $html .= '</form>'."\n";

        return $html;
    }

}

?>
