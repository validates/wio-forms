<?php

namespace WioForms\FieldRenderer;

class SwitcherCheckbox extends AbstractFieldRenderer
{
    public function showToEdit()
    {
        $this->html = '';
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();

        $checked = '';
        if ($this->value == 'on') {
            $checked = 'checked="checked"';
        }

        $this->html .= '<input type="hidden" name="'.$this->fieldName.'" value="off" />';
        $this->html .= '<input type="checkbox" name="'.$this->fieldName.'" value="on" '.$checked.' class="WioForms_SwitcherCheckBox '.$this->getAdditionalInputClasses().'" />';

        $this->html .= $this->javascriptFieldsSwitcher();

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    public function showToView()
    {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }

    private function javascriptFieldsSwitcher()
    {

        $conditions = $this->fieldInfo["switcherInputs"];

        $return = '<script type="text/javascript">'."\n";
        $return .= 'WioForms_SwitcherCheckBox={'."\n";
        $return .= '"'.$this->fieldName.'": {'."\n";
        $return .= 'on: ["'.implode($conditions['on'],'","').'"],'."\n";
        $return .= 'off: ["'.implode($conditions['off'],'","').'"],'."\n";
        $return .= '}};';

        $return .= <<<'EOT'

        function WioForms_SwitcherCheckBoxSwitch(fieldName,option){
            var F = WioForms_SwitcherCheckBox[fieldName];
            var Show = 'on';
            var Hide = 'off';

            if(option == 'off'){
                Show = 'off';
                Hide = 'on';
            }

            for(var i in F[Show]){
                $('.wioForms_InputContainer_'+F[Show][i]).show();
            }
            for(var i in F[Hide]){
                $('.wioForms_InputContainer_'+F[Hide][i]).hide();
            }
        }


        $(document).ready(function(){
            $('.WioForms_SwitcherCheckBox').change(function(){
                var fieldName = $(this).attr('name');
                var option = 'off';
                if($(this).attr('checked'))
                    option = 'on';

                WioForms_SwitcherCheckBoxSwitch(fieldName,option);
            });
        });

EOT;

        $option = 'off';
        if($this->value) $option = $this->value;
        $return .= '$(document).ready(function(){ WioForms_SwitcherCheckBoxSwitch("'.$this->fieldName.'","'.$option.'"); });';
        $return .= '</script>';


        return $return;

    }

}
