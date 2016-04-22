<?php
namespace WioForms\FieldValidator;

use GuzzleHttp\Client;

class ReCaptcha extends AbstractFieldValidator
{

    public function validatePHP( $value, $settings ){

        $isValid = false;


        if ( isset($this->wioForms->validatorService->entryData['g-recaptcha-response']) )
        {
            $value = $this->wioForms->validatorService->entryData['g-recaptcha-response'];

            $POST = [
                'secret' => $this->wioForms->settings['ReCaptcha']['SecretKey'],
                'response'=> $value
            ];

            $client = new Client();
            $requestData['form_params'] = $POST;

            $responsePOST = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', $requestData)->getBody()->getContents();
            $responseJSON = json_decode( $responsePOST );

            $isValid = $responseJSON->success;
        }


        if ( $isValid )
        {
            $this->state = 1;
            $this->valid = true;
        }
        else
        {
            $this->state = -1;
            $this->valid = false;
            $this->message = 'enter_captcha';
        }

        return $this->getReturn();
    }

}
?>
