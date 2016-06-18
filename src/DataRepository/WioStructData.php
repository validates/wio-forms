<?php

namespace WioForms\DataRepository;

use WioStruct\Core\StructDefinition;
use WioStruct\WioStruct;

class WioStructData extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        $this->data = [];

        $wioStruct = new WioStruct(new \Pixie\QueryBuilder\QueryBuilderHandler());

        $woj = $wioStruct->structQuery(
            (new StructDefinition())
                ->networkName('administrative')
                ->nodeTypeName('state')
            )
            ->get('Node');

        $wojewodztwa = [];
        foreach ($woj as $w) {
            $wojewodztwa[$w->NodeName] =
            [
                'node_id' => $w->NodeId,
                'lat' => $w->NodeLat,
                'lng' => $w->NodeLng,
                'szp_regions' => [],
                'ap_cities' => [],
            ];
        }


        $szp_regions = $wioStruct->structQuery(
            (new StructDefinition())
                ->networkName('Szlachetna Paczka')
                ->nodeTypeName('rejon')
                ->linkParent(
                    (new StructDefinition())
                        ->networkName('administrative')
                        ->nodeTypeName('state')
                )
            )
            ->get('Node');

        usort($szp_regions, function ($a, $b) {
            return strcmp($a->NodeName, $b->NodeName);
        });
        foreach ($szp_regions as $region) {
            $wojewodztwa[$region->ParentNodeName]['szp_regions'][$region->NodeName] = [
                'node_id' => $region->NodeId,
                'lat' => $region->NodeLat,
                'lng' => $region->NodeLng,
            ];
        }

        $ap_cities = $wioStruct->structQuery(
            (new StructDefinition())
                ->networkName('administrative')
                ->nodeTypeName('city')
                ->flagTypeName('mapa_liderow_2016_miasto_ap')
                ->linkParent(
                    (new StructDefinition())
                        ->networkName('administrative')
                        ->nodeTypeName('state')
                )
            )
            ->get('Node');
        foreach ($ap_cities as $city) {
            $wojewodztwa[$city->ParentNodeName]['ap_cities'][$city->NodeName] = [
                'node_id' => $city->NodeId,
                'lat' => $city->NodeLat,
                'lng' => $city->NodeLng,
            ];
        }

        // echo '<pre>'.print_r($wojewodztwa,true).'</pre>';

        $this->data = $wojewodztwa;


        if (empty($this->data)) {
            $this->repositoryDefinition['success'] = false;
            $this->repositoryDefinition['message'] = 'no_map_data';
            $this->data = false;
        } else {
            $this->repositoryDefinition['success'] = true;
        }
        $this->repositoryDefinition['tried'] = true;

        return $this->data;
    }
}
