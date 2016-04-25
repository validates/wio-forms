<?php
namespace WioForms;

use WioForms\ErrorLog\ErrorLog;
use WioForms\ContainerRenderer;
use WioForms\DatabaseConnection;
use WioForms\DataRepository;
use WioForms\FieldRenderer;
use WioForms\FieldValidator;
use WioForms\TemporarySave;

use WioForms\Service\DatabaseService;
use WioForms\Service\DataRepositoryService;
use WioForms\Service\RendererService;
use WioForms\Service\ValidatorService;
use WioForms\Service\ClassFinderService;
use WioForms\Service\StyleManagementService;


class WioForms{

    # Holds ErrorLog object
    public $errorLog;

    # Holds TemporarySave object
    public $temporarySave;

    # Holds table of enviroment and local settings
    public $settings;

    # Holds all informations about form structure and logic
    public $formStruct;

    # Lists of information what Field and Container lays where
    public $containersContains;

    # WioForms Service Objects
    public $rendererService;
    public $dataRepositoryService;
    public $validatorService;
    public $databaseService;
    public $classFinderService;
    public $styleManagementService;


    function __construct($localSettings = false){

        # Gets ErrorLog
        $this->errorLog = new ErrorLog();

        #Gets settings
        $settingsFile = file_get_contents(__DIR__.'/enviromentSettings.json');
        $enviromentSettings = json_decode($settingsFile, TRUE);

        if ($localSettings !== false)
            $enviromentSettings = array_replace_recursive($enviromentSettings,$localSettings);
        $this->settings = $enviromentSettings;

        $this->rendererService            = new RendererService($this );
        $this->dataRepositoryService      = new DataRepositoryService($this);
        $this->validatorService           = new ValidatorService($this);
        $this->databaseService            = new DatabaseService($this);
        $this->classFinderService         = new ClassFinderService($this->errorLog);
        $this->styleManagementService     = new StyleManagementService($this);


        if ($this->databaseService->setConnections() === false)
        {
            $this->errorLog->errorLog('Problem with: setDatabaseConnections();');
            die('Problem with set Database Connections');
        }

        // later on we should get that from config file or formStruct:
        $temporarySaveClass = '\WioForms\TemporarySave\Cookie';
        $this->temporarySave = new $temporarySaveClass($this);

    }


    /*
    Renders the form.
    If form has multiple sites, it renders 1 site.
    Also can render "thank you" view.
    Partial entry data can also by set by hand (for example going from some link will set some field)
    */
    public function showForm($formDataStructId = false, $permissionsArray = false, $partialEntryData = false)
    {

        if ($formDataStructId === false)
        {
            $this->errorLog->errorLog('No DataStructId to search for.');
            return false;
        }

        if ($this->databaseService->getFormDataStruct($formDataStructId) === false)
        {
            $this->errorLog->errorLog('Problem with getFormDataStructs().');
            return false;
        }

        $this->rendererService->createContainersContains();


        $entryData = [];
        if (!empty($_POST['_wioForms']))
        {
            $entryData = $_POST;
        }
        if (!empty($tempSave = $this->temporarySave->getFormData()))
        {
            $entryData = array_merge($tempSave, $entryData);
        }
        if ($partialEntryData and is_array($partialEntryData))
        {
            $entryData = array_merge($entryData, $partialEntryData);
        }
        $this->validatorService->validateForm($entryData);


        if (false) // We wanna save this form now
        {
            $this->temporarySave->clearFormData();
            // here we perform saving action
        }
        else
        {
            $this->temporarySave->saveFormData();
        }

        $lastEditedSite = $this->validatorService->getLastEditedSite();
        $this->styleManagementService->dontShowErrorsOnSite($lastEditedSite);

        $siteNumber = $this->validatorService->getAvaliableSiteNumber();
        $formHtml = $this->rendererService->renderFormSite($siteNumber);

        return $formHtml;
    }


    /*
    Renders the filled form entry.
    If Form has multiple sites or popout containers all are shown on single site.
    Form entry can be shown in read only mode or edit mode
    It can depend on PermissionsArray or other data
    */
    public function showEntry($formEntryId, $permissionsArray){}


    /*
    Send by Ajax after clicking submit button on form site.
    Checks if all fields are valid and sends info back to the browser
    If form is valid then its submitted
    If form is not valid it will show validasu tion errors
    */
    public function preSubmit($postData){}


    /*
    Checks if form is valid
    uses showForm() to shows next page/thank you page, or the same page with errors
    saves FromEntry
    use DatabaseStore to make additional data savings
    */
    public function submit($postData){}


    /*
    Checks if form is valid
    uses showEntry() to show the updated entry again
    updates FormEntry
    updates all data set by DatabaseStore
    */
    public function update($postData){}

    /*
    prints FromEntry as PHP multilevel array
    can apply permissions of viewing fields
    */
    public function getEntryAsArray($formEntryId, $permissionsArray){}

}

?>
