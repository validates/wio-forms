<?php

namespace WioForms\FormRenderer;

class FormRenderer extends AbstractFormRenderer
{
    public function showHead()
    {
        $this->wioForms->headerCollectorService->addCss('assets/css/wioForms_superW.css');

        $html = '';
        $html .= '<form method="post" action="" class="wioForms_Form">'."\n";

        return $html;
    }

    public function showTail()
    {
        $html = '';

        $html .= '<input type="hidden" name="_wioForms" value="yes" />'."\n";
        $html .= '<input type="hidden" name="_wioFormsSite" value="'.$this->wioForms->rendererService->siteNumber.'" />'."\n";
        $html .= '</form>'."\n";

        return $html;
    }
}
