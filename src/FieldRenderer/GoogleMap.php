<?php
namespace WioForms\FieldRenderer;

class GoogleMap extends AbstractFieldRenderer
{

    function showToEdit()
    {
      $this->html = '';
      $this->prepareDataSet();
      $this->inputContainerHead();
      $this->standardErrorDisplay();
      $this->inputTitleContainer();
      $this->inputFieldContainerHead();


      if (!isset($this->wioForms->settings['GoogleMapsApi']['Key']))
      {
          $this->wioForms->errorLog->errorLog('No GoogleMapsApi Key in settings');
      }


      $this->html .='<input type="input" name="'.$this->fieldName.'" value="abc" />';


      $this->html .= '<script>
      var map;
      var styleArray = [{ featureType: "all", stylers: [{ saturation: -80 }]}];
      function initMap() {
          map = new google.maps.Map(document.getElementById(\'map\'), {
              center: {lat: 52, lng: 19.5},
              zoom: 6,
              styles: styleArray,
              mapTypeControl: false,
              streetViewControl: false,
          });
      }
      </script>';

      $this->html .= '<div id="map" class="wioForms_Map"></div>';
      $this->html .= '<script src="https://maps.googleapis.com/maps/api/js?key='.$this->wioForms->settings['GoogleMapsApi']['Key'].'&callback=initMap" async defer></script>';




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
