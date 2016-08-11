<?php

namespace WioForms\DataRepository;

class BigFormBackendVolunteer extends AbstractDataRepository
{
    public function getData($requiredFields)
    {
        global $queryBuilder;

        $result = $queryBuilder->table('wio_users')
            ->join('wio_flow_entities', 'wio_users.id', '=', 'wio_flow_entities.wio_user_id')
            ->join('wio_user_flags', 'wio_users.id', '=', 'wio_user_flags.wio_user_id')
            ->join('recrutation_roles', 'wio_flow_entities.id', '=', 'recrutation_roles.wio_flow_entity_id')
            ->join('user_recrutation_answers_volunteer2016', 'wio_users.id', '=', 'user_recrutation_answers_volunteer2016.wio_user_id')
            ->join('user_address_data', 'wio_users.id', '=', 'user_address_data.wio_user_id')
            ->join('user_basic_data', 'wio_users.id', '=', 'user_basic_data.wio_user_id')
            ->where('wio_users.id', $requiredFields['userId'])
            ->where('wio_user_flags.flag_type_id', '<', 3)
            ->where('recrutation_roles.program_short_name', $requiredFields['program'])
            ->select([
                'wio_flow_entities.id' => 'wioFlowEntityId',
                'wio_user_flags.flag_type_id' => 'volunteerFlagId',
                'user_recrutation_answers_volunteer2016.szp_editions' => 'editionSzPCount',
                'user_recrutation_answers_volunteer2016.ap_editions' => 'editionAPCount',
                'user_recrutation_answers_volunteer2016.who_told_you' => 'infoSource',
                'user_recrutation_answers_volunteer2016.openQuestion1' => 'openQuestion1',
                'user_recrutation_answers_volunteer2016.openQuestion2' => 'openQuestion2',
                'user_recrutation_answers_volunteer2016.openQuestion3' => 'openQuestion3',
                'user_recrutation_answers_volunteer2016.openQuestion4' => 'openQuestion4',
                'user_recrutation_answers_volunteer2016.cvFileId' => 'uploadFile2',
                'user_recrutation_answers_volunteer2016.occupation' => 'engagement',
                'user_recrutation_answers_volunteer2016.education' => 'education',
                'user_recrutation_answers_volunteer2016.workExperience' => 'workExperience',
                'user_recrutation_answers_volunteer2016.wiosna_volunteerings' => 'previousRoles',
                'user_address_data.province' => 'province',
                'user_address_data.city' => 'city',
                'user_address_data.postal_code' => 'zipCode',
                'user_address_data.address_street' => 'street',
                'user_address_data.address_number' => 'houseNumber',
                'user_basic_data.id_number' => 'pesel',
                'user_basic_data.birth_date' => 'birthDate',
            ])
            ->first();

        if (!empty($result)) {
            $this->data['wioFlowEntityId'] = $result->wioFlowEntityId;
            $this->data['volunteerFlagId'] = $result->volunteerFlagId;
            $this->data['editionSzPCount'] = $result->editionSzPCount;
            $this->data['editionAPCount'] = $result->editionAPCount;
            $this->data['infoSource'] = $result->infoSource;
            $this->data['openQuestion1'] = $result->openQuestion1;
            $this->data['openQuestion2'] = $result->openQuestion2;
            $this->data['openQuestion3'] = $result->openQuestion3;
            $this->data['openQuestion4'] = $result->openQuestion4;
            $this->data['uploadFile2'] = $result->uploadFile2;
            $this->data['engagement'] = $result->engagement;
            $this->data['education'] = $result->education;
            $this->data['workExperience'] = $result->workExperience;
            $this->data['previousRoles'] = $result->previousRoles;
            $this->data['province'] = $result->province;
            $this->data['city'] = $result->city;
            $this->data['zipCode'] = $result->zipCode;
            $this->data['street'] = $result->street;
            $this->data['houseNumber'] = $result->houseNumber;
            $this->data['pesel'] = $result->pesel;
            $this->data['birthDate'] = $result->birthDate;
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

        if (isset($this->data['uploadFile2'])) {
            $result = $queryBuilder->table('uploaded_files')
                ->where('id', $this->data['uploadFile2'])
                ->first();

            if ($result) {
                $this->data['uploadFile2'] = [
                    'fileName' => $result->file_real_name,
                    'fileLink' => $result->file_path,
                ];
            }
        }

        $this->setRepositoryFlags();

        return $this->data;
    }
}
