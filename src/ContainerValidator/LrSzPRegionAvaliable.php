<?php

namespace WioForms\ContainerValidator;

class LrSzPRegionAvaliable extends AbstractContainerValidator
{
    public function validatePHP(&$container, &$settings)
    {
        $this->invalidMessage = 'region_not_avaliable';

        $userStatus = $this->wioForms->formStruct['Fields']['status']['value'];
        $userId = $this->wioForms->formStruct['Fields']['userId']['value'];
        $userNode = $this->wioForms->formStruct['Fields']['assigned_area']['value'];

        if ($userStatus == 60 or $userStatus == 70) {
            $this->valid = false;
            if ($this->isRegionFree($userNode,$userId)) {
                $this->valid = true;
            }
        } else {
            $this->valid = true;

        }

        $this->setAnswer();

        return $this->getReturn();
    }


    private function isRegionFree($regionId, $allowedUserId)
    {
        global $queryBuilder;
        $declinedStatusArray = [60,70];


        $query = $queryBuilder->table('wio_struct_nodes')
            ->leftJoin('recrutation_areas', 'wio_struct_nodes.id', '=', 'recrutation_areas.wio_struct_given_node_id')
            ->leftJoin('wio_flow_entities', 'wio_flow_entities.id', '=', 'recrutation_areas.wio_flow_entity_id')
            ->leftJoin('wio_struct_flags', 'wio_struct_flags.node_id', '=', 'wio_struct_nodes.id')
            ->leftJoin('wio_struct_flag_types', 'wio_struct_flag_types.id', '=', 'wio_struct_flags.flag_type_id')
            ->where('wio_struct_flag_types.name', 'is_built')
            ->where('wio_flow_entities.active_status','active')
            ->where('recrutation_areas.status','active')
            ->whereIn('wio_flow_entities.flow_status', $declinedStatusArray)
            ->where('wio_flow_entities.wio_user_id', '<>', $allowedUserId)
            ->where('recrutation_areas.wio_struct_given_node_id', $regionId);

        $answer = $query->get();

        if ($answer != null) {
            return false;
        }
        return true;
    }
}
