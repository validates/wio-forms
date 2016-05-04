<?php
namespace WioForms\Service;

class DataRepositoryService
{
    public $wioForms;
    public $formStruct;


    function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;
    }


    public function getForeignDataRepositories()
    {
        foreach ($this->formStruct['DataRepositories'] as $dataRepositoryName => &$repository)
        {
            if ($repository['type'] == 'foreign')
            {
                $this->getForeignDataRepository($dataRepositoryName);
            }
        }

        var_dump($this->formStruct['DataRepositories']);
    }

    private function getForeignDataRepository($dataRepositoryName)
    {
        $repository = &$this->formStruct['DataRepositories'][$dataRepositoryName];

        $dataRepositoryClass = $this->wioForms->classFinderService->checkName('DataRepository', $repository['class']);

        if (!$dataRepositoryClass)
        {
            $this->wioForms->errorLog->errorLog('DataRepository Class: '.$repository['class'].' not found.');
            return false;
        }

        $dataRepository = new $dataRepositoryClass($this->wioForms, $dataRepositoryName);

        $requiredFields = $this->checkRequiredFields($repository);
        if ($requiredFields === false)
        {
            $this->wioForms->errorLog->errorLog('RequiredFields for DataRepository not valid.');
            return false;
        }

        $repository['data'] = $dataRepository->getData($requiredFields);

    }

    private function checkRequiredFields(&$repository)
    {
        $requiredFields = [];
        if (!isset($repository['requiredFields']))
        {
            return $requiredFields;
        }

        $allValid = true;
        foreach ($repository['requiredFields'] as $requiredFieldName => $fieldName)
        {
            if ($this->formStruct['Fields'][$fieldName]['valid'])
            {
                $requiredFields[$requiredFieldName] = $this->formStruct['Fields'][$fieldName]['value'];
            }
            else
            {
                $allValid = false;
                break;
            }
        }

        if ($allValid)
        {
            return $requiredFields;
        }
        return false;
    }

}
