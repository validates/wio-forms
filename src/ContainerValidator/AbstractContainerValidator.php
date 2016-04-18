<?php
namespace WioForms\ContainerValidator;

abstract class AbstractContainerValidator
{
    protected $valid;
    protected $state;
    protected $message;

    protected $wioForms;

    function __construct( $wioFormsObiect ){
        $this->valid = false;
        $this->state = 0;
        $this->message = '';

        $this->wioForms = $wioFormsObiect;
    }


    abstract function validatePHP( $containerName, $settings );


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

        $javascript .= 'function( containerName, settings ){';
        $javascript .= '}';

        return $javascript;
    }
}
?>
