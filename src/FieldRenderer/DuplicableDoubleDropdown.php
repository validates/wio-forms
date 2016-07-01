<?php

namespace WioForms\FieldRenderer;

class DuplicableDoubleDropdown extends AbstractFieldRenderer
{
    public function showToEdit()
    {
        $this->html = '';
        $this->prepareDataSet();
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputDescriptionContainer();
        $this->inputFieldContainerHead();


        $this->duplicateCounter = 0;

        $valueTab = explode('|', $this->value);

        foreach ($valueTab as $key => $value) {
            if ($value == '') {
                unset($valueTab[$key]);
            }
        }

        if (count($valueTab) == 0) {
            $valueTab[] = '-';
        }

        foreach ($valueTab as $programRoleName) {
            list($programName) = explode(' - ', $programRoleName);

            $this->html .= '<b>Program:</b> <br/> <select name="" class="DuplicableDD_level1">';
            $this->html .= '<option value="">wybierz</option>';
            $level1 = 1;
            foreach ($this->dataSet as $option => $optionData) {
                $this->html .= '<option toChange="DuplicableDD_'.$this->duplicateCounter.'" toShow="DuplicableDD_'.$this->duplicateCounter.'_'.$level1.'" value="option'.($level1++).'"';
                if ($option == $programName) {
                    $this->html .= ' selected="selected"';
                }
                $this->html .= '>'.$option.'</option>';
            }
            $this->html .= '</select><br/>';

            $this->html .= '<b>Rola:</b> <br/> <select name="'.$this->fieldName.'[]" class="DuplicableDD_'.$this->duplicateCounter.' DuplicableDD_level2">';
            $level1 = 1;
            foreach ($this->dataSet as $option => $optionData) {
                $this->html .= '<option class="DuplicableDD_'.$this->duplicateCounter.'_'.$level1.'" value="">wybierz</option>';
                foreach ($optionData as $data) {
                    $displayNone = ' style="display: none;"';
                    if ($option == $programName) {
                        $displayNone = '';
                    }
                    $this->html .= '>'.$option.'</option>';

                    $this->html .= '<option class="DuplicableDD_'.$this->duplicateCounter.'_'.$level1.'" value="'.$option.' - '.$data.'"';
                    $this->html .= '.$displayNone.';
                    if ($option.' - '.$data == $programRoleName) {
                        $this->html .= ' selected="selected"';
                    }
                    $this->html .= '>'.$data.'</option>';
                }
                ++$level1;
            }
            $this->html .= '</select><br/><br/>';
            ++$this->duplicateCounter;
        }

        $this->inputFieldContainerTail();
        $this->html .= '<br/><a id="duplicate" class="duplicatorButton">dodaj dane</a>'."\n";
        $this->inputContainerTail();

        $this->html .= $this->javascriptManager();

        return $this->html;
    }

    public function showToView()
    {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }

    private function javascriptManager()
    {
        $html = '<script type="text/javascript">';

        $html .= 'DuplicateCounter = '.$this->duplicateCounter.';'."\n";
        $html .= 'ToDuplicate=\'';
        $html .= '<b>Program:</b> <br/> <select name="" class="DuplicableDD_level1">';
        $html .= '<option value="">wybierz</option>';
        $level1 = 1;
        foreach ($this->dataSet as $option => $optionData) {
            $html .= '<option toChange="DuplicableDD_##NUMER##" toShow="DuplicableDD_##NUMER##_'.$level1.'" value="option'.($level1++).'">'.$option.'</option>';
        }
        $html .= '</select><br/>';

        $html .= '<b>Rola:</b> <br/> <select name="'.$this->fieldName.'[]" class="DuplicableDD_##NUMER## DuplicableDD_level2">';
        $html .= '<option value="">--wybierz program--</option>';
        $level1 = 1;
        foreach ($this->dataSet as $option => $optionData) {
            $html .= '<option class="DuplicableDD_##NUMER##_'.$level1.'" value="">wybierz</option>';
            foreach ($optionData as $data) {
                $html .= '<option class="DuplicableDD_##NUMER##_'.$level1.'" value="'.$option.' - '.$data.'" style="display: none;">'.$data.'</option>';
            }
            ++$level1;
        }
        $html .= '</select><br/><br/>';
        $html .= '\';'."\n\n";  // end of string

        $html .= '$(".duplicatorButton").click(function(){ DuplicateCounter++;  $(".wioForms_InputFieldContainer[data-wio-forms=\''.$this->fieldName.'\']").append( ToDuplicate.replace(new RegExp(\'##NUMER##\',\'g\'),DuplicateCounter)); });'."\n";

        $html .= '$(".wioForms_InputFieldContainer[data-wio-forms=\''.$this->fieldName.'\']").on(\'change\',\'.DuplicableDD_level1\',function(){ ';
        $html .= 'toChange = $(this).children("option:selected").attr("toChange"); ';
        $html .= 'toShow = $(this).children("option:selected").attr("toShow"); ';
        $html .= '$("."+toChange).children("option").hide(); ';
        $html .= '$("."+toShow).show(); ';
        $html .= '$("."+toChange).val(""); ';
        $html .= 'console.log(toChange); console.log(toShow); ';
        $html .= '});'."\n";

        $html .= '</script>';

        return $html;
    }
}
