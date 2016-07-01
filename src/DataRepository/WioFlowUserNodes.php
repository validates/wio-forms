<?php

namespace WioForms\DataRepository;

use Pixie\QueryBuilder\QueryBuilderHandler;
use WioStruct\Core\StructDefinition;
use WioStruct\WioStruct;

class WioFlowUserNodes extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        $this->data = [];
        $userId = $this->getUserId();
        $wioStruct = new WioStruct(new QueryBuilderHandler());

        global $queryBuilder;
        $queryResult = $queryBuilder->table('wio_flow_entities')
            ->setFetchMode(\PDO::FETCH_ASSOC)
            ->join('recrutation_areas', 'recrutation_areas.wio_flow_entity_id', '=', 'wio_flow_entities.id')
            ->join('wio_users', 'wio_users.id', '=', 'wio_flow_entities.wio_user_id')
            ->where('wio_users.id', '=', $userId)
            ->first();

        $primaryNode = $queryResult["wio_struct_node_id"];
        $givenNode = $queryResult["wio_struct_given_node_id"];

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

        if ($givenNode > 0) {
            $nodeU = $wioStruct->structQuery((new StructDefinition())->nodeId($givenNode)->nodeTypeId($wojType))->get('Node');
            if ($nodeU != null) {
                $data['given_province'] = $givenNode;
            } else {
                $nodeU = $wioStruct->structQuery((new StructDefinition())->nodeId($givenNode)->nodeTypeId($miastoType))->get('Node');

                if ($nodeU != null) {
                    $data['given_city'] = $givenNode;
                    $nodeU = $wioStruct->structQuery((new StructDefinition())->nodeTypeId($wojType)
                        ->linkChildren((new StructDefinition())->nodeId($givenNode))
                    )->get('Node');

                    if ($nodeU != null) {
                        $data['given_province'] = $nodeU[0]->NodeId;
                    }
                }
            }
        }

        if ($data['given_city'] < 1) {
            $data['given_city'] = $data['city'];
        }

        if ($data['given_province'] < 1) {
            $data['given_province'] = $data['province'];
        }

        $this->data = $data;

        $this->setRepositoryFlags();

        return $this->data;
    }

    private function getUserId()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestUriArray = explode('/', $requestUri);

        return end($requestUriArray);
    }
}
