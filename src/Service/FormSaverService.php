<?php
namespace WioForms\Service;

use \WioForms\Service\Validation\Container as ContainerValidationService;

use \WioForms\Service\FormSaver\Field as FieldSaverService;

class FormSaverService
{
    private $wioForms;
    private $formStruct;

    private $clearTemporarySave;

    private $databaseEntries;

    private $fieldSaver;

    function __construct($wioFormsObject){
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;

        $this->containerValidationService = new ContainerValidationService($wioFormsObject);

        $this->clearTemporarySave = false;

        $this->databaseEntries = [];

        $this->fieldSaver = new FieldSaverService($wioFormsObject, $this);
    }

    public function getClearTemporarySave()
    {
        return $this->clearTemporarySave;
    }

    public function tryFormSavers()
    {
        $FormSavers = &$this->formStruct['FormSavers'];

        foreach ($FormSavers as $FormSaverName => &$FormSaver)
        {
            $valid = $this->validateFormSaver($FormSaver);

            if ($valid)
            {
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
        $this->saveEntry();

        foreach ($FormSaver['DatabaseSaves'] as &$databaseSave)
        {
            $valid = $this->validateDatabaseSave($databaseSave);

            if ($valid)
            {
                $this->makeDatabaseSave($databaseSave);
            }
        }

        $this->updateDatabaseEntries();
    }

    private function validateDatabaseSave(&$databaseSave)
    {
        if (isset($databaseSave['validationPHP']))
        {
            return $this->containerValidationService->validate($databaseSave);
        }
        return true;
    }


    private function saveEntry()
    {
        $fieldsValues = [];
        foreach ($this->formStruct['Fields'] as $fieldName=>&$field)
        {
            $fieldsValues[$fieldName] = $field['value'];
        }

        $query = [
            'table' => 'wio_forms_entries',
            'insert' => [
                'form_struct_id' => 'example-rlr-2016',
                'previous_version' => -1,
                'is_current_version' => 1,
                'database_entries' => '',
                'entry_data' => json_encode($fieldsValues)
            ]
        ];

        // ... DatabaseQuery?
        $insertedId = 1337;

        $this->databaseEntries['wio_forms_entries'][0] = $insertedId;

    }

    private function updateDatabaseEntries()
    {
        $databaseEntries = &$this->databaseEntries;

        if (!isset($databaseEntries['wio_forms_entries'][0]))
        {
            $this->wioForms->errorLog->errorLog('updateDatabaseEntries: Main wioFormsEntries insertedId not found.');
        }
        $wioFormsEntryId = $databaseEntries['wio_forms_entries'][0];

        unset($databaseEntries['wio_forms_entries']);

        if (!empty($databaseEntries))
        {
            $query = [
                'table' => 'wio_forms_entries',
                'where' => [
                    'id' => '$wioFormsEntryId'
                ],
                'update' => [
                    'database_entries' => json_encode($databaseEntries)
                ]
            ];

            // ... DatabaseQuery?
        }
    }

    private function makeDatabaseSave(&$databaseSave)
    {
        $query = [
            'table' => $databaseSave['tableName'],
            'insert' => []
        ];

        foreach ($databaseSave['fields'] as $saveFieldName => &$saveField)
        {
            $fieldValue = $this->fieldSaver->get($saveField);
            if ($fieldValue !== false)
            {
                $query['insert'][ $saveFieldName ] = $fieldValue;
            }
            else
            {
                $this->wioForms->errorLog->errorLog('makeDatabaseSave: field '.$saveFieldName.' for '.$databaseSave['tableName'].' not get properly.');
                return false;
            }
        }

        //... DatabaseQuery?
        $insertedId = 1337;
        $this->databaseEntries[ $databaseSave['tableName'] ][] = $insertedId;
    }

}
