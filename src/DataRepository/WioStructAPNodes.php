<?php

namespace WioForms\DataRepository;

use Pixie\QueryBuilder\QueryBuilderHandler;
use WioStruct\Core\StructDefinition;
use WioStruct\WioStruct;

class WioStructAPNodes extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        $this->data = [];

        $wioStruct = new WioStruct(new QueryBuilderHandler());

        $this->addNodesByType($wioStruct, 'województwo', 'province');
        $this->addNodesByType($wioStruct, 'szkoła', 'school');

        $apCities = $wioStruct->structQuery(
            (new StructDefinition())
                ->networkName('administrative')
                ->nodeTypeName('city')
                ->flagTypeName('mapa_liderow_2016_miasto_ap')
                ->linkParent(
                    (new StructDefinition())
                        ->networkName('administrative')
                        ->nodeTypeName('state')
                )
        )->get('Node');
        foreach ($apCities as $city) {
            $this->data['city'][$city->NodeId] = $city->NodeName;
        }

        $this->addNodesByType($wioStruct, 'kolegium', 'collegium');

        $this->setRepositoryFlags();

        return $this->data;
    }

    private function addNodesByType($wioStruct, $nodeTypeName, $subsetName)
    {
        $nodeList = $wioStruct->structQuery(
            (new StructDefinition())
                ->networkName('Akademia Przyszłości')
                ->nodeTypeName($nodeTypeName)
        )->get('Node');

        foreach ($nodeList as $node) {
            $this->data[$subsetName][$node->NodeId] = $node->NodeName;
        }
    }
}
