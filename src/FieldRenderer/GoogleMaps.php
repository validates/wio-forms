<?php

namespace WioForms\FieldRenderer;

class GoogleMaps extends AbstractFieldRenderer
{
    protected $declension = [];

    public function showToEdit()
    {
        $this->html = '';
        $this->prepareDataSet();
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputTitleContainer();
        $this->inputFieldContainerHead();

        if ($this->wioForms->entryData['akcja'] == 'AP') {
            $this->declension = ['miasto', 'miasta', 'miast'];
        }

        if ($this->wioForms->entryData['akcja'] == 'SZP') {
            $this->declension = ['rejon', 'rejony', 'rejonów'];
        }

        if (isset($this->fieldInfo['rendererData']['declension'])) {
            $this->declension = $this->fieldInfo['rendererData']['declension'];
        }

        if (!isset($this->wioForms->settings['GoogleMapsApi']['Key'])) {
            $this->wioForms->errorLog->errorLog('No GoogleMapsApi Key in settings');
        }

        $this->html .= '<select name="country_state">';
        $this->html .= '<option value="">wybierz</option>';
        foreach ($this->dataSet as $wojewodztwoName => $wojewodztwo) {
            $this->html .= '<option value="'.$wojewodztwo['node_id'].'">'.$wojewodztwoName.'</option>';
        }
        $this->html .= '</select>';

        if (isset($this->fieldInfo['rendererData']['secondLvl'])) {
            $this->html .= '<div class="wioForms_InputTitleContainer">'.$this->fieldInfo['rendererData']['secondLvlTitle'].'</div>';
            $this->html .= '<select name="szp_regions">';
            $this->html .= '<option class="region_state_none" value="">wybierz</option>';
            foreach ($this->dataSet as $wojewodztwoName => $wojewodztwo) {
                foreach ($wojewodztwo[$this->fieldInfo['rendererData']['secondLvl']] as $regionName => $region) {
                    if (isset($region['grey']) && $region['grey'] !== 'false') {
                        continue;
                    }
                    $this->html .= '<option class="region_state_'.$wojewodztwo['node_id'].'" value="'.$region['node_id'].'">'.$regionName.'</option>';
                }
            }
            $this->html .= '</select>';
            $this->html .= $this->javascriptNodesInfo();
        }

        $this->html .= $this->javascriptMapManager();
        $this->html .= $this->javascriptSelectManager();
        $this->html .= $this->javascriptDefaultDataSetter();

        $this->wioForms->headerCollectorService->addJS('assets/js/wojewodztwa16.js');

        $this->html .= '<div id="map" class="wioForms_Map"></div>';
        $this->html .= '<script src="https://maps.googleapis.com/maps/api/js?key='.$this->wioForms->settings['GoogleMapsApi']['Key'].'"></script>';
        $this->html .= '<script type="text/javascript" src="//rawgit.com/googlemaps/v3-utility-library/master/infobox/src/infobox.js"></script>';
        $this->html .= '<script type="text/javascript" src="//rawgit.com/googlemaps/js-map-label/gh-pages/src/maplabel-compiled.js"></script>';

        if (!empty($this->fieldInfo['infoBox'])) {
            $this->html .= '<div id="infoBox" class="box"></div>';
        }
        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;
    }

    private function javascriptNodesInfo()
    {
        $js = '';
        foreach ($this->dataSet as $wojewodztwoName => $wojewodztwo) {
            $js .= '"'.$wojewodztwoName.'":{';
            foreach ($wojewodztwo[$this->fieldInfo['rendererData']['secondLvl']] as $regionName => $region) {
                $js .= $region['node_id'].':{name:"'.$regionName.'",lat:'.$region['lat'].',lng:'.$region['lng'].',grey:'.$region['grey'].'},';
            }
            $js .= '},';
        }

        return '<script type="text/javascript">SecondLvlMarkers={'.$js.'};</script>';
    }

    private function javascriptSelectManager()
    {
        $return = <<<'EOT'
        <script type="text/javascript">
EOT;
        if (!empty($this->fieldInfo['infoBox'])) {
            $return .= 'var ajaxUrl = '."'".$this->fieldInfo['infoBox']['url']."'".';';
        } else {
            $return .= 'var ajaxUrl = false;';
        }
        $return .= <<<'EOT'

        $('select[name="country_state"]').change(function(){
            var node_id = $(this).val();
            stateNodeChanged(node_id);
            var wojewodztwoName = $('select[name="country_state"] option[value="'+node_id+'"]').html();
            WOJoptions.selected = wojewodztwoName;

            centerOnWojewodztwo(wojewodztwoName,node_id);
        });
        $('select[name="szp_regions"]').change(function(){
            var node_id = $(this).val();
            $('input[name="node_id"]').val(node_id);
            if (ajaxUrl) {
                fillInfoBox(ajaxUrl, node_id);
            }
        });
        function stateNodeChanged(node_id){
            $('select[name="szp_regions"] option').hide();
            $('select[name="szp_regions"] .region_state_none').show();
            $('select[name="szp_regions"] .region_state_'+node_id).show();
            $('select[name="szp_regions"]').val('');
        }
        function regionNodeChanged(node_id){

            if (ajaxUrl) {
                fillInfoBox(ajaxUrl, node_id);
            }
            var selectedOption = $('select[name="szp_regions"] option[value="'+node_id+'"]');

            if (selectedOption.length) {
                var state_node_id = selectedOption.attr('class').split('_')[2];
            } else {
                $('select[name="szp_regions"]').val('');
                $('input[name="node_id"]').val('');
                return false;
            }

            stateNodeChanged(state_node_id);
            $('select[name="country_state"]').val(state_node_id);
            $('select[name="szp_regions"]').val(node_id);
            $('input[name="node_id"]').val(node_id);
        }

        function fillInfoBox(ajaxUrl, node_id) {
            $.ajax({
                url: ajaxUrl+node_id,
                success: function(data) {
                    $('#infoBox')
                        .html(data)
                },
                type: 'GET'
            });
        }
        </script>
EOT;

        return $return;
    }

    public function javascriptMapManager()
    {
        $return = <<<'EOT'
        <script type="text/javascript">
        var map;
        var circleBoxes = [];
        WOJ={};
EOT;
        $return .= 'var program = '.($this->wioForms->entryData['akcja'] == 'AP' ? '"AP"' : '"SZP"').';';
        $return .= 'WOJoptions={';
        $return .= 'zoomLvl: 6, ';
        $return .= 'zoomStage: 1, ';
        $return .= "selected: 'mazowieckie', ";
        $return .= 'secondLvl: '.((isset($this->fieldInfo['rendererData']['secondLvl'])) ? 'true' : 'false').',';
        $return .= "color: '".($this->fieldInfo['rendererData']['mapColor'] ? $this->fieldInfo['rendererData']['mapColor'] : '#FF0000')."',";
        $return .= 'declension: '.json_encode($this->declension).'};';
        $return .= <<<'EOT'

        function findPolygonCenter(P){
            var latMin = 200, latMax = -200, lngMin = 200, lngMax = -200;
            for(var p in P){
                if(P[p].lat > latMax) latMax = P[p].lat;
                if(P[p].lat < latMin) latMin = P[p].lat;
                if(P[p].lng > lngMax) lngMax = P[p].lng;
                if(P[p].lng < lngMin) lngMin = P[p].lng;
            }
            return {
                lat:(latMin- -(latMax-latMin)/2),
                lng:(lngMin- -(lngMax-lngMin)/2)
            };
        }

        function selectWojewodztwo(wojewodztwoName){
            WOJoptions.selected = wojewodztwoName;
            $('select[name="country_state"] option').each(function(){
                if(WOJoptions.selected == $(this).html()){
                    var node_id = $(this).attr('value');
                    centerOnWojewodztwo(wojewodztwoName,node_id);
                    $('select[name="country_state"]').val(node_id);
                    stateNodeChanged(node_id);
                }

            });
        }

        function centerOnWojewodztwo(wojewodztwoName,node_id){
            if(WOJoptions.secondLvl){
                map.setZoom(8);
                var center = WOJ[wojewodztwoName].center;
                map.setCenter({lat:center.lat,lng:center.lng});
                if(WOJoptions.zoomStage == 2 && WOJoptions.selected){
                    hideMarkers();
                    for(var i in WOJ){
                        WOJ[i].GMO.setOptions({fillOpacity:0});
                    }
                    showMarkers(WOJoptions.selected);
                    WOJ[WOJoptions.selected].GMO.setOptions({fillOpacity:0.3});
                }
            }else{
                map.setZoom(7);
                var center = WOJ[wojewodztwoName].center;
                map.setCenter({lat:center.lat,lng:center.lng});
                $('input[name="node_id"]').val(node_id);
                for(var i in WOJ){
                    WOJ[i].GMO.setOptions({fillOpacity:0.3});
                }
                WOJ[wojewodztwoName].GMO.setOptions({fillOpacity:0.7});
            }
        }

        function mapDragged(){
            var u = map.getCenter();
            for(var i in WOJ){
                if(google.maps.geometry.poly.containsLocation(u, WOJ[i].GMO)){
                    if(i != WOJoptions.selected){
                        selectWojewodztwo(i);
                    }
                    break;
                }
            }
        }

        function openCircleBoxes()
        {
            for(var i in circleBoxes) {
                circleBoxes[i].open(map);
            }
        }

        function closeCircleBoxes()
        {
            for(var i in circleBoxes) {
                circleBoxes[i].close();
            }
        }

        function changeZoom(zoomLvl){
            var oldZoomStage = WOJoptions.zoomStage;

            if(zoomLvl < 8){
                WOJoptions.zoomStage = 1;
            }
            if(zoomLvl > 7 && zoomLvl < 11){
                WOJoptions.zoomStage = 2;
            }
            if(zoomLvl > 10){
                WOJoptions.zoomStage = 3;
            }

            if(oldZoomStage != WOJoptions.zoomStage){
                changeZoomStage();
            }
        }

        function changeZoomStage(){
            switch(WOJoptions.zoomStage){
                case 1:
                    openCircleBoxes();
                    hideMarkers();
                    for(var wojName in WOJ){
                        WOJ[wojName].GMO.setOptions({fillOpacity:0.3});
                    }
                    if(WOJoptions.selected){
                        WOJ[WOJoptions.selected].GMO.setOptions({fillOpacity:0.7});
                    }
                break;
                case 2:
                    closeCircleBoxes();
                    hideMarkers();
                    for(var wojName in WOJ){
                        WOJ[wojName].GMO.setOptions({fillOpacity:0});
                    }
                    if(WOJoptions.selected){
                        WOJ[ WOJoptions.selected ].GMO.setOptions({fillOpacity:0.3});
                        showMarkers(WOJoptions.selected);
                    }

                break;
                case 3:
                    openCircleBoxes();
                    for(var wojName in WOJ){
                        WOJ[wojName].GMO.setOptions({fillOpacity:0});
                        showMarkers(wojName);
                    }
                break;
            }
        }

        function createWojewodztwa(){
            var areaCount = 0;

            for(var i in WOJEWODZTWA) {

                if (typeof SecondLvlMarkers !== 'undefined') {
                    areaCount = Object.keys(SecondLvlMarkers[i]).length;
                }

                WOJ[i] = {center:{lat:0,lng:0}};
                WOJ[i].GMO = new google.maps.Polygon({
                    path: WOJEWODZTWA[i],
                    wojewodztwoName: i,
                    geodesic: true,
                    fillColor: WOJoptions.color,
                    fillOpacity: 0.3,
                    strokeWeight: 1,
                    // zIndex: 1
                });

                WOJ[i].GMO.setMap(map);
                WOJ[i].center = findPolygonCenter(WOJEWODZTWA[i]);

                WOJ[i].GMO.addListener('click', function(){
                    selectWojewodztwo(this.wojewodztwoName);
                });
                WOJ[i].GMO.addListener('mouseover', function(){
                  if(WOJoptions.zoomStage == 1){
                      this.setOptions({fillOpacity:0.7});
                  }
                });
                WOJ[i].GMO.addListener('mouseout', function(){
                    if(WOJoptions.zoomStage == 1 && !(!WOJoptions.secondLvl && this.wojewodztwoName == WOJoptions.selected)){
                        this.setOptions({fillOpacity:0.3});
                    }
                });

                if (areaCount > 0) {
                    var circleBoxOptions = {
                        content: '<div class="circle-box" style="color: ' + WOJoptions.color +'"><span class="count">' + areaCount + '</span>' + declension(areaCount) + '</div>',
                        position: new google.maps.LatLng(WOJEWODZTWA_CENTRUM[i].lat, WOJEWODZTWA_CENTRUM[i].lng),
                        disableAutoPan: true,
                        closeBoxURL: "",
                        isHidden: false,
                    };

                    var circleBox = new InfoBox(circleBoxOptions);
                    circleBox.open(map);
                    circleBoxes.push(circleBox);
                }

            }

        }

        function createMarkers(){
            if(typeof SecondLvlMarkers == 'undefined'){
                setTimeout(createMarkers,200);
                return 1;
            }

            for(var Vv in SecondLvlMarkers){
                for(var R in SecondLvlMarkers[Vv]){
                    var obj = {
                        position: {lat:SecondLvlMarkers[Vv][R].lat, lng:SecondLvlMarkers[Vv][R].lng},
                        rejonId: R
                    };
                    if (SecondLvlMarkers[Vv][R].grey === true) {
                        obj.icon = 'https://www.google.com/mapfiles/marker_grey.png';
                    }
                    SecondLvlMarkers[Vv][R].GMO = new google.maps.Marker(obj);
                    SecondLvlMarkers[Vv][R].GMO.addListener('click',function(){
                        regionNodeChanged(this.rejonId);
                    });
                }
            }
        }

        function showMarkers(wojName){
            for(var R in SecondLvlMarkers[wojName]){
                SecondLvlMarkers[wojName][R].GMO.setMap(map);
            }
        }

        function hideMarkers(){
            for(var Vv in SecondLvlMarkers){
                for(var R in SecondLvlMarkers[Vv]){
                    SecondLvlMarkers[Vv][R].GMO.setMap(null);
                }
            }
        }

        function initMap() {
            var styleArray = [{ featureType: "all", stylers: [{ saturation: -80 }]}];
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 52, lng: 19.5},
                zoom: 6,
                styles: styleArray,
                mapTypeControl: false,
                streetViewControl: false,
            });

            map.addListener('zoom_changed',function(){
                changeZoom(map.getZoom());
            });
            map.addListener('dragend',function(){
                if(WOJoptions.zoomStage == 2){
                    mapDragged();
                }
            });

            createWojewodztwa();
            if(WOJoptions.secondLvl){
                createMarkers();
            }
        }

        function declension(number) {

        	if (number !== 1 && (number % 10 <= 1 || number % 10 >= 5 || (number % 100 >= 11 && number % 100 <= 19))) {
                return WOJoptions.declension[2];
            } else if (number == 1) {
                return WOJoptions.declension[0];
            }
            return WOJoptions.declension[1];
        }

        $(function() {
            initMap();
        });
        </script>
EOT;

        return $return;
    }

    private function javascriptDefaultDataSetter()
    {
        $return = <<<'EOT'
        <script type="text/javascript">

        function getDefaultNodeIdValue(){
            var node_id = parseInt($('input[name="node_id"]').val());
            if (isNaN(node_id)) return 1;

            regionNodeChanged(node_id);
        }
        getDefaultNodeIdValue();
        </script>
EOT;

        return $return;
    }

    public function showToView()
    {
        $html = 'TextInput: '.'abc'.'<br/>';

        return $html;
    }
}
