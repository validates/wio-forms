<?php

namespace WioForms\DataRepository;

use WioStruct\Core\StructDefinition;
use WioStruct\WioStruct;

class RecruitmentData extends AbstractDataRepository
{
    /**
     * @todo : logika dla pola typ (nowy, weteran, awansowany)
     */
    public function getData($requiredFields)
    {
        global $queryBuilder;
        $this->data = $queryBuilder->table('wio_flow_entities')
            ->setFetchMode(\PDO::FETCH_ASSOC)
            ->join('recrutation_roles', 'recrutation_roles.wio_flow_entity_id', '=', 'wio_flow_entities.id')
            ->join('wio_users', 'wio_users.id', '=', 'wio_flow_entities.wio_user_id')
            ->join('user_basic_data', 'user_basic_data.wio_user_id', '=', 'wio_users.id')
            ->join('user_phone_data', 'user_phone_data.wio_user_id', '=', 'wio_users.id')
            ->join('recrutation_areas', 'recrutation_areas.wio_flow_entity_id', '=', 'wio_flow_entities.id')
            ->where('wio_users.id', '=', $requiredFields['userId'])
            ->first();

        $this->data['type'] = rand(1, 3);
        $this->data['wanted_area_id'] = $this->data['wio_struct_node_id'];
        $this->setUpDummyAreaLogic('wanted_area_name', $this->data['wanted_area_id']);

        $this->data['assigned_area_id'] = $this->data['wio_struct_given_node_id'];

        $this->setRepositoryFlags();

        return $this->data;
    }

    /**
     * @param $wioStruct
     * @param $area
     */
    private function setUpDummyAreaLogic($field, $nodeId)
    {
        $wioStruct = new WioStruct(new \Pixie\QueryBuilder\QueryBuilderHandler());
        $area = $wioStruct->structQuery(
            (new StructDefinition())
                ->nodeId($nodeId)
        )
            ->get('Node');

        $area = reset($area);

        $this->data[$field] = $area->NodeName;
    }
}
