<?php

namespace WioForms\FieldRenderer;

class SelectWithTextfield extends AbstractFieldRenderer
{
    public function showToEdit()
    {
        $this->html = '';
        $this->prepareDataSet();
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();

        list($selectValue, $inputValue) = $this->prepareValues();

        $this->html .= '<input type="hidden" name="'.$this->fieldName.'" value="'.$this->value.'">';

        $this->html .= '<select name="'.$this->fieldName.'_select" class="wioForms_selectWithTextfield">';
        $this->html .= '<option value="">wybierz</option>';
        foreach ($this->dataSet as $itemName => $itemType) {
            $this->html .= '<option class="'.$itemName.'"';
            if ($itemName == $selectValue) {
                $this->html .= ' selected="selected"';
            }
            $this->html .= '>'.$itemName.'</option>';
        }
        $this->html .= '</select>';

        $this->html .= '<div class="'.$this->fieldName.'_textInputContainer"'.($inputValue != '' ? '' : ' style="display: none;"').'>';
        $this->html .= 'Wpisz: <input type="text" class="wioForms_SelectWithTextfield_textInput" name="'.$this->fieldName.'_textInput" value="'.$inputValue.'">';
        $this->html .= '</div>';

        $this->html .= $this->javascriptData();
        $this->html .= $this->javascriptActions();

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    private function prepareValues()
    {
        $selectValue = $this->value;
        $inputValue = '';
        $possibleInputName = '';
        $isValueFromList = false;
        foreach ($this->dataSet as $itemName => $itemType) {
            if ($itemType == 'textfield') {
                $possibleInputName = $itemName;
            }
            if ($this->value == $itemName) {
                $isValueFromList = true;
                break;
            }
        }
        if (!$isValueFromList && $this->value != '') {
            $inputValue = $this->value;
            $selectValue = $possibleInputName;
        }

        return [$selectValue, $inputValue];
    }

    private function javascriptData()
    {
        $js = '<script type="text/javascript">';
        $js .= 'WioForms_fieldData=[];';
        $js .= 'WioForms_fieldData["'.$this->fieldName.'"]={';
        foreach ($this->dataSet as $itemName => $itemType) {
            if ($itemType == 'textfield') {
                $js .= '"'.$itemName.'":true, ';
            }
        }
        $js .= '};';
        $js .= '</script>';

        return $js;
    }

    private function javascriptActions()
    {
        return <<<'EOT'
        <script type="text/javascript">
        jQuery(document).ready(function(){
            $('.wioForms_selectWithTextfield').change(function(){
                console.log($(this).val());

                var fieldValue = $(this).val();
                var fieldName = $(this).attr('name').replace('_select','');
                console.log(fieldName);
                if(WioForms_fieldData[fieldName][fieldValue]){
                    $('.'+fieldName+'_textInputContainer').slideDown();
                    $('input[name='+fieldName+']').val("");
                } else {
                    $('.'+fieldName+'_textInputContainer').slideUp();
                    $('input[name='+fieldName+'_textInput]').val("");
                    $('input[name='+fieldName+']').val(fieldValue);
                }
            });

            $('.wioForms_Form').on('click, keyup','.wioForms_SelectWithTextfield_textInput',function(){
                var fieldValue = $(this).val();
                var fieldName = $(this).attr('name').replace('_textInput','');
                $('input[name='+fieldName+']').val(fieldValue);
            });
        });
        </script>
EOT;
    }

    public function showToView()
    {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}
