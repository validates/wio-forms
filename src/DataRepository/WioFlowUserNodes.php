<?php

namespace WioForms\DataRepository;

use Exception;
use Pixie\QueryBuilder\QueryBuilderHandler;
use WioStruct\Core\StructDefinition;
use WioStruct\WioStruct;

class WioFlowUserNodes extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        if (is_null($this->wioForms->formStruct['Fields']['wioFlowEntityId']['value'])) {
            throw new Exception('WioFlowEntityId property is null');
        }
        $this->data = [];
        $wioFlowEntityId = $this->wioForms->formStruct['Fields']['wioFlowEntityId']['value'];

        $wioStruct = new WioStruct(new QueryBuilderHandler());

        global $queryBuilder;
        $queryResult = $queryBuilder->table('wio_flow_entities')
            ->select($queryBuilder->raw('recrutation_areas.*'))
            ->setFetchMode(\PDO::FETCH_ASSOC)
            ->join('recrutation_areas', 'recrutation_areas.wio_flow_entity_id', '=', 'wio_flow_entities.id')
            ->where('wio_flow_entities.id', '=', $wioFlowEntityId)
            ->first();

        $primaryNode = $queryResult['wio_struct_node_id'];
        $givenNode = $queryResult['wio_struct_given_node_id'];

        $wojType = 1;
        $miastoType = 4;
        $wojApType = 7;
        $miastoApType = 8;
        $kolegiumApType = 9;

        $data = [
            'province' => 0,
            'city' => 0,
            'given_province' => 0,
            'given_city' => 0,
            'given_school' => $givenNode,
        ];

        if ($primaryNode > 0) {
            $nodeU = $wioStruct->structQuery((new StructDefinition())->nodeId($primaryNode)->nodeTypeId($wojType))->get('Node');
            if ($nodeU != null) {
                $data['province'] = $primaryNode;
            } else {
                $nodeU = $wioStruct->structQuery((new StructDefinition())->nodeId($primaryNode)->nodeTypeId($miastoType))->get('Node');

                if ($nodeU != null) {
                    $data['city'] = $primaryNode;
                    $nodeU = $wioStruct->structQuery((new StructDefinition())->nodeTypeId($wojType)
                        ->linkChildren((new StructDefinition())->nodeId($primaryNode))
                    )->get('Node');

                    if ($nodeU != null) {
                        $data['province'] = $nodeU[0]->NodeId;
                    }
                }
            }
        }

        if ($givenNode) {
            $givenCity = $wioStruct->structQuery((new StructDefinition())
                ->linkChildren((new StructDefinition())->nodeId($givenNode))
                ->nodeTypeName('city'))->get('Node');
            $data['given_city'] = isset($givenCity[0]->NodeId)
                ? $givenCity[0]->NodeId
                : 0;

            $givenProvince = $wioStruct->structQuery((new StructDefinition())
                ->linkChildren((new StructDefinition())->nodeId($givenNode))
                ->networkName('administrative')
                ->nodeTypeName('state'))->get('Node');
            $data['given_province'] = isset($givenProvince[0]->NodeId)
                ? $givenProvince[0]->NodeId
                : 0;
        }

        $this->data = $data;

        $this->setRepositoryFlags();

        return $this->data;
    }
}
