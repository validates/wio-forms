<?php

namespace WioForms\FieldRenderer;

abstract class AbstractFieldRenderer
{
    protected $wioForms;
    protected $fieldName;
    protected $formStruct;
    protected $fieldInfo;

    // data prepared by prepareDefauldData method
    protected $value;
    protected $styleOptions;
    protected $message;

    // data prepared by prepareDataSet method
    protected $dataSet;

    protected $html;

    public function __construct($fieldName, $wioFormsObject)
    {
        $this->fieldName = $fieldName;
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;
        $this->fieldInfo = &$this->wioForms->formStruct['Fields'][$this->fieldName];

        $this->prepareDefaultData();
    }

    private function prepareDefaultData()
    {
        $this->value = '';
        if (!empty($this->fieldInfo['value'])) {
            $this->value = $this->fieldInfo['value'];
        }

        $this->styleOptions = [];
        if (isset($this->fieldInfo['styleOptions'])) {
            $this->styleOptions = $this->fieldInfo['styleOptions'];
        }

        $this->message = false;
        if (isset($this->fieldInfo['valid']) and !$this->fieldInfo['valid']) {
            $this->message = $this->fieldInfo['message'];
        }


        if (isset($this->styleOptions['dont_display_errors'])
            and $this->styleOptions['dont_display_errors']) {
            $this->message = false;
        }
    }

    protected function prepareDataSet()
    {
        $this->dataSet = [];
        $dataSetName = $this->fieldInfo['dataSet']['repositoryName'];
        if (isset($this->formStruct['DataRepositories'][$dataSetName])) {
            $this->dataSet = &$this->formStruct['DataRepositories'][$dataSetName]['data'];
        } else {
            $this->wioForms->errorLog->errorLog('DataRepository: '.$dataSetName.' not found.');
        }
    }

    protected function inputContainerHead($additional_class = '')
    {
        $additional_class .= $this->getAdditionalWrapperClasses();
        $this->html .= '<div class="wioForms_InputContainer '.$additional_class.'">'."\n";
    }

    protected function inputContainerTail()
    {
        $this->html .= '</div>'."\n";
    }

    protected function inputFieldContainerHead()
    {
        $this->html .= '<div data-wio-forms="'.$this->fieldName
            .'"class="wioForms_InputFieldContainer">'."\n";
    }

    protected function inputFieldContainerTail()
    {
        $this->html .= '</div>'."\n";
    }

    protected function inputTitleContainer()
    {
        if (isset($this->fieldInfo['title']) and strlen($this->fieldInfo['title']) > 0) {
            $this->html .= '<div class="wioForms_InputTitleContainer">'.$this->fieldInfo['title'].': </div>'."\n";
        }
    }

    protected function inputDescriptionContainer()
    {
        if (isset($this->fieldInfo['description']) and strlen($this->fieldInfo['description']) > 0) {
            $this->html .= '<div class="wioForms_InputDescriptionContainer">'.$this->fieldInfo['description'].'</div>'."\n";
        }
    }

    protected function standardErrorDisplay()
    {
        if ($this->message !== false) {
            $this->html .= '<div class="wioForms_ErrorMessage">'.$this->message.'</div>'."\n";
        }
    }

    abstract public function showToEdit();

    abstract public function showToView();

    protected function getAdditionalWrapperClasses()
    {
        return isset($this->fieldInfo['class']['wrapper']) ? $this->fieldInfo['class']['wrapper'] : '';
    }

    protected function getAdditionalInputClasses()
    {
        return isset($this->fieldInfo['class']['input']) ? $this->fieldInfo['class']['input'] : '';
    }
}
