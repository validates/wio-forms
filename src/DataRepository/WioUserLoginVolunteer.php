<?php

namespace WioForms\DataRepository;

class WioUserLoginVolunteer extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        global $queryBuilder;

        $result = $queryBuilder->table('wio_users')
            ->join('wio_flow_entities', 'wio_users.id', '=', 'wio_flow_entities.wio_user_id')
            ->join('wio_user_flags', 'wio_users.id', '=', 'wio_user_flags.wio_user_id')
            ->join('recrutation_roles', 'wio_flow_entities.id', '=', 'recrutation_roles.wio_flow_entity_id')
            ->where('wio_users.email', $requiredFields['email'])
            ->where('wio_users.password', md5($requiredFields['password']))
            ->where('wio_user_flags.flag_type_id', '<', 3)
            ->where('recrutation_roles.program_short_name', $requiredFields['program'])
            ->select([
                'wio_users.id' => 'wioUserId',
                'wio_flow_entities.id' => 'wioFlowEntityId',
                'wio_user_flags.flag_type_id' => 'volunteerFlagId',
            ])
            ->first();

        if (!empty($result)) {
            $this->data['wioUserId'] = $result->wioUserId;
            $this->data['wioFlowEntityId'] = $result->wioFlowEntityId;
            $this->data['volunteerFlagId'] = $result->volunteerFlagId;
        }

        $this->setRepositoryFlags();
        return $this->data;
    }
}
