<?php
namespace WioForms\FieldRenderer;

class TextInput extends AbstractFieldRenderer
{

    function ShowToEdit(){
        $value = '';
        if ( !empty($this->FieldInfo['value']) )
        {
            $value = $this->FieldInfo['value'];
        }

        $message = false;
        if( isset($this->FieldInfo['valid']) and !$this->FieldInfo['valid'] )
        {
            $message = $this->FieldInfo['message'];
        }

        $html = $this->FieldInfo['title']. ': <input type="text" name="'.$this->FieldName.'" value="'.$value.'" />';
        if ($message !== false)
            $html .= '<b style="color: red;"> &nbsp; '.$message.'</b>';
        $html .= '<br/>';


        return $html;
    }

    function ShowToView(){
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}

?>
