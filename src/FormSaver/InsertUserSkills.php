<?php

namespace WioForms\FormSaver;

class InsertUserSkills extends AbstractFormSaver
{
    public function makeSavingAction($settings)
    {
        $userIdFieldName = $settings['userId'];
        $fieldName = $settings['field'];

        $databaseConnection = $this->wioForms->databaseService->connections['Main'];

        $wioUserId = $this->wioForms->formStruct['Fields'][$userIdFieldName]['value'];
        $skillsList = $this->wioForms->formStruct['Fields'][$fieldName]['value'];

        $skillsTab = explode('|',$skillsList);

        foreach ($skillsTab as $skill) {
            $skillParts = explode('-', $skill);
            if(count($skillParts) == 3) {
                $query = [
                    'table' => 'user_skills',
                    'insert' => [
                        'wio_user_id' => $wioUserId,
                        'group_id' => $skillParts[0],
                        'skill_id' => $skillParts[1],
                        'skill_name' => $skillParts[2]
                    ]
                ];
            } else {
                $query = [
                    'table' => 'user_skills',
                    'insert' => [
                        'wio_user_id' => $wioUserId,
                        'group_id' => 0,
                        'skill_id' => 0,
                        'skill_name' => $skill
                    ]
                ];
            }
            $insertedId = $databaseConnection->insert($query);
        }


/*
CREATE TABLE `user_skills`(
    `id` INT(11) NOT NULL AUTO_INCREMENT ,
    `wio_user_id` INT(11) NOT NULL ,
    `group_id` INT(5) NOT NULL ,
    `skill_id` INT(5) NOT NULL ,
    `skill_name` VARCHAR(256) NOT NULL ,
    `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
*/
    }
}
