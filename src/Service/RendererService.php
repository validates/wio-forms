<?php
namespace WioForms\Service;

use WioForms\FormRenderer\FormRenderer;

class RendererService
{
    # Holds FormRenderer object
    private $FormRenderer;

    # Holds html to display
    private $outputHtml;

    # Lists of information what Field and Container lays where
    private $ContainersContains;

    public $WioForms;
    public $formStruct;


    function __construct( $WioFormsObiect ){
        $this->WioForms = $WioFormsObiect;

        # Gets FormRenderer
        $this->FormRenderer = new FormRenderer( $this->WioForms );

        $this->outputHtml = '';
    }

    public function renderFormSite( $siteNumber ){
        $this->formStruct = $this->WioForms->formStruct;

        $this->createContainersContains();

        $this->outputHtml .= $this->FormRenderer->showHead();

        foreach ($this->ContainersContains['main_'.$siteNumber] as $ElemData)
        {
            if ($ElemData['type'] == 'Fields'){
                $this->ErrorLog->ErrorLog('We have Field directly in "main_'.$siteNumber.'" container.');
                continue;
            }
            $Container = $this->formStruct['Containers'][ $ElemData['name'] ];
            if ( $Container['site'] == $siteNumber )
            {
                $this->renderContainer( $ElemData['name'] );
            }
        }

        $this->outputHtml .= $this->FormRenderer->showTail();

        echo $this->outputHtml;
    }

    private function renderField( $FieldName ){
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

    private function renderContainer( $ContainerName ){
        $Cont = $this->formStruct['Containers'][ $ContainerName ];

        $className = '\WioForms\ContainerRenderer\\'.$Cont['displayType'];
        if ( class_exists($className) ) {
            $ContainerClass = new $className( $ContainerName, $this );
        }
        else
        {
            $this->WioForms->ErrorLog->ErrorLog('Class '.$className.' not found.');
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



    private function createContainersContains(){
        $this->ContainersContains = [];

        foreach (['Fields','Containers'] as $ElemType)
        {
            foreach ($this->formStruct[$ElemType]  as $ElemName => $Elem )
            {
                $cont = $Elem['container'];
                $pos = $Elem['position'];
                if( $cont == 'main')
                {
                    $cont = 'main_'.$Elem['site'];
                }
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


}





?>
