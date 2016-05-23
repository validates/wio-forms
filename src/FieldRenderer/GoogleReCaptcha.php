<?php
namespace WioForms\FieldRenderer;

class GoogleReCaptcha extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $this->html = '';
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputFieldContainerHead();

        if (!isset($this->wioForms->settings['ReCaptcha']['SiteKey']))
        {
            $this->wioForms->errorLog->errorLog('No ReCaptcha SiteKey in settings');
        }

        $this->wioForms->headerCollectorService->addJS('https://www.google.com/recaptcha/api.js?hl=pl');

        $this->html .= '<div class="g-recaptcha" data-sitekey="'. $this->wioForms->settings['ReCaptcha']['SiteKey'] .'"></div>'."\n";

        $this->inputFieldContainerTail();
        $this->inputContainerTail();


        return $this->html;
    }

    function showToView()
    {
        $html = 'reCaptcha';

        return $html;
    }
}
