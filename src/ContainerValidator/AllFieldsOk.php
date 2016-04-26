<?php
namespace WioForms\ContainerValidator;

class AllFieldsOk extends AbstractContainerValidator
{

    public function validatePHP(&$container, &$settings)
    {

        $valid = true;

        if (isset($settings['containerToCheck']))
        {
            $containersToCheck = [];
            array_push($containersToCheck, $settings['containerToCheck']);


            $contContains = $this->wioForms->containersContains;
            while ($containerName = array_pop($containersToCheck))
            {
                if (isset($contContains[ $containerName ]))
                {
                    foreach ($contContains[ $containerName ] as $elem)
                    {
                        if ($elem['type'] == 'Fields')
                        {
                            if ($this->wioForms->formStruct['Fields'][ $elem['name'] ]['state'] < 1)
                            {
                                $valid = false;
                                break;
                            }
                        }
                        elseif ($elem['type'] == 'Containers')
                        {
                            array_push($containersToCheck, $elem['name']);
                        }
                    }
                }
                if (!$valid)
                {
                    break;
                }
            }
        }

        if ($valid)
        {
            $this->state = 1;
            $this->valid = true;
        }
        else
        {
            $this->state = -1;
            $this->valid = false;
            $this->message = 'fill_all_fields';
        }

        return $this->getReturn();
    }

}
