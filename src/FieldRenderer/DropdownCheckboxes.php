<?php

namespace WioForms\FieldRenderer;

class DropdownCheckboxes extends AbstractFieldRenderer
{
    public function showToEdit()
    {
        $this->html = '';
        $this->prepareDataSet();
        $this->prepareValueArray();
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();

        foreach ($this->dataSet as $groupName => $groupData) {
            $this->html .= '<div class="wioForms_DropdownCheckboxes_outerContainer">';
            $this->html .= '<input type="checkbox" name="'.$this->fieldName.'[]" ';
            if (isset($this->valueArray[$groupName])) {
                $this->html .= 'checked="checked" ';
            }
            $this->html .= 'class="wioForms_DropdownCheckboxes_outerContainerCheckbox" value="'.$groupName.'" /> '.$groupName.'<br/>';

            $inner_html = '';
            foreach ($groupData as $item) {
                if (is_string($item)) {
                    $inner_html .= '<input type="checkbox" name="'.$this->fieldName.'[]" value="'.$item.'"';
                    if (isset($this->valueArray[$groupName]) and isset($this->valueArray[$item])) {
                        $inner_html .= ' checked="checked"';
                    }
                    $inner_html .= '> '.$item.'<br/>';
                } else {
                    if ($item['name'] != '') {
                        $inner_html .= $item['name'].': ';
                    }
                    $inner_html .= '<input type="text" name="'.$this->fieldName.'['.$item['textfield'].']" value="'.$this->textValuesArray[$item['textfield']].'">';
                }
            }

            if (count($inner_html) != 0) {
                $this->html .= '<div class="wioForms_DropdownCheckboxes_innerContainer"';
                if (!isset($this->valueArray[$groupName])) {
                    $this->html .= ' style="display: none;"';
                }
                $this->html .= '>'.$inner_html.'</div>';
            }

            $this->html .= '</div>';
        }

        $this->html .= $this->javascriptActions();

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    private function javascriptActions()
    {
        return <<<'EOT'
        <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery('.wioForms_DropdownCheckboxes_outerContainerCheckbox').click(function(){
                if($(this).is(':checked') == true){
                    $(this).parent().children('.wioForms_DropdownCheckboxes_innerContainer').slideDown();
                }else{
                    $(this).parent().children('.wioForms_DropdownCheckboxes_innerContainer').slideUp();
                    $(this).parent().children('.wioForms_DropdownCheckboxes_innerContainer').children('input').each(function(){
                        if($(this).attr('type') == 'checkbox'){
                            $(this).attr('checked', false);
                        }else{
                            $(this).val('');
                        }
                    });
                }
            });
        });
        </script>
EOT;
    }

    private function prepareValueArray()
    {
        $valArray = array_flip(explode('|', $this->value));
        $this->valueArray = $valArray;

        $this->textValuesArray = [];

        foreach ($this->dataSet as $groupName => $groupData) {
            unset($valArray[$groupName]);
            foreach ($groupData as $item) {
                if (is_string($item)) {
                    unset($valArray[$item]);
                } else {
                    $this->textValuesArray[$item['textfield']] = '';
                }
            }
        }

        foreach ($valArray as $textinput => $none) {
            $keyA = each($this->textValuesArray);
            $key = $keyA['key'];
            $this->textValuesArray[$key] = $textinput;
        }
    }

    public function showToView()
    {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}
