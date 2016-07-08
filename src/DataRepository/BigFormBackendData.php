<?php

namespace WioForms\DataRepository;

class BigFormBackendData extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        global $queryBuilder;

        $result = $queryBuilder->table('wio_users')
            ->join('wio_flow_entities', 'wio_users.id', '=', 'wio_flow_entities.wio_user_id')
            ->join('recrutation_roles', 'recrutation_roles.wio_flow_entity_id', '=', 'wio_flow_entities.id')
            ->join('user_recrutation_answers', 'user_recrutation_answers.wio_user_id', '=', 'wio_users.id')
            ->join('user_address_data', 'user_address_data.wio_user_id', '=', 'wio_users.id')
            ->join('user_basic_data', 'user_basic_data.wio_user_id', '=', 'wio_users.id')
            ->where('wio_users.id', $requiredFields['userId'])
            ->select([
                'wio_flow_entities.id' => 'wioFlowEntityId',
                'recrutation_roles.role_short_name' => 'userRole',
                'recrutation_roles.program_short_name' => 'userProgram',
                'user_recrutation_answers.szp_editions' => 'szp_editions',
                'user_recrutation_answers.ap_editions' => 'ap_editions',
                'user_recrutation_answers.who_told_you' => 'who_told_you',
                'user_recrutation_answers.openWhyLider' => 'openWhyLider',
                'user_recrutation_answers.openWhyRW' => 'openWhyRW',
                'user_recrutation_answers.openExpectations' => 'openExpectations',
                'user_recrutation_answers.openDreaming' => 'openDreaming',
                'user_recrutation_answers.cvFileId' => 'cvFileId',
                'user_recrutation_answers.openFileId' => 'openFileId',
                'user_recrutation_answers.occupation' => 'occupation',
                'user_recrutation_answers.education' => 'education',
                'user_recrutation_answers.wiosna_volunteerings' => 'wiosna_volunteerings',
                'user_recrutation_answers.otherRoles' => 'otherRoles',
                'user_address_data.province' => 'province',
                'user_address_data.city' => 'city',
                'user_address_data.postal_code' => 'postal_code',
                'user_address_data.address_street' => 'address_street',
                'user_address_data.address_number' => 'address_number',
                'user_basic_data.id_number' => 'id_number',
                'user_basic_data.birth_date' => 'birth_date',
            ])
            ->first();

        if (!empty($result)) {
            $this->data['wioFlowEntityId'] = $result->wioFlowEntityId;
            $this->data['userRole'] = $result->userRole;
            $this->data['userProgram'] = $result->userProgram;
            $this->data['szp_editions'] = $result->szp_editions;
            $this->data['ap_editions'] = $result->ap_editions;
            $this->data['who_told_you'] = $result->who_told_you;
            $this->data['openWhyLider'] = $result->openWhyLider;
            $this->data['openWhyRW'] = $result->openWhyRW;
            $this->data['openExpectations'] = $result->openExpectations;
            $this->data['openDreaming'] = $result->openDreaming;
            $this->data['cvFileId'] = $result->cvFileId;
            $this->data['openFileId'] = $result->openFileId;
            $this->data['occupation'] = $result->occupation;
            $this->data['education'] = $result->education;
            $this->data['wiosna_volunteerings'] = $result->wiosna_volunteerings;
            $this->data['otherRoles'] = $result->otherRoles;
            $this->data['province'] = $result->province;
            $this->data['city'] = $result->city;
            $this->data['postal_code'] = $result->postal_code;
            $this->data['address_street'] = $result->address_street;
            $this->data['address_number'] = $result->address_number;
            $this->data['id_number'] = $result->id_number;
            $this->data['birth_date'] = $result->birth_date;
            $this->data['skille'] = '';
        }

        $result = $queryBuilder->table('user_skills')
            ->where('wio_user_id', $requiredFields['userId'])
            ->orderBy('id')
            ->get();

        if (!empty($result)) {
            $skille = '';
            foreach ($result as $res) {
                if ($res->group_id != 0) {
                    $skille .= $res->group_id.'-';
                    if ($res->skill_id != 0) {
                        $skille .= $res->skill_id;
                    }
                    $skille .= '-';
                }
                $skille .= $res->skill_name.'|';
            }
            $this->data['skille'] = $skille;
        }


        foreach (['cvFileId', 'openFileId'] as $fileName) {
            if (isset($this->data[$fileName])) {
                $result = $queryBuilder->table('uploaded_files')
                    ->where('id', $this->data[$fileName])
                    ->first();

                if ($result) {
                    $this->data[$fileName] = [
                        'fileName' => $result->file_real_name,
                        'fileLink' => $result->file_path,
                    ];
                }
            }
        }

        $this->setRepositoryFlags();

        return $this->data;
    }
}
