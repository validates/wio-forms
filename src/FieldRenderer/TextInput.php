<?php
namespace WioForms\FieldRenderer;

class TextInput extends AbstractFieldRenderer
{

    function showToEdit(){
        $value = '';
        if ( !empty($this->fieldInfo['value']) )
        {
            $value = $this->fieldInfo['value'];
        }

        $message = false;
        if( isset($this->fieldInfo['valid']) and !$this->fieldInfo['valid'] )
        {
            $message = $this->fieldInfo['message'];
        }

        $html = $this->fieldInfo['title']. ': <input type="text" name="'.$this->fieldName.'" value="'.$value.'" />';
        if ($message !== false)
            $html .= '<b style="color: red;"> &nbsp; '.$message.'</b>';
        $html .= '<br/>';


        return $html;
    }

    function showToView(){
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}

?>
