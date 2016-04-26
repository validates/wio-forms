<?php
namespace WioForms\FieldRenderer;

abstract class AbstractFieldRenderer
{
    protected $wioForms;
    protected $fieldName;
    protected $formStruct;
    protected $fieldInfo;

    # data prepared by prepareDefauldData method
    protected $value;
    protected $styleOptions;
    protected $message;

    # data prepared by prepareDataSet method
    protected $dataSet;

    function __construct($fieldName, $wioFormsObject)
    {
        $this->fieldName = $fieldName;
        $this->wioForms = $wioFormsObject;
        $this->formStruct = &$this->wioForms->formStruct;
        $this->fieldInfo = &$this->wioForms->formStruct['Fields'][ $this->fieldName ];

        $this->prepareDefaultData();
    }

    private function prepareDefaultData()
    {
        $this->value = '';
        if (!empty($this->fieldInfo['value']))
        {
            $this->value = $this->fieldInfo['value'];
        }

        $this->styleOptions = [];
        if (isset($this->fieldInfo['styleOptions']))
        {
            $this->styleOptions = $this->fieldInfo['styleOptions'];
        }

        $this->message = false;
        if (isset($this->fieldInfo['valid']) and !$this->fieldInfo['valid'])
        {
            $this->message = $this->fieldInfo['message'];
        }
        if (isset($this->styleOptions['dont_display_errors'])
            and $this->styleOptions['dont_display_errors'])
        {
            $this->message = false;
        }
    }

    protected function prepareDataSet()
    {
        $this->dataSet = [];
        $dataSetName = $this->fieldInfo['dataSet']['repositoryName'];
        if (isset($this->formStruct['DataRepositories'][ $dataSetName ]))
        {
            $this->dataSet = &$this->formStruct['DataRepositories'][ $dataSetName ]['data'];
        }
        else
        {
            $this->wioForms->errorLog->errorLog('DataRepository: '.$dataSetName.' not found.');
        }
    }

    protected function standardErrorDisplay($html_after = '')
    {
        $html ='';
        if ($this->message !== false)
        {
            $html .= '<b style="color: red;"> &nbsp; '.$this->message.'</b>';
            $html .= $html_after;
        }
        return $html;
    }

    abstract function showToEdit();

    abstract function showToView();
}
