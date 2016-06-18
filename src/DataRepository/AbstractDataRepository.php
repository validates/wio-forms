<?php

namespace WioForms\DataRepository;

abstract class AbstractDataRepository
{
    protected $wioForms;
    protected $repositoryDefinition;

    protected $data = [];

    public function __construct($wioFormsObject, $repositoryName)
    {
        $this->wioForms = $wioFormsObject;
        $this->repositoryDefinition = &$this->wioForms->formStruct['DataRepositories'][$repositoryName];

        $this->repositoryDefinition['success'] = false;
        $this->repositoryDefinition['tried'] = false;
        $this->repositoryDefinition['message'] = '';
    }

    abstract public function getData($requiredFields);

    protected function setRepositoryFlags()
    {
        if (empty($this->data)) {
            $this->repositoryDefinition['success'] = false;
            $this->repositoryDefinition['message'] = 'no_map_data';
            $this->data = false;
        } else {
            $this->repositoryDefinition['success'] = true;
        }
        $this->repositoryDefinition['tried'] = true;
    }
}
