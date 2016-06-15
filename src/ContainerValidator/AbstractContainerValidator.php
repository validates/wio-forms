<?php

namespace WioForms\ContainerValidator;

abstract class AbstractContainerValidator
{
    protected $wioForms;

    protected $valid;
    protected $state;
    protected $message;

    protected $validState = 1;
    protected $validMessage = '';
    protected $invalidState = -1;
    protected $invalidMessage = 'field_invalid';

    public function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;

        $this->valid = false;
        $this->state = 0;
        $this->message = '';
    }

    abstract public function validatePHP(&$container, &$settings);

    protected function setAnswer()
    {
        if ($this->valid) {
            $this->state = $this->validState;
            $this->message = $this->validMessage;
        } else {
            $this->state = $this->invalidState;
            $this->message = $this->wioForms
                                ->langService
                                ->getLang($this->invalidMessage);
        }
    }

    protected function getReturn()
    {
        $array = [
            'valid'   => $this->valid,
            'state'   => $this->state,
            'message' => $this->message,
        ];

        return $array;
    }

    public function print_validateJS()
    {
        $javascript = '';

        $javascript .= 'function( containerName, settings ){';
        $javascript .= 'return {valid:valid, state:state, message:message};';
        $javascript .= '}';

        return $javascript;
    }
}
