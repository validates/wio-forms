<?php
namespace WioForms\FieldRenderer;

class GoogleReCaptcha extends AbstractFieldRenderer
{

    function showToEdit(){
        $html = '';

        $html .= $this->standardErrorDisplay('<br/>');
        $html .= "<script src='https://www.google.com/recaptcha/api.js?hl=pl'></script>";
        $html .= '<div class="g-recaptcha" data-sitekey="'. $this->wioForms->settings['ReCaptcha']['SiteKey'] .'"></div>';

        return $html;
    }

    function showToView(){
        $html = 'reCaptcha';

        return $html;
    }
}

?>
