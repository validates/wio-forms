<?php
namespace WioForms\DataRepository;

class RecruitmentData extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        global $queryBuilder;
        $this->data = $queryBuilder->table('wio_flow_entities')
            ->setFetchMode(\PDO::FETCH_ASSOC)
            ->join('recrutation_roles','recrutation_roles.wio_flow_entity_id','=','wio_flow_entities.id')
            ->join('wio_users', 'wio_users.id', '=', 'wio_flow_entities.wio_user_id')
            ->join('user_basic_data', 'user_basic_data.wio_user_id', '=', 'wio_users.id')
            ->join('user_phone_data','user_phone_data.wio_user_id','=','wio_users.id')
            ->where('wio_users.id', '=', $requiredFields['userId'])
            ->first();

        $this->setRepositoryFlags();

        return $this->data;
    }

}
