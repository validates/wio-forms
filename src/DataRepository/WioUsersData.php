<?php

namespace WioForms\DataRepository;

use SuperW\Service\ApiService;

class WioUsersData extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        $this->repositoryDefinition['tried'] = true;

        $this->data = [];

        $apiService = new ApiService();
        $this->data = $apiService->getData(
            $requiredFields['email'],
            $requiredFields['password']
        );

        if (empty($this->data)) {
            $this->repositoryDefinition['success'] = false;
            $this->repositoryDefinition['message'] = 'login_failed';
            $this->data = false;
        } else {
            $this->repositoryDefinition['success'] = true;
        }

        return $this->data;
    }
}
