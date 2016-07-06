<?php

namespace WioForms\DataRepository;

class WioUserLogin extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        global $queryBuilder;

        $result = $queryBuilder->table('wio_users')
            ->join('wio_flow_entities', 'wio_users.id', '=', 'wio_flow_entities.wio_user_id')
            ->join('recrutation_roles', 'recrutation_roles.wio_flow_entity_id', '=', 'wio_flow_entities.id')
            ->where('wio_users.email', $requiredFields['email'])
            ->where('wio_users.password', md5($requiredFields['password']))
            ->select([
                'wio_users.id' => 'wioUserId',
                'wio_flow_entities.id' => 'wioFlowEntityId',
                'recrutation_roles.role_short_name' => 'userRole',
                'recrutation_roles.program_short_name' => 'userProgram',
            ])
            ->first();

        if (!empty($result)) {
            $this->data['wioUserId'] = $result->wioUserId;
            $this->data['wioFlowEntityId'] = $result->wioFlowEntityId;
            $this->data['userRole'] = $result->userRole;
            $this->data['userProgram'] = $result->userProgram;
        }

        $this->setRepositoryFlags();

        return $this->data;
    }
}
