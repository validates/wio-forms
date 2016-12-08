<?php

namespace WioForms\ContainerValidator;

class AllFieldsOk extends AbstractContainerValidator
{
    public function validatePHP(&$container, &$settings)
    {
        $this->invalidMessage = 'fill_all_fields';
        $this->valid = true;

        if (isset($settings['containerToCheck'])) {
            $containersToCheck = [];
            array_push($containersToCheck, $settings['containerToCheck']);

            $contContains = $this->wioForms->containersContains;
            while ($containerName = array_pop($containersToCheck)) {
                if (isset($contContains[$containerName])) {
                    foreach ($contContains[$containerName] as $elem) {
                        if ($elem['type'] == 'Fields') {
                            if ($this->wioForms->formStruct['Fields'][$elem['name']]['state'] < 1) {
                                $this->valid = false;
                                break;
                            }
                        } elseif ($elem['type'] == 'Containers') {
                            array_push($containersToCheck, $elem['name']);
                        }
                    }
                }
                if (!$this->valid) {
                    break;
                }
            }
        }

        $this->setAnswer();

        return $this->getReturn();
    }
}
