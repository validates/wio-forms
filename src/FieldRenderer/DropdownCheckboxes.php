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

        foreach ($this->dataSet as $groupNumber => $groupData) {
            $this->html .= '<div class="wioForms_DropdownCheckboxes_outerContainer">';
            $inner_html = '';

            if (isset($groupData['children'])) {
                $this->html .= '<input type="checkbox" name="'.$this->fieldName.'[]" ';

                $groupValue = $groupNumber.'--'.$groupData['name'];
                if (isset($this->valueArray[$groupValue])) {
                    $this->html .= 'checked="checked" ';
                }
                $this->html .= 'class="wioForms_DropdownCheckboxes_outerContainerCheckbox" value="'.$groupValue.'" /> '.$groupData['name'].'<br/>';

                foreach ($groupData['children'] as $itemNumber => $itemData) {
                    if (is_string($itemData)) {
                        $itemValue = $groupNumber.'-'.$itemNumber.'-'.$itemData;
                        $inner_html .= '<input type="checkbox" name="'.$this->fieldName.'[]" value="'.$itemValue.'"';
                        if (isset($this->valueArray[$groupValue]) and isset($this->valueArray[$itemValue])) {
                            $inner_html .= ' checked="checked"';
                        }
                        $inner_html .= '> '.$itemData.'<br/>';
                    } else {
                        if ($itemData['name'] != '') {
                            $inner_html .= $itemData['name'].': ';
                        }
                        $inner_html .= '<input type="text" name="'.$this->fieldName.'['.$groupNumber.'-'.$itemNumber.']" value="'.$this->textValuesArray[$itemData['textfield']].'">';
                    }
                }
            }
            if (isset($groupData['textfield'])) {
                if ($groupData['name'] != '') {
                    $inner_html .= $groupData['name'].': ';
                }
                $inner_html .= '<input type="text" name="'.$this->fieldName.'['.$groupNumber.'-]" value="'.$this->textValuesArray[$groupData['textfield']].'">';
            }

            if (count($inner_html) != 0) {
	    	if (!isset($groupData['children'])) {
			$this->html .= '<div class="wioForms_DropdownCheckboxes_innerContainer">'.$inner_html.'</div>';
		} else {
		
	                $this->html .= '<div class="wioForms_DropdownCheckboxes_innerContainer"';
	                if (!isset($this->valueArray[$groupValue])) {
	                    $this->html .= ' style="display: none;"';
	                }
	                $this->html .= '>'.$inner_html.'</div>';
		}
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
        $textKeys = [];

        foreach ($this->dataSet as $groupNumber => $groupData) {
            $groupValue = $groupNumber.'--'.$groupData['name'];
            if (isset($groupData['children'])) {
                unset($valArray[$groupValue]);
                foreach ($groupData['children'] as $itemNumber => $itemData) {
                    if (is_string($itemData)) {
                        $itemValue = $groupNumber.'-'.$itemNumber.'-'.$itemData;
                        unset($valArray[$itemValue]);
                    } else {
                        $this->textValuesArray[$itemData['textfield']] = '';
                    }
                }
            }
            if (isset($groupData['textfield'])) {
                $this->textValuesArray[$groupData['textfield']] = '';
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
