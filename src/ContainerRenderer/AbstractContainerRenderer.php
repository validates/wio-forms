<?php
namespace WioForms\ContainerRenderer;


abstract class AbstractContainerRenderer
{
    protected $WioForms;
    protected $ContainerName;
    protected $ContainerInfo;

    function __construct( $ContainerName, $WioFormsObject ){
        $this->ContainerName = $ContainerName;
        $this->WioForms = $WioFormsObject;
        $this->ContainerInfo = $this->WioForms->formStruct['Containers'][ $this->ContainerName ];
    }

    abstract function ShowHead();
    abstract function ShowTail();

}

?>
