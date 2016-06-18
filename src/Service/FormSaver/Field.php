<?php

namespace WioForms\Service\FormSaver;

class Field
{
    private $wioForms;
    private $formStruct;

    private $formSaver;

    public function __construct($wioFormsObject, $formSaverServiceObject)
    {
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;

        $this->formSaver = $formSaverServiceObject;
    }

    public function get(&$saveField)
    {
        switch ($saveField['type']) {
            case 'insertedId':
                $value = $this->getInsertedId($saveField);
            break;
            case 'const':
                $value = $this->getConst($saveField);
            break;
            case 'field':
                $value = $this->getField($saveField);
            break;
            default:
                $value = false;
        }

        if (isset($saveField['converter'])) {
            $value = $this->getConverted($value, $saveField['converter']);
        }

        return $value;
    }

    private function getInsertedId(&$saveField)
    {
        if (!isset($saveField['table'])) {
            $this->wioForms->errorLog->errorLog('getField::InsertedId: no "table" field.');

            return false;
        }

        $entries = $this->formSaver->databaseEntries[$saveField['table']];

        return $entries[count($entries) - 1];
    }

    private function getConst(&$saveField)
    {
        if (!isset($saveField['const'])) {
            $this->wioForms->errorLog->errorLog('getField::InsertedId: no "const" field.');

            return false;
        }

        return $saveField['const'];
    }

    private function getField(&$saveField)
    {
        if (!isset($saveField['field'])) {
            $this->wioForms->errorLog->errorLog('getField::InsertedId: no "field" field.');

            return false;
        }

        return $this->formStruct['Fields'][$saveField['field']]['value'];
    }

    private function getConverted($value, $converterName)
    {
        $converterClass = $this->wioForms->classFinderService->checkName('FieldConverter', $converterName);

        if (!$converterClass) {
            $this->wioForms->errorLog->errorLog('getFieldConvert: ConverterClass '.$converterName.' not found.');

            return false;
        }

        $converter = new $converterClass();

        return $converter->convert($value);
    }
}
