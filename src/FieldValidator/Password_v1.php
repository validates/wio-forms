<?php
namespace WioForms\FieldValidator;

class Password_v1 extends AbstractFieldValidator
{

    public function validatePHP($value, $settings)
    {
        $this->invalidMessage = 'password_to_short';

        if (strlen($value) >= 8)
        {
            $this->valid = true;
        }

        $this->setAnswer();
        return $this->getReturn();
    }
}
