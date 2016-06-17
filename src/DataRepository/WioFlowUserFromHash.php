<?php

namespace WioForms\DataRepository;

class WioFlowUserFromHash extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        global $queryBuilder;

        $hash = filter_input(INPUT_GET, "hash", FILTER_SANITIZE_STRING);
        if (empty($hash)) {
            redirect('');
        }
        $result = $queryBuilder->table('wio_hash')
            ->setFetchMode(\PDO::FETCH_ASSOC)
            ->join('wio_flow_entities', 'wio_hash.wio_flow_entity_id', '=', 'wio_flow_entities.id')
            ->where('wio_hash.hash', '=', $hash)
            ->first();

        $this->data = $result['wio_user_id'];
        $this->setRepositoryFlags();

        return $this->data;
    }
}
