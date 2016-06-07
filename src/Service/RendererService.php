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

    function __construct($wioFormsObject)
    {
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;

        # Gets FormRenderer
        $this->formRenderer = new FormRenderer($this->wioForms);

        $this->outputHtml = '';
    }

    public function renderFormSite($siteNumber)
    {
        $this->siteNumber = $siteNumber;

        $this->outputHtml .= $this->formRenderer->showHead();

        foreach ($this->wioForms->containersContains['_site_'.$this->siteNumber] as $elemData)
        {
            if ($elemData['type'] == 'Fields'){
                $this->wioForms->errorLog->errorLog('We have Field directly in "_site_'.$this->siteNumber.'" container.');
                continue;
            }
            $this->renderContainer($elemData['name']);
        }

        $this->outputHtml .= $this->formRenderer->showTail();

        return $this->outputHtml;
    }

    private function renderContainer($containerName)
    {
        $container = &$this->formStruct['Containers'][ $containerName ];

        if (isset($container['hidden'])
            and $container['hidden'] == true)
        {
            return true;
        }

        $this->wioForms->styleManagementService->getContainerParentStyles($containerName);

        $className = $this->wioForms->classFinderService->checkName('ContainerRenderer', $container['displayType']);
        if ($className)
        {
            $rendererObject = new $className($containerName, $this->wioForms);
        }
        else
        {
            return false;
        }

        $this->outputHtml .= $rendererObject->showHead();

        if (isset($this->wioForms->containersContains[ $containerName ]))
        {
            foreach ($this->wioForms->containersContains[ $containerName ] as $elemData)
            {
                if ($elemData['type'] == 'Containers')
                {
                    $this->renderContainer($elemData['name']);
                }
                if ($elemData['type'] == 'Fields')
                {
                    $this->renderField($elemData['name']);
                }
            }
        }

        $this->outputHtml .= $rendererObject->showTail();
    }

    private function renderField($fieldName)
    {
        $field = &$this->formStruct['Fields'][ $fieldName ];

        $this->wioForms->styleManagementService->getFieldParentStyles($fieldName);

        $className = $this->wioForms->classFinderService->checkName('FieldRenderer', $field['type']);
        if ($className)
        {
            $rendererObject = new $className($fieldName, $this->wioForms);
        }
        else
        {
            return false;
        }

        $this->outputHtml .= $rendererObject->showToEdit();
    }

    private function addFunctionsToJavaScript(){}

    /*
    prints all javascript code needed to show the form
    adds all validation functions
    */
    private function renderJavaScript(){}


    public function createContainersContains()
    {
        $this->wioForms->containersContains = [];

        foreach (['Fields','Containers'] as $elemType)
        {

            foreach ($this->formStruct[$elemType]  as $elemName => $elem)
            {
                $cont = $elem['container'];
                $pos = $elem['position'];
                if ($cont == '_site')
                {
                    $cont = '_site_'.$elem['site'];
                }
                if (!isset($this->wioForms->containersContains[ $cont ]))
                {
                    $this->wioForms->containersContains[ $cont ] = [];
                }
                if (isset($this->wioForms->containersContains[ $cont ][ $pos ]))
                {
                    $this->wioForms->errorLog->errorLog('Doubled position for '.$elemType.'::'.$elemName.' in container '.$cont.'.');
                }
                else
                {
                    $this->wioForms->containersContains[ $cont ][ $pos ] = [
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
