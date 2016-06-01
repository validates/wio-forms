<?php
namespace WioForms\Service\Validation;

use \WioForms\Service\Validation\LogicEquation as LogicEquationValidationService;

class Container
{
    private $wioForms;
    private $formStruct;

    private $logicEquationValidationService;

    function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;

        $this->logicEquationValidationService = new LogicEquationValidationService($this->wioForms);
    }


    public function validate(&$container)
    {
        $container['valid'] = true;
        $container['state'] = 0;
        $container['message'] = false;

        if (isset($container['validationPHP'])
            and is_array($container['validationPHP']))
        {
            foreach ($container['validationPHP'] as $validatorInfo)
            {
                if ($validatorInfo['type'] == 'method')
                {
                    if (isset($this->formStruct['ContainerValidatorsPHP'][ $validatorInfo['method'] ]['class']))
                    {
                        $className = $this->formStruct['ContainerValidatorsPHP'][ $validatorInfo['method'] ]['class'];
                    }
                    else
                    {
                        $this->wioForms->errorLog->errorLog('ContainerValidatorsPHP class name for '.$validatorInfo['method'].'  not found. ');
                        continue;
                    }

                    if (!($validatorClass = $this->wioForms->classFinderService->checkName('ContainerValidator', $className)))
                    {
                        continue;
                    }

                    $settings = [];
                    if (isset($validatorInfo['settings']))
                    {
                        $settings = $validatorInfo['settings'];
                    }

                    $validator = new $validatorClass($this->wioForms);
                    $validationResult = $validator->validatePHP($container, $settings);

                    $this->applyValidationResult($container, $validationResult);
                }
                elseif ($validatorInfo['type'] == 'logic')
                {
                    $result = $this->logicEquationValidationService->solveEquation($validatorInfo['logicEquation']);

                    $validationResult = $this->prepareLogicResult($container, $validatorInfo, $result);

                    $this->applyValidationResult($container, $validationResult);

                }
            }
            return $container['valid'];
        }
    }


    private function prepareLogicResult(&$container, $validatorInfo, $result)
    {
        $validationResult = [];
        $validationResult['valid'] = $result;
        $validationResult['state'] = $container['state'];
        $validationResult['message'] = false;

        if ($result === true)
        {
            if (isset($validatorInfo['newState']))
            {
                $validationResult['state'] = $validatorInfo['newState'];
            }
        }
        if ($result === false)
        {
            if (isset($validatorInfo['newErrorState']))
            {
                $validationResult['state'] = $validatorInfo['newErrorState'];
            }
            if (isset($validatorInfo['newErrorMessage']))
            {
                $validationResult['message'] = $validatorInfo['newErrorMessage'];
            }
        }

        return $validationResult;
    }

    private function applyValidationResult(&$container, $validationResult)
    {
        if (!(!$container['valid'] and $validationResult['valid']))
        {
            $this->changeState($container, $validationResult['state']);
            $container['valid'] = $validationResult['valid'];
            $container['message'] = $validationResult['message'];
        }
    }

    private function changeState(&$container, $newState)
    {
        if (isset($container['state']))
        {
            $oldState = $container['state'];
        }
        else
        {
            $oldState = 0;
        }

        $container['state'] = $newState;

        if (!isset($container['stateActions']))
        {
            return true;
        }

        foreach ($container['stateActions'] as $action)
        {
            if ($action['state'] == $newState)
            {
                $doIt = true;
                if (isset($action['previousAllowedStates']))
                {
                    $doIt = false;
                    foreach ($action['previousAllowedStates'] as $wantedState)
                    {
                        if ($wantedState == $oldState)
                        {
                            $doIt = true;
                            break;
                        }
                    }
                }

                if ($doIt)
                {
                    foreach ($action['actions'] as $action)
                    {
                        $this->makeAction($container, $action);
                    }

                }
            }
        }
    }

    private function makeAction(&$container, $action)
    {
        if ($action == 'hide')
        {
            $container['hidden'] = true;
        }
        elseif ($action == 'show')
        {
            unset($container['hidden']);
        }
        else
        {
            if (!isset($container['styleOptions']))
            {
                $container['styleOptions'] = [];
            }
            $container['styleOptions'][ $action ] = true;
        }
    }

}
