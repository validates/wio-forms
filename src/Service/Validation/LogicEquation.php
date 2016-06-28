<?php

namespace WioForms\Service\Validation;

class LogicEquation
{
    public $wioForms;
    public $formStruct;

    public function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;
    }

    public function solveEquation($sentence)
    {
        $result = $this->solveSentence($sentence);

        return $result;
    }

    private function solveSentence($sentence)
    {
        if (isset($sentence['data'])
            and is_array($sentence['data'])) {
            foreach ($sentence['data'] as $i => $subSentence) {
                $sentence['data'][$i] = $this->solveSentence($subSentence);
            }
        }

        $result = true;
        switch ($sentence['type']) {
            case 'fieldValue':
                $result = $this->getFieldValue($sentence); break;
            case 'repositoryValue':
                $result = $this->getRepositoryValue($sentence); break;
            case 'const':
                $result = $this->getConst($sentence); break;
            case 'equal':
                $result = $this->getEqual($sentence); break;
            case 'and':
                $result = $this->getAnd($sentence); break;
            case 'or':
                $result = $this->getOr($sentence); break;
            case 'isValidField':
                $result = $this->getIsValidField($sentence); break;
            case 'isNotValidField':
                $result = $this->getIsNotValidField($sentence); break;
            case 'isValidContainer':
                $result = $this->getIsValidContainer($sentence); break;
            case 'isNotValidContainer':
                $result = $this->getIsNotValidContainer($sentence); break;
            case 'isSuccessRepository':
                $result = $this->getIsSuccessRepository($sentence); break;
            case 'isNotSuccessRepository':
                $result = $this->getIsNotSuccessRepository($sentence); break;
            case 'runMethod':
                $result = $this->getRunMethod($sentence); break;
            default:
                $this->wioForms->errorLog->errorLog('LogicEquationError: No "'.$sentence['type'].'" sentence type.');
        }

        return $result;
    }

    private function getFieldValue($sentence)
    {
        $result = $this->formStruct['Fields'][$sentence['field']]['value'];

        return $result;
    }

    private function getRepositoryValue($sentence)
    {
        $result = $this->wioForms->entryCollectorService->getDefaultValue($sentence);

        return $result;
    }

    private function getConst($sentence)
    {
        $result = $sentence['const'];

        return $result;
    }

    private function getEqual($sentence)
    {
        $result = true;
        for ($i = 1; $i < count($sentence['data']); ++$i) {
            if ($sentence['data'][$i] != $sentence['data'][($i - 1)]) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    private function getAnd($sentence)
    {
        $result = true;
        foreach ($sentence['data'] as $element) {
            if (!($element)) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    private function getOr($sentence)
    {
        $result = false;
        foreach ($sentence['data'] as $element) {
            if ($element) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    private function getIsNotValidField($sentence)
    {
        $result = false;
        if (isset($this->formStruct['Fields'][$sentence['field']]['valid'])
            and !$this->formStruct['Fields'][$sentence['field']]['valid']) {
            $result = true;
        }

        return $result;
    }

    private function getIsValidField($sentence)
    {
        $result = false;
        if (isset($this->formStruct['Fields'][$sentence['field']]['valid'])
            and $this->formStruct['Fields'][$sentence['field']]['valid']) {
            $result = true;
        }

        return $result;
    }

    private function getIsNotValidContainer($sentence)
    {
        $result = false;
        if (isset($this->formStruct['Containers'][$sentence['container']]['valid'])
            and !$this->formStruct['Containers'][$sentence['container']]['valid']) {
            $result = true;
        }

        return $result;
    }

    private function getIsValidContainer($sentence)
    {
        $result = false;
        if (isset($this->formStruct['Containers'][$sentence['container']]['valid'])
            and $this->formStruct['Containers'][$sentence['container']]['valid']) {
            $result = true;
        }

        return $result;
    }

    private function getIsNotSuccessRepository($sentence)
    {
        $result = false;
        if (isset($this->formStruct['DataRepositories'][$sentence['repository']]['success'])
            and !$this->formStruct['DataRepositories'][$sentence['repository']]['success']) {
            $result = true;
        }

        return $result;
    }

    private function getIsSuccessRepository($sentence)
    {
        $result = false;
        if (isset($this->formStruct['DataRepositories'][$sentence['repository']]['success'])
            and $this->formStruct['DataRepositories'][$sentence['repository']]['success']) {
            $result = true;
        }

        return $result;
    }

    private function getRunMethod($sentence)
    {
        $result = false;

        if (!isset($this->formStruct['ContainerValidatorsPHP'][$sentence['method']]['class'])) {
            return false;
        }
        $className = $this->formStruct['ContainerValidatorsPHP'][$sentence['method']]['class'];


        if (!($validatorClass = $this->wioForms->classFinderService->checkName('ContainerValidator', $className))) {
            return false;
        }

        $settings = [];
        if (isset($sentence['settings'])) {
            $settings = $sentence['settings'];
        }

        $validator = new $validatorClass($this->wioForms);
        $validationResult = $validator->validatePHP($container, $settings);

        $result = $validationResult['valid'];

        return $result;
    }
}
