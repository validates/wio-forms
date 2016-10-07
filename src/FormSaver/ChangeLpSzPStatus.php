<?php

namespace WioForms\FormSaver;

use WioStruct\Core\StructDefinition;
use WioStruct\WioStruct;

class ChangeLpSzPStatus extends AbstractFormSaver
{
    public function makeSavingAction($settings)
    {
        if (!isset($this->wioForms->entryData['status'])) {
            return;
        }
        $newStatus = $this->wioForms->entryData['status'];
        if ($this->isFinalStatus($newStatus) && !empty($this->wioForms->entryData['assigned_area'])) {
            $this->handleNodeFlags($this->wioForms->entryData['assigned_area']);
            $this->setUpRecrutationAreas($this->wioForms->entryData['userId']);
        } elseif (!$this->isFinalStatus($newStatus)) {
            $this->setUpRecrutationAreas($this->wioForms->entryData['userId']);
        }
    }

    private function isFinalStatus($newStatus)
    {
        return (int) $newStatus === 60;
    }

    private function handleNodeFlags($assignedArea)
    {
        global $queryBuilder;
        $wioStruct = new WioStruct($queryBuilder);

        $node = (new StructDefinition())
            ->nodeId($assignedArea);

        $wioStruct->structQuery($node)
            ->add('Flag', 'is_built');
    }

    private function setUpRecrutationAreas($id)
    {
        global $queryBuilder;

        $wioFlow = $queryBuilder->table('wio_flow_entities')
            ->where('wio_user_id', '=', $id)
            ->first();

        $wioFlowId = $wioFlow->id;

        $data = array(
            'wio_struct_given_node_id' => $this->wioForms->entryData['assigned_area'],
        );

        $queryBuilder->table('recrutation_areas')
            ->where('wio_flow_entity_id', $wioFlowId)
            ->update($data);
    }
}
