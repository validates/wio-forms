<?php

namespace WioForms\DataRepository;

class WioFlowEntityData extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        global $queryBuilder;

        $this->data = $queryBuilder->table('wio_flow_entities')
            ->setFetchMode(\PDO::FETCH_ASSOC)
            ->where('wio_user_id', '=', $requiredFields['userId'])
            ->first();
        $this->setRepositoryFlags();

        return $this->data['id'];
    }
}
