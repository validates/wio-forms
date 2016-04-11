<?php
namespace WioForms;

use WioForms\ErrorLog\ErrorLog;
use WioForms\ContainerRenderer;
use WioForms\DatabaseConnection;
use WioForms\DataRepository;
use WioForms\FieldRenderer;
use WioForms\FieldValidator;

class WioForms{

    # Holds ErrorLog obiect
    private $ErrorLog;

    # Holds table of enviroment and local settings
    private $settings;

    # Holds table of obiects used for database connections
    private $DatabaseConnections;

    # Holds all informations about form structure and logic
    public $formStruct;

    # Lists of information what Field and Container lays where
    private $ContainersContains;

    # Holds html to display
    private $outputHtml;


    function __construct( $localSettings = false ){ # Done

        # Gets ErrorLog
        $this->ErrorLog = new ErrorLog();

        #Gets settings
        $SettingsFile = file_get_contents(__DIR__.'/enviromentSettings.json');
        $enviromentSettings = json_decode($SettingsFile, TRUE);

        if($localSettings !== false)
            $enviromentSettings = array_replace_recursive($enviromentSettings,$localSettings);
        $this->settings = $enviromentSettings;


        # sets DatabaseConnections
        if ($this->setDatabaseConnections() === false)
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

        if ( $this->getFormDataStruct( $formDataStructId ) === false )
        {
            $this->ErrorLog->ErrorLog('Problem with getFormDataStructs().');
            return false;
        }

        $this->outputHtml = '';

        // somehow magically we know it:
        $siteNumber = 0;

        $this->renderFormSite( $siteNumber );


        echo $this->outputHtml;
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



    ### PRIVATE METHODS:

    /*
    this function get FormSettings and bind in to out obiect
    its used by all public method
    its used directly in showForm()
    in showEntry() and getEntryAsArray() we get $formDataStructID from database
    in preSubmit(), submit() and update() we get $formDataStuctID from POST
    */
    private function getFormDataStruct( $formDataStructId ){  # Done

        $databaseQuery =
        [
            'table' => 'WioForms_formStruct',
            'where' => 'formStructId == "'.$formDataStructId.'"',
        ];

        $queryResult = $this->DatabaseConnections['Main']->SelectOne( $databaseQuery );

        if ( $queryResult == 'false' )
        {
            $this->ErrorLog->ErrorLog('Cannot get DataStruct from Database.');
            return false;
        }

        $this->formStruct = json_decode( $queryResult['dataStruct'], true );
        if ( json_last_error()!= JSON_ERROR_NONE )
        {
            $this->ErrorLog->ErrorLog('Problem with JSON validation of formStruct file.');
            return false;
        }
        $this->getFieldsContainerLists();
        return true;
    }

    private function getFieldsContainerLists(){  # Done
        $this->ContainersContains = [];

        foreach (['Fields','Containers'] as $ElemType)
        {
            foreach ($this->formStruct[$ElemType]  as $ElemName => $Elem )
            {
                $cont = $Elem['container'];
                $pos = $Elem['position'];
                if ( !isset( $this->ContainersContains[$cont] ))
                {
                    $this->ContainersContains[$cont] = [];
                }
                if ( isset( $this->ContainersContains[$cont][$pos] ))
                {
                    $this->ErrorLog->ErrorLog('Doubled position for '.$ElemType.'::'.$ElemName.' in container '.$cont.'.');
                }
                else
                {
                    $this->ContainersContains[$cont][$pos] = [
                        "name" => $ElemName,
                        "type" => $ElemType
                    ];
                }
            }
        }
        foreach ($this->ContainersContains as $Key => $Array)
        {
            ksort($this->ContainersContains[ $Key ]);
        }
    }



    ### Data and functions:

    /*
    this function collect methods of getting Foreign Data Repository
    */
    private function prepareDataRepositories( ){}

    /*
    this function runs mothod of getting Foreign Data Repository and fills "Data" field
    */
    private function getForeignDataRepository( $dataRepositoryName ){}

    /*
    this function collect Foreign Functions
    */
    private function prepareFunctionRepositories( ){}


    ### Render Views:

    private function renderFormSite( $siteNumber ){  # Done

        foreach ($this->ContainersContains['main'] as $ElemData)
        {
            if ($ElemData['type'] == 'Fields'){
                $this->ErrorLog->ErrorLog('We have Field directly in "main" container.');
                continue;
            }
            $Container = $this->formStruct['Containers'][ $ElemData['name'] ];
            if ( $Container['site'] == $siteNumber )
            {
                $this->renderContainer( $ElemData['name'] );

            }
        }
     }

    private function renderField( $FieldName ){ # Done
      $Field = $this->formStruct['Fields'][ $FieldName ];

      $className = '\WioForms\FieldRenderer\\'.$Field['type'];
      if ( class_exists($className) ) {
          $FieldClass = new $className( $FieldName, $this );
      }
      else
      {
          $this->ErrorLog->ErrorLog('Class '.$className.' not found.');
          return false;
      }

      $this->outputHtml .= $FieldClass->ShowToEdit();
    }

    private function renderContainer( $ContainerName ){  # Done
        $Cont = $this->formStruct['Containers'][ $ContainerName ];

        $className = '\WioForms\ContainerRenderer\\'.$Cont['displayType'];
        if ( class_exists($className) ) {
            $ContainerClass = new $className( $ContainerName, $this );
        }
        else
        {
            $this->ErrorLog->ErrorLog('Class '.$className.' not found.');
            return false;
        }

        $this->outputHtml .= $ContainerClass->ShowHead();

        if ( isset($this->ContainersContains[ $ContainerName ]) ){
            foreach ($this->ContainersContains[ $ContainerName ] as $ElemData)
            {
                if ( $ElemData['type'] == 'Containers' )
                {
                    $this->renderContainer( $ElemData['name'] );
                }
                if ( $ElemData['type'] == 'Fields' )
                {
                    $this->renderField( $ElemData['name'] );
                }
            }
        }

        $this->outputHtml .= $ContainerClass->ShowTail();
    }

    private function addFunctionsToJavaScript( ){}

    /*
    prints all javascript code needed to show the form
    adds all validation functions
    */
    private function renderJavaScript( ){}


    ### Validation:

    /*
    runs by preSubmit(), submit(), update()
    checks all fields and all containers for validation errors
    */
    private function validateForm( ){}


    /*
    checks validation of field
    can use Foreign Functions
    can use checkIfDataInRepository()
    */
    private function validateField( $fieldName ){}


    /*
    checks if Data are maching Data Repository (We dont want people born 37th of September)
    */
    private function checkIfDataInRepository( $fieldName ){    }


    /*
    checks validation of container
    can use Foreign Functions
    can use solveLogicEquations( )
    */
    private function validateContainer( $containerName ){}

    /*
    function solving logic equasion in validateContainer
    */
    private function solveLogicEquation( $containerName, $validator ){}

    private function getDataStruct( $formDataStructId ){}



    ### Saving forms:

    private function setDatabaseConnections(){  # Done
        foreach ($this->settings['DatabaseConnections'] as $DBconn_Name => $DBconn_Data)
        {
            $className = '\WioForms\DatabaseConnection\\'.$DBconn_Name;
            if ( class_exists($className) ) {
                $this->DatabaseConnections[ $DBconn_Name ] = new $className( $DBconn_Data );
            }
            else {
                $this->ErrorLog->ErrorLog('There is no '.$className.' class');
                return false;
            }
        }
        return true;
    }


    /*
    used for saveEntry() and updateEntry()
    produces FormEntry format from $postData
    */
    private function getEntryFromPost( $postData ){}


    /*
    Used by submit()
    It uses main channel of DB communication and puts FormEntry there
    */
    private function saveEntry( $formEntryFormat ){}

    /*
    Used by submit()
    It uses main channel of DB communication and puts FormEntry there
    */
    private function updateEntry( $formEntryFormat ){}


    /*
    this function collects all DatabaseStore Input and Update queries
    it saves them to database by DatabaseStore connections
    */
    private function advancedEntrySave( $postData ){}


    /*
    makes similar thing to DatabaseStore_save()
    changes Input queries for Update queries
    */
    private function advancedEntryUpdate( $postData ){}

}

/*
CREATE TABLE `WioForms_formStruct` (
    `formStructId` VARCHAR(128) NOT NULL ,
    `name` VARCHAR(128) NOT NULL ,
    `used` TINYINT(4) NOT NULL ,
    `dataStruct` TEXT NOT NULL ,
    PRIMARY KEY (`formId`)
) ENGINE = InnoDB;

CREATE TABLE `WioForms_entries` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `formStructId` VARCHAR(128) NOT NULL ,
    `dateAdded`
    `previousVersion`
    `isCurrentVersion`
    `entryDatat` TEXT NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

*/

?>
