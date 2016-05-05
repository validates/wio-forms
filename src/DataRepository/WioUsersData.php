<?php
namespace WioForms\DataRepository;

use SuperW\Service\ApiService;

class WioUsersData extends AbstractDataRepository
{

    function getData($requiredFields)
    {
        $this->repositoryDefinition['tried'] = true;

        $data = [];

        $apiService = new ApiService();
        $data = $apiService->getData($requiredFields['email'], $requiredFields['password']);

        if (empty($data))
        {
            $this->repositoryDefinition['success'] = false;
            $this->repositoryDefinition['message'] = 'login_failed';
            $data = false;
        }
        else
        {
            $this->repositoryDefinition['success'] = true;
        }

        return $data;
    }

}
