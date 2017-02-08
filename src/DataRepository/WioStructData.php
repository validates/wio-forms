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
                'ap_collegium' => [],
            ];
        }

        $szp_regions = $wioStruct->structQuery(
            (new StructDefinition())
                ->networkName('Szlachetna Paczka')
                ->nodeTypeName('rejon')
                ->flagTypeName('mapa_liderow_2016_rejon_szp')
                ->linkParent(
                    (new StructDefinition())
                        ->networkName('administrative')
                        ->nodeTypeName('state')
                )
            )
            ->get('Node');

        $szp_regions_grey = $wioStruct->structQuery(
            (new StructDefinition())
                ->networkName('Szlachetna Paczka')
                ->nodeTypeName('rejon')
                ->flagTypeName('mapa_liderow_2016_rejon_szp')
                ->flagTypeName('is_grey')
                ->linkParent(
                    (new StructDefinition())
                        ->networkName('administrative')
                        ->nodeTypeName('state')
                )
            )
            ->get('Node');

        $szp_regions_grey_array = [];
        foreach ($szp_regions_grey as $key => $value) {
            $szp_regions_grey_array[$value->NodeId] = 1;
        }

        usort($szp_regions, function ($a, $b) {
            return strcmp($a->NodeName, $b->NodeName);
        });
        foreach ($szp_regions as $region) {
            $wojewodztwa[$region->ParentNodeName]['szp_regions'][$region->NodeName] = [
                'node_id' => $region->NodeId,
                'lat' => $region->NodeLat,
                'lng' => $region->NodeLng,
                'grey' => (isset($szp_regions_grey_array[$region->NodeId]))
                    ? 'true'
                    : 'false',
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

        $ap_cities_grey = $wioStruct->structQuery(
                (new StructDefinition())
                    ->networkName('administrative')
                    ->nodeTypeName('city')
                    ->flagTypeName('mapa_liderow_2016_miasto_ap')
                    ->flagTypeName('is_grey')
                    ->linkParent(
                        (new StructDefinition())
                            ->networkName('administrative')
                            ->nodeTypeName('state')
                    )
                )
                ->get('Node');
        $ap_cities_grey_array = [];
        foreach ($ap_cities_grey as $key => $value) {
            $ap_cities_grey_array[$value->NodeId] = 1;
        }

        foreach ($ap_cities as $city) {
            $wojewodztwa[$city->ParentNodeName]['ap_cities'][$city->NodeName] = [
                'node_id' => $city->NodeId,
                'lat' => $city->NodeLat,
                'lng' => $city->NodeLng,
                'grey' => (isset($ap_cities_grey_array[$region->NodeId]))
                    ? 'true'
                    : 'false',
            ];
        }

        $apCollegiumList = $wioStruct->structQuery(
            (new StructDefinition())
                ->networkName('Akademia Przyszłości')
                ->nodeTypeName('kolegium')
                ->flagTypeName('mapa_wolontariuszy_2016')
                ->linkParent(
                    (new StructDefinition())
                        ->networkName('administrative')
                        ->nodeTypeName('state')
                )
            )
            ->get('Node');

        foreach ($apCollegiumList as $apCollegium) {
            $wojewodztwa[$apCollegium->ParentNodeName]['ap_collegium'][$apCollegium->NodeName] = [
                'node_id' => $apCollegium->NodeId,
                'lat' => $apCollegium->NodeLat,
                'lng' => $apCollegium->NodeLng,
            ];
        }

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
