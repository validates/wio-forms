<?php
namespace WioForms\ContainerRenderer;


abstract class AbstractContainerRenderer
{
    protected $wioForms;
    protected $containerName;
    protected $containerInfo;

    protected $title;
    protected $message;

    function __construct($containerName, $wioFormsObject)
    {
        $this->containerName = $containerName;
        $this->wioForms = $wioFormsObject;
        $this->containerInfo = &$this->wioForms->formStruct['Containers'][ $this->containerName ];

        $this->prepareDefaultData();
    }

    private function prepareDefaultData()
    {
        $this->title = false;
        if (isset($this->containerInfo['title'])
            and !empty($this->containerInfo['title']))
        {
            $this->title = $this->containerInfo['title'];
        }

        $this->message = false;
        if (isset($this->containerInfo['valid'])
            and !$this->containerInfo['valid']
            and isset($this->containerInfo['message']))
        {
            $this->message = $this->containerInfo['message'];
        }
        if (isset($this->styleOptions['dont_display_errors'])
            and $this->styleOptions['dont_display_errors'])
        {
            $this->message = false;
        }
    }

    abstract function showHead();
    abstract function showTail();

}

?>
