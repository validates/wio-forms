<?php
namespace WioForms\FieldValidator;

class Password_v1 extends AbstractFieldValidator
{

    public function validatePHP( $value, $settings ){

        if (  strlen( $value ) >= 8 )
        {
            $this->state = 1;
            $this->valid = true;
        }
        else
        {
            $this->state = -1;
            $this->valid = false;
            $this->message = 'password_to_short';
        }

        return $this->getReturn();
    }

}
?>
