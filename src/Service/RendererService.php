<?php
namespace WioForms\Service;

use WioForms\FormRenderer\FormRenderer;

class RendererService
{
    # Holds FormRenderer object
    private $formRenderer;

    # Holds html to display
    private $outputHtml;

    public $wioForms;
    public $formStruct;


    function __construct( $wioFormsObiect ){
        $this->wioForms = $wioFormsObiect;
        $this->formStruct = &$this->wioForms->formStruct;

        # Gets FormRenderer
        $this->formRenderer = new FormRenderer( $this->wioForms );

        $this->outputHtml = '';
    }

    public function renderFormSite( $siteNumber ){

        $this->outputHtml .= $this->formRenderer->showHead();

        foreach ($this->wioForms->containersContains['_site_'.$siteNumber] as $elemData)
        {
            if ($elemData['type'] == 'Fields'){
                $this->wioForms->errorLog->errorLog('We have Field directly in "_site_'.$siteNumber.'" container.');
                continue;
            }
            $container = $this->formStruct['Containers'][ $elemData['name'] ];
            if ( $container['site'] == $siteNumber )
            {
                $this->renderContainer( $elemData['name'] );
            }
        }

        $this->outputHtml .= $this->formRenderer->showTail();

        echo $this->outputHtml;
    }

    private function renderField( $fieldName ){
      $field = $this->formStruct['Fields'][ $fieldName ];

      $className = '\WioForms\FieldRenderer\\'.$field['type'];
      if ( class_exists($className) ) {
          $rendererObject = new $className( $fieldName, $this );
      }
      else
      {
          $this->wioForms->errorLog->errorLog('Class '.$className.' not found.');
          return false;
      }

      $this->outputHtml .= $rendererObject->showToEdit();
    }

    private function renderContainer( $containerName ){
        $container = $this->formStruct['Containers'][ $containerName ];

        if( isset( $container['hidden'] ) and $container['hidden'] == true )
        {
            return true;
        }

        $className = '\WioForms\ContainerRenderer\\'.$container['displayType'];
        if ( class_exists($className) ) {
            $rendererObiect = new $className( $containerName, $this );
        }
        else
        {
            $this->wioForms->errorLog->errorLog('Class '.$className.' not found.');
            return false;
        }

        $this->outputHtml .= $rendererObiect->showHead();

        if ( isset($this->wioForms->containersContains[ $containerName ]) ){
            foreach ($this->wioForms->containersContains[ $containerName ] as $elemData)
            {
                if ( $elemData['type'] == 'Containers' )
                {
                    $this->renderContainer( $elemData['name'] );
                }
                if ( $elemData['type'] == 'Fields' )
                {
                    $this->renderField( $elemData['name'] );
                }
            }
        }

        $this->outputHtml .= $rendererObiect->showTail();
    }

    private function addFunctionsToJavaScript( ){}

    /*
    prints all javascript code needed to show the form
    adds all validation functions
    */
    private function renderJavaScript( ){}



    public function createContainersContains(){
        $this->wioForms->containersContains = [];

        foreach (['Fields','Containers'] as $elemType)
        {
            foreach ($this->formStruct[$elemType]  as $elemName => $elem )
            {
                $cont = $elem['container'];
                $pos = $elem['position'];
                if( $cont == '_site')
                {
                    $cont = '_site_'.$elem['site'];
                }
                if ( !isset( $this->wioForms->containersContains[$cont] ))
                {
                    $this->wioForms->containersContains[$cont] = [];
                }
                if ( isset( $this->wioForms->containersContains[$cont][$pos] ))
                {
                    $this->wioForms->errorLog->errorLog('Doubled position for '.$elemType.'::'.$elemName.' in container '.$cont.'.');
                }
                else
                {
                    $this->wioForms->containersContains[$cont][$pos] = [
                        "name" => $elemName,
                        "type" => $elemType
                    ];
                }
            }
        }
        foreach ($this->wioForms->containersContains as $key => $array)
        {
            ksort($this->wioForms->containersContains[ $key ]);
        }
    }


}





?>
