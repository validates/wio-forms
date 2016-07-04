<?php

namespace WioForms\DataRepository;

class WioStructAvailableSzPRegions extends AbstractDataRepository
{
    /**
     * @TODO: Rewrite methods fetching available regions
     */
    public function getData($requiredFields)
    {
        $this->data = [];
        $dontUnsetRegion = $this->getUserRegion($requiredFields['userId']);

        $regionsWithDeclined = $this->getRegionsWithDeclinedLider();
        $unbuildRegions = $this->getUnbuildRegions();
        $regionsWithLider = $this->getRegionsWithLider();

        $regionList = $unbuildRegions + $regionsWithDeclined;
        foreach ($regionList as $region) {
            $this->data[$region->id] = $region->name;
        }
        foreach ($regionsWithLider as $regionWithLider) {
            if ($regionWithLider->id != $dontUnsetRegion) {
                unset($this->data[$regionWithLider->id]);
            }
        }
        $this->setRepositoryFlags();


        return $this->data;
    }

    private function getRegionsWithDeclinedLider()
    {
        global $queryBuilder;
        $declinedStatusArray = [2, 3, 11, 12, 21, 22, 31, 32, 41, 42, 51, 61, 62, 71, 72];

        $query = $queryBuilder->table('wio_struct_nodes')
            ->leftJoin('wio_struct_flags', 'wio_struct_flags.node_id', '=', 'wio_struct_nodes.id')
            ->leftJoin('recrutation_areas', 'recrutation_areas.wio_struct_node_id', '=', 'wio_struct_nodes.id')
            ->leftJoin('recrutation_roles', function ($table) {
                global $queryBuilder;
                $table->on('recrutation_roles.id', '=', 'recrutation_areas.recrutation_role_id');
                $table->on('recrutation_roles.status', '=', $queryBuilder->raw('\'active\''));
            })
            ->leftJoin('wio_flow_entities', 'wio_flow_entities.id', '=', 'recrutation_roles.wio_flow_entity_id')
            ->leftJoin('wio_struct_flag_types', function ($table) {
                $table->on('wio_struct_flag_types.id', '=', 'wio_struct_flags.flag_type_id');
            })
            ->select('wio_struct_nodes.id', 'wio_struct_nodes.name')
            ->where('wio_struct_flag_types.name', '=', $queryBuilder->raw('is_built'))
            ->whereIn('wio_flow_entities.flow_status', $declinedStatusArray)
            ->groupBy('wio_struct_nodes.id');
        $queryObj = $query->getQuery();
        $sql = $queryObj->getRawSql();

        $regionList = $query->get();

        return $regionList;
    }

    private function getUnbuildRegions()
    {
        global $queryBuilder;
        $query = $queryBuilder->table('wio_struct_nodes')
            ->leftJoin('wio_struct_flags', 'wio_struct_flags.node_id', '=', 'wio_struct_nodes.id')
            ->leftJoin('recrutation_areas', 'recrutation_areas.wio_struct_node_id', '=', 'wio_struct_nodes.id')
            ->leftJoin('recrutation_roles', function ($table) {
                global $queryBuilder;
                $table->on('recrutation_roles.id', '=', 'recrutation_areas.recrutation_role_id');
                $table->on('recrutation_roles.status', '=', $queryBuilder->raw('\'active\''));
            })
            ->leftJoin('wio_flow_entities', 'wio_flow_entities.id', '=', 'recrutation_roles.wio_flow_entity_id')
            ->leftJoin('wio_struct_flag_types', function ($table) {
                $table->on('wio_struct_flag_types.id', '=', 'wio_struct_flags.flag_type_id');
            })
            ->select('wio_struct_nodes.id', 'wio_struct_nodes.name')
            ->where('wio_struct_flag_types.name', '=', $queryBuilder->raw('mapa_liderow_2016_rejon_szp'))
            ->groupBy('wio_struct_nodes.id');
        $queryObj = $query->getQuery();
        $sql = $queryObj->getRawSql();

        $regionList = $query->get();

        return $regionList;
    }

    private function getRegionsWithLider()
    {
        global $queryBuilder;
        $declinedStatusArray = [60,70];
        $nodeFlagTypeName = 'is_built';

        $query = $queryBuilder->table('wio_struct_nodes')
            ->leftJoin('recrutation_areas', 'wio_struct_nodes.id', '=', 'recrutation_areas.wio_struct_given_node_id')
            ->leftJoin('wio_flow_entities', 'wio_flow_entities.id', '=', 'recrutation_areas.wio_flow_entity_id')
            ->leftJoin('wio_struct_flags', 'wio_struct_flags.node_id', '=', 'wio_struct_nodes.id')
            ->leftJoin('wio_struct_flag_types', 'wio_struct_flag_types.id', '=', 'wio_struct_flags.flag_type_id')
            ->where('wio_struct_flag_types.name',$nodeFlagTypeName)
            ->where('wio_flow_entities.active_status','active')
            ->where('recrutation_areas.status','active')
            ->whereIn('wio_flow_entities.flow_status', $declinedStatusArray)
            ->select('wio_struct_nodes.id', 'wio_struct_nodes.name');

        $queryObj = $query->getQuery();
        $sql = $queryObj->getRawSql();

        $regionList = $query->get();

        return $regionList;
    }

    private function getUserRegion($userId)
    {
        global $queryBuilder;
        $query = $queryBuilder->table('recrutation_areas')
            ->leftJoin('wio_flow_entities', 'wio_flow_entities.id', '=', 'recrutation_areas.wio_flow_entity_id')
            ->where('wio_flow_entities.wio_user_id', $userId)
            ->select('recrutation_areas.wio_struct_given_node_id');

        $answer = $query->first();
        return $answer->wio_struct_given_node_id;
    }


}
