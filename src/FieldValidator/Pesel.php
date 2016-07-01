<?php

namespace WioForms\FieldValidator;

class Pesel extends AbstractFieldValidator
{
    public function validatePHP($value, $settings)
    {
        $this->invalidMessage = 'pesel_invalid';


        $weightTable = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];

        if (!is_numeric($value)) {
            $this->valid = false;
        } elseif (strlen($value) != 11) {
            $this->valid = false;
        } else {
            $sum = 0;
            for ($i = 0; $i < 10; ++$i) {
                $sum += $value[$i] * $weightTable[$i];
            }

            $control = $sum % 10;
            if ($control != 0) {
                $control = 10 - $control;
            }

            if ($control == $value[10]) {
                $this->valid = true;
            } else {
                $this->valid = false;
            }
        }

        $this->setAnswer();

        return $this->getReturn();
    }
}
