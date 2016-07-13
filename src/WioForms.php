<?php

namespace WioForms;

use WioForms\ErrorLog\ErrorLog;
use WioForms\Service\ClassFinderService;
use WioForms\Service\DatabaseService;
use WioForms\Service\DataRepositoryService;
use WioForms\Service\EntryCollectorService;
use WioForms\Service\FormSaverService;
use WioForms\Service\HeaderCollectorService;
use WioForms\Service\LangService;
use WioForms\Service\RendererService;
use WioForms\Service\StyleManagementService;
use WioForms\Service\ValidatorService;

class WioForms
{
    // Holds ErrorLog object
    public $errorLog;

    // Holds TemporarySave object
    public $temporarySave;

    // Holds table of enviroment and local settings
    public $settings;

    // Holds all informations about form structure and logic
    public $formStruct;

    // Lists of information what Field and Container lays where
    public $containersContains;

    // Holds entryData
    public $entryData;


    // WioForms Service Objects
    public $rendererService;
    public $dataRepositoryService;
    public $validatorService;
    public $databaseService;
    public $classFinderService;
    public $styleManagementService;
    public $headerCollectorService;
    public $entryCollectorService;
    public $formSaverService;
    public $langService;

    public function __construct($localSettings = false)
    {

        // Gets ErrorLog
        $this->errorLog = new ErrorLog();

        //Gets settings
        $settingsFile = file_get_contents(__DIR__.'/enviromentSettings.json');
        $enviromentSettings = json_decode($settingsFile, true);

        if ($localSettings !== false) {
            $enviromentSettings = array_replace_recursive($enviromentSettings, $localSettings);
        }
        $this->settings = $enviromentSettings;

        $this->rendererService = new RendererService($this);
        $this->dataRepositoryService = new DataRepositoryService($this);
        $this->validatorService = new ValidatorService($this);
        $this->databaseService = new DatabaseService($this);
        $this->classFinderService = new ClassFinderService($this->errorLog);
        $this->styleManagementService = new StyleManagementService($this);
        $this->headerCollectorService = new HeaderCollectorService($this);
        $this->entryCollectorService = new EntryCollectorService($this);
        $this->formSaverService = new FormSaverService($this);
        $this->langService = new LangService($this);

        if ($this->databaseService->setConnections() === false) {
            $this->errorLog->errorLog('Problem with: setDatabaseConnections();');
            die('Problem with set Database Connections');
        }

        // later on we should get that from config file or formStruct:
        $temporarySaveClass = '\WioForms\TemporarySave\Session';
        $this->temporarySave = new $temporarySaveClass($this);
    }

    public function showForm($formDataStructId = false, $permissionsArray = false, $partialEntryData = false)
    {
        if ($formDataStructId === false) {
            $this->errorLog->errorLog('No DataStructId to search for.');

            return false;
        }

        if ($this->databaseService->getFormDataStruct($formDataStructId) === false) {
            $this->errorLog->errorLog('Problem with getFormDataStructs().');

            return false;
        }

        $this->rendererService->createContainersContains();

        $this->entryCollectorService->collectEntries($partialEntryData);


        $this->validatorService->validateFields();
        $this->dataRepositoryService->getForeignDataRepositories();
        $this->entryCollectorService->getDefaultValuesFromDataRepositories();
        $this->validatorService->validateFields();


        $this->validatorService->validateContainers();
        $this->formSaverService->tryFormSavers();
        $this->validatorService->validateContainers();


        if ($this->formSaverService->getClearTemporarySave()) {
            $this->temporarySave->clearFormData();
        } else {
            $this->temporarySave->saveFormData();
        }

        $lastEditedSite = $this->validatorService->getLastEditedSite();
        $this->styleManagementService->dontShowErrorsOnSite($lastEditedSite);

        $siteNumber = $this->validatorService->getAvaliableSiteNumber();
        $formHtml = $this->rendererService->renderFormSite($siteNumber);

        return $formHtml;
    }

    public function getHeaders($dir = '')
    {
        return $this->headerCollectorService->getHeaders($dir);
    }
}
