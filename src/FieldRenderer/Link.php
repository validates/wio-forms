<?php

namespace WioForms\FieldRenderer;

class Link extends AbstractFieldRenderer
{
    protected $paramSet = [];
    protected $title;

    public function showToEdit()
    {
        $this->prepareParamSet();
        $this->swapPlaceholdersWithData();
        $this->prepareTitleSet();

        $label = isset($this->fieldInfo['label']) ? $this->fieldInfo['label'] : '';
        $html = '<div>'.$label.' <a class="'.$this->fieldInfo['class'].'" href="'.$this->fieldInfo['url'].'">'.$this->title.'</a>'."\n".'</div>';

        return $html;
    }

    public function showToView()
    {
        $html = 'Link: '.'http://www.org.pl'.'<br/>'."\n";

        return $html;
    }

    private function prepareParamSet()
    {
        if (!isset($this->fieldInfo['paramSet'])) {
            return;
        }
        foreach ($this->fieldInfo['paramSet'] as $paramKey => $paramRepository) {
            $paramSetName = $paramRepository['repositoryName'];
            if (!isset($this->formStruct['DataRepositories'][$paramSetName])) {
                $this->wioForms->errorLog->errorLog('DataRepository: '.$paramSetName.' not found.');
                continue;
            }

            if (isset($paramRepository['subset'])) {
                foreach ($paramRepository['subset'] as $branchName) {
                    $this->paramSet[$paramKey] = $this->formStruct['DataRepositories'][$paramSetName]['data'][$branchName];
                }
                continue;
            }

            $this->paramSet[$paramKey] = $this->formStruct['DataRepositories'][$paramSetName]['data'];
        }
    }

    private function swapPlaceholdersWithData()
    {
        $urlPartsList = explode('/', $this->fieldInfo['url']);
        foreach ($urlPartsList as &$urlPart) {
            if (isset($this->paramSet[$urlPart])) {
                $urlPart = $this->paramSet[$urlPart];
            }
        }
        $this->fieldInfo['url'] = implode('/', $urlPartsList);
    }

    private function prepareTitleSet()
    {
        $this->title = 'LINK';
        if (isset($this->fieldInfo['title'])) {
            $this->title = $this->fieldInfo['title'];
        }

        if (isset($this->fieldInfo['titleSet']) && is_array($this->fieldInfo['titleSet'])) {
            $titleRepository = $this->fieldInfo['titleSet'];
            $this->title = $this->formStruct['DataRepositories'][$titleRepository['repositoryName']]['data'][reset($titleRepository['subset'])];
        }
    }
}
