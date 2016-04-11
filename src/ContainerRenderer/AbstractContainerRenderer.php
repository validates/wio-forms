<?php
namespace WioForms\ContainerRenderer;


abstract class AbstractContainerRenderer
{
    private $WioForms;
    private $ContainerName;
    private $ContainerInfo;

    abstract function __construct( $ContainerName, $WioFormsObject );

    abstract function ShowHead();
    abstract function ShowTail();

}

?>
