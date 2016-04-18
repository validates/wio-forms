<?php
namespace WioForms\ContainerRenderer;


abstract class AbstractContainerRenderer
{
    protected $wioForms;
    protected $containerName;
    protected $containerInfo;

    function __construct( $containerName, $wioFormsObject ){
        $this->containerName = $containerName;
        $this->wioForms = $wioFormsObject;
        $this->containerInfo = $this->wioForms->formStruct['Containers'][ $this->containerName ];
    }

    abstract function showHead();
    abstract function showTail();

}

?>
