<?php
namespace WioForms\FieldValidator;

abstract class AbstractFieldValidator
{
    protected $valid;
    protected $state;
    protected $message;

    function __construct(){
        $this->valid = false;
        $this->state = 0;
        $this->message = '';
    }


    abstract function validatePHP( $value, $settings );


    protected function getReturn(){
        $array = [
            'valid'   => $this->valid,
            'state'   => $this->state,
            'message' => $this->message
        ];
        return $array;
    }


    public function print_validateJS(){
        $javascript = '';

        $javascript .= 'function( value, settings ){';
        $javascript .= 'return {valid:valid, state:state, message:message};';
        $javascript .= '}';

        return $javascript;
    }
}
?>
