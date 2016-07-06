<?php

namespace WioForms\FormSaver;

class UploadFileToFolder extends AbstractFormSaver
{
    public function makeSavingAction($settings)
    {
        $fieldName = $settings['field'];
        $dir = $settings['dir'];

        $fileData = $_FILES[$fieldName.'_file'];

        //var_dump($fileData);

        $databaseConnection = $this->wioForms->databaseService->connections['Main'];

        $wioUserId = $this->wioForms->formStruct['Fields']['userId']['value'];
        $wioFlowEntityId = $this->wioForms->formStruct['Fields']['wioFlowEntityId']['value'];

        $fileName = basename($fileData['name']);
        $fileParts = explode('.', $fileName);
        $fileType = end($fileParts);

        //var_dump($fileType);

        $hash = substr(md5(rand()), 0, 12);

        $filePath = '../uploads/'.$dir.'/wioFlowId_'.$wioFlowEntityId.'_hash_'.$hash.'.'.$fileType;

        if (move_uploaded_file($fileData['tmp_name'], BASEPATH.$filePath)) {
            // echo "The file ". basename( $fileData["name"]). " has been uploaded.";

            $query = [
                'table' => 'uploaded_files',
                'insert' => [
                    'wio_user_id' => $wioUserId,
                    'wio_flow_entity_id' => $wioFlowEntityId,
                    'file_real_name' => $fileName,
                    'file_path' => $filePath,
                    'deleted' => 'not_deleted',
                ],
            ];
            $insertedId = $databaseConnection->insert($query);

            //var_dump($insertedId);

            $this->wioForms->formStruct['Fields'][$fieldName]['value'] = $insertedId;
            $this->wioForms->formStruct['Fields'][$fieldName]['validated'] = false;
            // Ugh! Thats soooo not nice.
            $this->wioForms->validatorService->validateFields();
            $this->wioForms->validatorService->validateContainers();
        } else {
            // echo "Sorry, there was an error uploading your file.";
        }


/*
`wio_user_id` INT(11) NOT NULL ,
`wio_flow_entity_id` INT(11) NOT NULL ,
`file_name` VARCHAR(256) NOT NULL ,
`file_folder` VARCHAR(256) NOT NULL ,
`deleted` VARCHAR(16) NOT NULL ,
`date_created`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
*/
    }
}
