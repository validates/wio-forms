<?php
namespace WioForms\FieldRenderer;

class GoogleMap extends AbstractFieldRenderer
{

    function showToEdit()
    {
        $this->html = '';
        $this->inputContainerHead();
        $this->standardErrorDisplay();
        $this->inputFieldContainerHead();


        if (!isset($this->wioForms->settings['GoogleMapsApi']['Key']))
        {
            $this->wioForms->errorLog->errorLog('No GoogleMapsApi Key in settings');
        }

        $this->html .='<input type="input" name="'.$this->fieldName.'" value="'.$this->value.'" />';

        $this->wioForms->headerCollectorService->addJS('assets/js/wojewodztwa16.js');

        $this->html .= $this->javascriptMapManager();

        $this->html .= '<div id="map" class="wioForms_Map"></div>';
        $this->html .= '<script src="https://maps.googleapis.com/maps/api/js?key='.$this->wioForms->settings['GoogleMapsApi']['Key'].'&callback=initMap" async defer></script>';


        $this->inputFieldContainerTail();
        $this->inputContainerTail();

        return $this->html;

    }

    function javascriptMapManager(){
        return <<<EOT
        <script type="text/javascript">
        var map;
        WOJ={};
        WOJoptions={zoomLvl: 6, zoomStage: 1, selected: 'mazowieckie'};

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
            centerOnWojewodztwo(wojewodztwoName);
            $('select[name="country_state"] option').each(function(){
                if(WOJoptions.selected == $(this).html()){
                    var node_id = $(this).attr('value');
                    $('select[name="country_state"]').val(node_id);
                    stateNodeChanged(node_id);
                }

            });
        }

        function centerOnWojewodztwo(wojewodztwoName){
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
        }

        function mapDragged(){
            var u = map.getCenter();
            for(var i in WOJ){
                if(google.maps.geometry.poly.containsLocation(u, WOJ[i].GMO)){
                    console.log('mapDrag('+i+')');
                    if(i != WOJoptions.selected){
                        selectWojewodztwo(i);
                    }
                    break;
                }
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
                    hideMarkers();
                    for(var wojName in WOJ){
                        WOJ[wojName].GMO.setOptions({fillOpacity:0.3});
                    }
                    if(WOJoptions.selected){
                        WOJ[WOJoptions.selected].GMO.setOptions({fillOpacity:0.7});
                    }
                break;
                case 2:
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
                    for(var wojName in WOJ){
                        WOJ[wojName].GMO.setOptions({fillOpacity:0});
                        showMarkers(wojName);
                    }
                break;
            }
            console.log(WOJoptions.zoomStage);
        }

        function createWojewodztwa(){
            for(var i in WOJEWODZTWA){
                WOJ[i] = {center:{lat:0,lng:0}};
                WOJ[i].GMO = new google.maps.Polygon({
                    path: WOJEWODZTWA[i],
                    wojewodztwoName: i,
                    geodesic: true,
                    fillColor: '#FF0000',
                    fillOpacity: 0.3,
                    strokeWeight: 0
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
                    if(WOJoptions.zoomStage == 1){
                        this.setOptions({fillOpacity:0.3});
                    }
                });

            }

        }

        function createMarkers(){
            if(typeof RejonySzp == 'undefined'){
                setTimeout(createMarkers,200);
                return 1;
            }

            for(var Vv in RejonySzp){
                for(var R in RejonySzp[Vv]){
                    RejonySzp[Vv][R].GMO = new google.maps.Marker({
                        position: {lat:RejonySzp[Vv][R].lat, lng:RejonySzp[Vv][R].lng},
                        rejonId: R
                    });
                    RejonySzp[Vv][R].GMO.addListener('click',function(){
                        regionNodeChanged(this.rejonId);
                    });
                }
            }
        }

        function showMarkers(wojName){
            console.log('showMarkers('+wojName+')');
            for(var R in RejonySzp[wojName]){
                RejonySzp[wojName][R].GMO.setMap(map);
            }
        }

        function hideMarkers(){
            for(var Vv in RejonySzp){
                for(var R in RejonySzp[Vv]){
                    RejonySzp[Vv][R].GMO.setMap(null);
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
            createMarkers();
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
