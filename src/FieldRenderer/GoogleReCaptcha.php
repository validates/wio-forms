<?php
namespace WioForms\FieldRenderer;

class GoogleReCaptcha extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $html = '';

        if (!isset($this->wioForms->settings['ReCaptcha']['SiteKey']))
        {
            $this->wioForms->errorLog->errorLog('No ReCaptcha SiteKey in settings');
        }

        $html .= $this->standardErrorDisplay('<br/>');
        $this->wioForms->headerCollectorService->addJS('https://www.google.com/recaptcha/api.js?hl=pl');

        $html .= '<div class="g-recaptcha" data-sitekey="'. $this->wioForms->settings['ReCaptcha']['SiteKey'] .'"></div>'."\n";

        return $html;
    }

    function showToView()
    {
        $html = 'reCaptcha';

        return $html;
    }
}
