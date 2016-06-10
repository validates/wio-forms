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


        $this->html .= '<div class="wioForms_InputTitleContainer '.$this->getAdditionalWrapperClasses().'">Wybierz Region:</div>';
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

        $this->html .= $this->javascriptNodesInfo();
        $this->html .= $this->javascriptSelectManager();

        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    private function javascriptNodesInfo(){
        $js = '';
        foreach ($this->dataSet as $wojewodztwoName => $wojewodztwo)
        {
            $js .= '"'.$wojewodztwoName.'":{';
            foreach ($wojewodztwo['szp_regions'] as $regionName => $region)
            {
                $js .= $region['node_id'].':{name:"'.$regionName.'",lat:'.$region['lat'].',lng:'.$region['lng'].'},';
            }
            $js .= '},';
        }

        return '<script type="text/javascript">RejonySzp={'.$js.'};</script>';
    }

    private function javascriptSelectManager(){
        return <<<EOT
        <script type="text/javascript">
        $('select[name="country_state"]').change(function(){
            var node_id = $(this).val();
            stateNodeChanged(node_id);
            var wojewodztwoName = $('select[name="country_state"] option[value="'+node_id+'"]').html();
            WOJoptions.selected = wojewodztwoName;

            centerOnWojewodztwo(wojewodztwoName);
        });
        $('select[name="szp_regions"]').change(function(){
            var node_id = $(this).val();
            $('input[name="node_id"]').val(node_id);
        });
        function stateNodeChanged(node_id){
            $('select[name="szp_regions"] option').hide();
            $('select[name="szp_regions"] .region_state_none').show();
            $('select[name="szp_regions"] .region_state_'+node_id).show();
            $('select[name="szp_regions"]').val('');
        }
        function regionNodeChanged(node_id){
            var state_node_id = $('select[name="szp_regions"] option[value="'+node_id+'"]').attr('class').split('_')[2];

            stateNodeChanged(state_node_id);
            $('select[name="country_state"]').val(state_node_id);
            $('select[name="szp_regions"]').val(node_id);
            $('input[name="node_id"]').val(node_id);
        }
        </script>
EOT;
    }

    function showToView()
    {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}
