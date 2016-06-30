<?php

namespace WioForms\DataRepository;

use Pixie\QueryBuilder\QueryBuilderHandler;
use WioStruct\Core\StructDefinition;
use WioStruct\WioStruct;

class WioStructUserNodes extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        $this->data = [];


        global $queryBuilder;
        $queryResult = $queryBuilder->table('wio_flow_entities')
            ->setFetchMode(\PDO::FETCH_ASSOC)
            ->join('recrutation_areas', 'recrutation_areas.wio_flow_entity_id', '=', 'wio_flow_entities.id')
            ->join('wio_users', 'wio_users.id', '=', 'wio_flow_entities.wio_user_id')
            ->where('wio_users.id', '=', $requiredFields['userId'])
            ->first();

        $wioStruct = new WioStruct(new QueryBuilderHandler());

        $wioStructNodeId = $queryResult["wio_struct_node_is"];

        var_dump($queryBuilder);

        $provinceList = $wioStruct->structQuery(
            (new StructDefinition())
                ->networkName('Szlachetna Paczka')
                ->nodeTypeName('województwo')
            )
            ->get('Node');

        foreach ($provinceList as $province) {
            $this->data[$province->NodeId] = $province->NodeName;
        }

        $this->setRepositoryFlags();

        return $this->data;
    }
}
