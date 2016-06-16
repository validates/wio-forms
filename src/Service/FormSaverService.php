<?php

namespace WioForms\Service;

use WioForms\Service\FormSaver\Field as FieldSaverService;
use WioForms\Service\Validation\Container as ContainerValidationService;

class FormSaverService
{
    private $wioForms;
    private $formStruct;

    private $clearTemporarySave;

    public $databaseEntries;

    private $fieldSaver;

    private $databaseConneciton;

    public function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;

        $this->containerValidationService = new ContainerValidationService($wioFormsObject);

        $this->clearTemporarySave = false;

        $this->databaseEntries = [];

        $this->fieldSaver = new FieldSaverService($wioFormsObject, $this);

        $this->databaseConnection = false;
    }

    public function getClearTemporarySave()
    {
        return $this->clearTemporarySave;
    }

    public function tryFormSavers()
    {
        $FormSavers = &$this->formStruct['FormSavers'];

        foreach ($FormSavers as $FormSaverName => &$FormSaver) {
            $valid = $this->validateFormSaver($FormSaver);

            if ($valid) {
                $this->saveForm($FormSaver);
            }
        }
    }

    private function validateFormSaver(&$FormSaver)
    {
        return $this->containerValidationService->validate($FormSaver);
    }

    private function saveForm(&$FormSaver)
    {
        $this->setDatabaseConnection($FormSaver);
        $this->saveEntry();

        foreach ($FormSaver['DatabaseSaves'] as &$databaseSave) {
            $valid = $this->validateDatabaseSave($databaseSave);

            if ($valid) {
                $this->makeDatabaseSave($databaseSave);
            }
        }

        $this->updateDatabaseEntries();

        if (isset($FormSaver['clearTemporarySave'])) {
            $this->clearTemporarySave = $FormSaver['clearTemporarySave'];
        }
    }

    private function setDatabaseConnection(&$FormSaver)
    {
        $this->databaseConnection = $this->wioForms->databaseService->connections[$FormSaver['databaseConnection']];
    }

    private function validateDatabaseSave(&$databaseSave)
    {
        if (isset($databaseSave['validationPHP'])) {
            return $this->containerValidationService->validate($databaseSave);
        }

        return true;
    }

    private function saveEntry()
    {
        $fieldsValues = [];
        foreach ($this->formStruct['Fields'] as $fieldName => &$field) {
            $fieldsValues[$fieldName] = $field['value'];
        }

        $query = [
            'table' => 'wio_forms_entries',
            'insert' => [
                'form_struct_id' => 'example-rlr-2016',
                'previous_version' => -1,
                'is_current_version' => 1,
                'database_entries' => '',
                'entry_data' => json_encode($fieldsValues),
            ],
        ];

        $insertedId = $this->databaseConnection->insert($query);

        $this->databaseEntries['wio_forms_entries'][0] = $insertedId;
    }

    private function updateDatabaseEntries()
    {
        $databaseEntries = &$this->databaseEntries;

        if (!isset($databaseEntries['wio_forms_entries'][0])) {
            $this->wioForms->errorLog->errorLog('updateDatabaseEntries: Main wioFormsEntries insertedId not found.');
        }
        $wioFormsEntryId = $databaseEntries['wio_forms_entries'][0];

        unset($databaseEntries['wio_forms_entries']);

        if (!empty($databaseEntries)) {
            $query = [
                'table' => 'wio_forms_entries',
                'where' => [
                    'id' => '$wioFormsEntryId',
                ],
                'update' => [
                    'database_entries' => json_encode($databaseEntries),
                ],
            ];

            $this->databaseConnection->update($query);
        }
    }

    private function makeDatabaseSave(&$databaseSave)
    {
        $query = [
            'table' => $databaseSave['tableName'],
            $databaseSave['type'] => [],
        ];

        foreach ($databaseSave['fields'] as $saveFieldName => &$saveField) {
            $fieldValue = $this->fieldSaver->get($saveField);
            if ($fieldValue !== false) {
                $query[$databaseSave['type']][$saveFieldName] = $fieldValue;
            } else {
                $this->wioForms->errorLog->errorLog('makeDatabaseSave: field '.$saveFieldName.' for '.$databaseSave['tableName'].' not get properly.');

                return false;
            }
        }
        if ($databaseSave['type'] === 'update') {
            $updateOn = reset(array_keys($databaseSave['where']));
            $query['where'] = [
                $updateOn => $this->fieldSaver->get($databaseSave['where'][$updateOn]),
            ];
        }


        $insertedId = $this->databaseConnection->$databaseSave['type']($query);

        $this->databaseEntries[$databaseSave['tableName']][] = $insertedId;
    }
}
