<?php

namespace WioForms\Service\Validation;

class Field
{
    private $wioForms;
    private $formStruct;

    public function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;
    }

    public function validate($fieldName)
    {
        $field = &$this->formStruct['Fields'][$fieldName];

        $field['valid'] = true;
        $field['state'] = 1;
        $field['message'] = false;

        if (isset($field['validationPHP']) and is_array($field['validationPHP'])) {
            foreach ($field['validationPHP'] as $validatorInfo) {
                if (isset($this->formStruct['FieldValidatorsPHP'][$validatorInfo['method']]['class'])) {
                    $className = $this->formStruct['FieldValidatorsPHP'][$validatorInfo['method']]['class'];
                } else {
                    $this->wioForms->errorLog->errorLog('FieldValidatorsPHP class name for '.$validatorInfo['method'].' not found. ');
                    continue;
                }

                if (!($validatorClass = $this->wioForms->classFinderService->checkName('FieldValidator', $className))) {
                    continue;
                }

                $settings = [];
                if (isset($validatorInfo['settings'])) {
                    $settings = $validatorInfo['settings'];
                }

                $validator = new $validatorClass($this->wioForms);
                $validationResult = $validator->validatePHP($field['value'], $settings);

                $this->applyValidationResult($field, $validationResult);
            }
        }

        return $field['valid'];
    }

    private function applyValidationResult(&$field, $validationResult)
    {
        if (!(!$field['valid'] and $validationResult['valid'])) {
            $field['state'] = $validationResult['state'];
            $field['valid'] = $validationResult['valid'];

            if ($validationResult['valid'] === false
                and isset($validatorInfo['newErrorMessage'])) {
                $field['message'] = $this->wioForms
                                        ->langService
                                        ->getLang($validatorInfo['newErrorMessage']);
            } else {
                $field['message'] = $validationResult['message'];
            }
        }
    }
}
