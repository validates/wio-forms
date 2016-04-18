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

    public $siteNumber;

    function __construct( $wioFormsObiect )
    {
        $this->wioForms = &$wioFormsObiect;
        $this->formStruct = &$this->wioForms->formStruct;

        # Gets FormRenderer
        $this->formRenderer = new FormRenderer( $this->wioForms );

        $this->outputHtml = '';

    }

    public function renderFormSite( $siteNumber )
    {
        $this->siteNumber = $siteNumber;

        $this->outputHtml .= $this->formRenderer->showHead();

        foreach ($this->wioForms->containersContains['_site_'.$this->siteNumber] as $elemData)
        {
            if ($elemData['type'] == 'Fields'){
                $this->wioForms->errorLog->errorLog('We have Field directly in "_site_'.$this->siteNumber.'" container.');
                continue;
            }
            $container = $this->formStruct['Containers'][ $elemData['name'] ];
            if ( $container['site'] == $this->siteNumber )
            {
                $this->renderContainer( $elemData['name'] );
            }
        }

        $this->outputHtml .= $this->formRenderer->showTail();

        return $this->outputHtml;
    }

    private function renderContainer( $containerName )
    {
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

        $this->getContainerParentStyles( $containerName );

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

    private function renderField( $fieldName )
    {
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
        $this->getFieldParentStyles( $fieldName );

        $this->outputHtml .= $rendererObject->showToEdit();
    }

    private function addFunctionsToJavaScript( ){}

    /*
    prints all javascript code needed to show the form
    adds all validation functions
    */
    private function renderJavaScript( ){}



    public function createContainersContains()
    {
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

    public function dontShowErrorsOnSite( $siteNumber )
    {
        if ( isset($this->wioForms->containersContains['_site_'.$siteNumber]) )
        {
            foreach ($this->wioForms->containersContains['_site_'.$siteNumber] as $elem)
            {
                if ( $elem['type'] == 'Fields' )
                {
                    $this->addStyleToField( $elem['name'], 'dont_display_errors', true);

                }
                if ( $elem['type'] == 'Containers' )
                {
                    $this->addStyleToContainer( $elem['name'], 'dont_display_errors', true);
                }
            }
        }
    }

    private function addStyleToField( $fieldName, $style , $force = false)
    {
        $field = &$this->formStruct['Fields'][ $fieldName ];

        if ( !isset($field['styleOptions']) )
        {
            $field['styleOptions'] = [];
        }
        if ( $force or !isset( $field['styleOptions'][$style] ) )
        {
            $field['styleOptions'][ $style ] = true;
        }
    }

    private function addStyleToContainer( $containerName, $style, $force = false )
    {
        $container = &$this->formStruct['Containers'][ $containerName ];

        if ( !isset($container['styleOptions']) )
        {
            $container['styleOptions'] = [];
        }
        if ( $force or !isset( $container['styleOptions'][$style] ) )
        $container['styleOptions'][ $style ] = true;
    }

    private function getContainerParentStyles( $containerName )
    {
        $container = &$this->formStruct['Containers'][ $containerName ];
        if ( $container['container'] == '_site' )
        {
            return true;
        }
        $parentContainer = &$this->formStruct['Containers'][ $container['container'] ];

        if ( isset($parentContainer['styleOptions']) )
        {
            foreach ($parentContainer['styleOptions'] as $style => $styleState)
            {
                $this->addStyleToContainer( $containerName, $style );
            }
        }
    }

    private function getFieldParentStyles( $fieldName )
    {
        $field = &$this->formStruct['Fields'][ $fieldName ];

        $parentContainer = &$this->formStruct['Containers'][ $field['container'] ];

        if ( isset($parentContainer['styleOptions']) )
        {
            foreach ($parentContainer['styleOptions'] as $style => $styleState)
            {
                $this->addStyleToField( $fieldName, $style );
            }
        }
    }

}
?>
