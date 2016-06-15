<?php

namespace WioForms\DataRepository;

class WioFlowCurrentUserData extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestUriArray = explode('/', $requestUri);

        $this->data = end($requestUriArray);
        $this->setRepositoryFlags();

        return $this->data;
    }
}
