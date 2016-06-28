<?php

namespace WioForms\FieldValidator;

use GuzzleHttp\Client;

class ReCaptcha extends AbstractFieldValidator
{
    public function validatePHP($value, $settings)
    {
        $this->invalidMessage = 'enter_captcha';
        $isValid = false;

        if (isset($this->wioForms->entryData['g-recaptcha-response'])) {
            $client = new Client();
            $requestData['form_params'] = [
                'secret' => $this->wioForms->settings['ReCaptcha']['SecretKey'],
                'response' => $this->wioForms->entryData['g-recaptcha-response'],
            ];

            $responsePOST = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', $requestData)->getBody()->getContents();
            $responseJSON = json_decode($responsePOST);

            $isValid = $responseJSON->success;
        }


        if ($isValid) {
            $this->valid = true;
        }

        $this->setAnswer();

        return $this->getReturn();
    }
}
