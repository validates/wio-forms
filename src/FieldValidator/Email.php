<?php
namespace WioForms\FieldValidator;

class Email extends AbstractFieldValidator
{

    public function validatePHP($value, $settings)
    {
        $this->invalidMessage = 'email_invalid';

        if (filter_var($value, FILTER_VALIDATE_EMAIL))
        {
            $this->valid = true;
        }

        $this->setAnswer();
        return $this->getReturn();
    }
}
