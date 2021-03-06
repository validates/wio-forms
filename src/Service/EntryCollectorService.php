<?php

namespace WioForms\Service;

class EntryCollectorService
{
    private $wioForms;
    private $formStruct;

    public function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;
    }

    public function collectEntries($partialEntryData = false)
    {
        $entryData = [];
        if (!empty($_POST['_wioForms'])) {
            $entryData = $_POST;
        }
        foreach ($entryData as $dataIndex => $dataValue) {
            if (is_array($dataValue)) {
                $entryData[$dataIndex] = implode($dataValue, '|');
            }
        }
        if (!empty($tempSave = $this->wioForms->temporarySave->getFormData())) {
            $entryData = array_merge($tempSave, $entryData);
        }
        if (is_array($partialEntryData)) {
            $entryData = array_merge($entryData, $partialEntryData);
        }

        $this->setEntries($entryData);
    }

    public function setEntries($entryData)
    {
        $this->wioForms->entryData = $entryData;

        foreach ($this->wioForms->formStruct['Fields'] as $fieldName => &$field) {
            $fieldEntry = '';
            if (isset($entryData[$fieldName])) {
                $fieldEntry = $entryData[$fieldName];
            }
            $field['value'] = $fieldEntry;

            $field['waitForDefaultValue'] = false;
            $field['validated'] = false;
        }
    }

    public function getDefaultValuesFromDataRepositories()
    {
        foreach ($this->wioForms->formStruct['Fields'] as $fieldName => &$field) {
            if (isset($field['defaultValue'])
                and empty($field['value'])) {
                $field['waitForDefaultValue'] = true;
                $field['value'] = $this->getDefaultValue($field['defaultValue']);
            }
        }
    }

    public function getDefaultValue($defaultValue)
    {
        $repositoryName = $defaultValue['repositoryName'];
        if (isset($this->formStruct['DataRepositories'][$repositoryName]['success'])
            and !$this->formStruct['DataRepositories'][$repositoryName]['success']) {
            $this->wioForms->errorLog->errorLog('getDefaultValue: Repository "'.$repositoryName.'" not ended with success.');

            return false;
        }

        $value = $this->formStruct['DataRepositories'][$repositoryName]['data'];

        if (isset($defaultValue['subset'])) {
            $subset = $defaultValue['subset'];
            foreach ($subset as $branchName) {
                if (!isset($value[$branchName])) {
                    $this->wioForms->errorLog->errorLog('getDefaultValue: wrong data subset in '.$repositoryName.'.');

                    return false;
                }
                $value = $value[$branchName];
            }
        }

        if (isset($defaultValue['converter'])) {
            $converterName = $defaultValue['converter'];
            $converterClass = $this->wioForms->classFinderService->checkName('FieldConverter', $converterName);

            if (!$converterClass) {
                $this->wioForms->errorLog->errorLog('getDefaultValue: ConverterClass '.$converterName.' not found.');

                return false;
            }

            $converter = new $converterClass();
            $value = $converter->convert($value);
        }

        return $value;
    }
}
