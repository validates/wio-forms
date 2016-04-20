<?php
namespace WioForms\FieldRenderer;

class GoogleMap extends AbstractFieldRenderer
{

    function showToEdit(){
      $html = $this->fieldInfo['title']. ': <input type="input" name="'.$this->fieldName.'" value="abc" />';
      $html .= '<br/>';

      $html .= $this->standardErrorDisplay();

      return $html;

    }

    function showToView(){
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}

?>
