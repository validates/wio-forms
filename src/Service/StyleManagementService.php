<?php
namespace WioForms\Service;


class StyleManagementService
{
    public $wioForms;
    public $formStruct;

    function __construct( $wioFormsObiect ){
        $this->wioForms = &$wioFormsObiect;
        $this->formStruct = &$this->wioForms->formStruct;

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

    public function getContainerParentStyles( $containerName )
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

    public function getFieldParentStyles( $fieldName )
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
