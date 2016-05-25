<?php
namespace WioForms\FieldRenderer;

class NodeSelect extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $this->html = '';
        $this->prepareDataSet();
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();


        $this->html .= '<select name="country_state">';
        $this->html .= '<option value="">wybierz</option>';
        foreach ($this->dataSet as $wojewodztwoName => $wojewodztwo)
        {
            $this->html .= '<option value="'.$wojewodztwo['node_id'].'">'.$wojewodztwoName.'</option>';
        }
        $this->html .='</select>';


        $this->html .= '<div class="wioForms_InputTitleContainer">Wybierz Region:</div>';
        $this->html .= '<select name="szp_regions">';
        $this->html .= '<option class="region_state_none" value="">wybierz</option>';
        foreach ($this->dataSet as $wojewodztwoName => $wojewodztwo)
        {
            foreach ($wojewodztwo['szp_regions'] as $regionName => $region)
            {
                $this->html .= '<option class="region_state_'.$wojewodztwo['node_id'].'" value="'.$region['node_id'].'">'.$regionName.'</option>';
            }
        }
        $this->html .='</select>';

        $this->html .= <<<EOT
            <script type="text/javascript">
            $('select[name="country_state"]').change(function(){
                var node_id = $(this).val();
                $('select[name="szp_regions"] option').hide();
                $('select[name="szp_regions"] .region_state_none').show();
                $('select[name="szp_regions"] .region_state_'+node_id).show();
                $('select[name="szp_regions"]').val('');
            });
            </script>
EOT;

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    function showToView()
    {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}
