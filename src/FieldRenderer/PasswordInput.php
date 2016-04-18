<?php
namespace WioForms\FieldRenderer;

class PasswordInput extends AbstractFieldRenderer
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

        $html = $this->fieldInfo['title']. ': <input type="password" name="'.$this->fieldName.'" value="'.$value.'" />';
        if ($message !== false)
            $html .= '<b style="color: red;"> &nbsp; '.$message.'</b>';
        $html .= '<br/>';

        return $html;
    }

    function showToView(){
        $html = 'PasswordInput: '.'********'.'<br/>';

        return $html;
    }
}

?>
