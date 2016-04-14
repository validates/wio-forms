<?php
namespace WioForms;

use WioForms\ErrorLog\ErrorLog;
use WioForms\ContainerRenderer;
use WioForms\DatabaseConnection;
use WioForms\DataRepository;
use WioForms\FieldRenderer;
use WioForms\FieldValidator;

use WioForms\Service\DatabaseService;
use WioForms\Service\DataRepositoryService;
use WioForms\Service\RendererService;
use WioForms\Service\ValidatorService;

class WioForms{

    # Holds ErrorLog object
    private $ErrorLog;

    # Holds table of enviroment and local settings
    public $settings;

    # Holds all informations about form structure and logic
    public $formStruct;

    # WioForms Service Obiects
    private $RendererService;
    private $DataRepositoryService;
    private $ValidatorService;
    private $DatabaseService;


    function __construct( $localSettings = false ){

        # Gets ErrorLog
        $this->ErrorLog = new ErrorLog();

        #Gets settings
        $SettingsFile = file_get_contents(__DIR__.'/enviromentSettings.json');
        $enviromentSettings = json_decode($SettingsFile, TRUE);

        if($localSettings !== false)
            $enviromentSettings = array_replace_recursive($enviromentSettings,$localSettings);
        $this->settings = $enviromentSettings;


        $this->RendererService       = new RendererService( $this );
        $this->DataRepositoryService = new DataRepositoryService( $this );
        $this->ValidatorService      = new ValidatorService( $this );
        $this->DatabaseService       = new DatabaseService( $this );


        if ($this->DatabaseService->setConnections() === false)
        {
            $this->ErrorLog->ErrorLog('Problem with: setDatabaseConnections();');
            die(' Problem with set Database Connections ');
        }
    }


    /*
    Renders the form.
    If form has multiple sites, it renders 1 site.
    Also can render "thank you" view.
    Partial entry data can also by set by hand (for example going from some link will set some field)
    */
    public function showForm( $formDataStructId = false, $permissionsArray = false, $partialEntryData = false ){

        if ( $formDataStructId === false )
        {
            $this->ErrorLog->ErrorLog('No DataStructId to search for.');
            return false;
        }

        if ( $this->DatabaseService->getFormDataStruct( $formDataStructId ) === false )
        {
            $this->ErrorLog->ErrorLog('Problem with getFormDataStructs().');
            return false;
        }


        if ( !empty($_POST['wio_forms']) ){
            $entryData = $_POST;
            $this->ValidatorService->validateForm( $entryData );

        }

        // somehow magically we know it:
        $siteNumber = 0;

        $this->RendererService->renderFormSite( $siteNumber );

    }


    /*
    Renders the filled form entry.
    If Form has multiple sites or popout containers all are shown on single site.
    Form entry can be shown in read only mode or edit mode
    It can depend on PermissionsArray or other data
    */
    public function showEntry( $formEntryId, $permissionsArray ){}


    /*
    Send by Ajax after clicking submit button on form site.
    Checks if all fields are valid and sends info back to the browser
    If form is valid then its submitted
    If form is not valid it will show validasu tion errors
    */
    public function preSubmit( $postData ){}


    /*
    Checks if form is valid
    uses showForm() to shows next page/thank you page, or the same page with errors
    saves FromEntry
    use DatabaseStore to make additional data savings
    */
    public function submit( $postData ){}


    /*
    Checks if form is valid
    uses showEntry() to show the updated entry again
    updates FormEntry
    updates all data set by DatabaseStore
    */
    public function update( $postData ){}

    /*
    prints FromEntry as PHP multilevel array
    can apply permissions of viewing fields
    */
    public function getEntryAsArray( $formEntryId, $permissionsArray ){}

}

?>
