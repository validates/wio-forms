<?php
namespace WioForms\DataRepository;


abstract class AbstractDataRepository
{
    protected $wioForms;
    protected $repositoryDefinition;

    function __construct($wioFormsObject, $repositoryName)
    {
        $this->wioForms = $wioFormsObject;
        $this->repositoryDefinition = &$this->wioForms->formStruct['DataRepositories'][$repositoryName];

        $this->repositoryDefinition['success'] = false;
        $this->repositoryDefinition['tried'] = false;
        $this->repositoryDefinition['message'] = '';
    }

    abstract function getData($requiredFields);

}
