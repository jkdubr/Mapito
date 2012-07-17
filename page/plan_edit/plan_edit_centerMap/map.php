<?php
$planId = (int) $_GET['planId'];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <meta charset="utf-8"/>          
        <title>Map settings</title>

        <script src="../../../module/viewer/lib/js/openlayers/OpenLayers.js"></script>

        <script>
            var map;
            function init() {

                var point = new OpenLayers.LonLat(1734313.1855206, 6412903.6720712); //stred republiky
                var zoom = 0;


                var options = {
                    projection: new OpenLayers.Projection("EPSG:900913"),
                    units: "m",
                    maxExtent: new OpenLayers.Bounds(-20037508, -20037508, 20037508, 20037508.34),
                    allOverlays: true,
                    controls: [
                        new OpenLayers.Control.Navigation(),
                        new OpenLayers.Control.KeyboardDefaults(),
                        new OpenLayers.Control.PanZoom(),
                        new OpenLayers.Control.MousePosition()    
                    ]
                };

                map = new OpenLayers.Map('map', options);

  
                var  gsat = new OpenLayers.Layer.Google(
                "Google Satellite",
                {
                    'sphericalMercator': true,
                    numZoomLevels: 21
                    
                })
  
                map.addLayers([gsat]);
 
                map.setCenter(point, zoom);
    
    

                OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {                
   
                    initialize: function() {
                        this.handler = new OpenLayers.Handler.Click(
                        this, {
                            'click': this.trigger
                        }, this.handlerOptions
                    );
                    }
        
                    /*, 

        trigger: function(e) {
            //var lonlat = map.getLonLatFromViewPortPx(e.xy);
            
            var lonlat = map.getCenter();
            var zoomMap = map.getZoom();    
        
            window.opener.document.getElementById('lon').value = lonlat.lon;
            window.opener.document.getElementById('lat').value = lonlat.lat;
            window.opener.document.getElementById('zoom').value = zoomMap;
        
            if(stred.features.length > 0) {
                stred.removeFeatures(stred.features[0])
            }
            var point = new OpenLayers.Geometry.Point(lonlat.lon, lonlat.lat);
            var Fpoint = new OpenLayers.Feature.Vector(point);
            stred.addFeatures(Fpoint);
        
        }*/

                });


            }

            function saveMapCenter(){
                var lonlat = map.getCenter();
                var zoomMap = map.getZoom();    
                window.opener.document.getElementById('plan_edit<?php echo($planId); ?>_mapCenterLon').value = lonlat.lon;
                window.opener.document.getElementById('plan_edit<?php echo($planId); ?>_mapCenterLat').value = lonlat.lat;
                window.opener.document.getElementById('plan_edit<?php echo($planId); ?>_mapZoom').value = zoomMap;
        
    
            }            
        </script>

        <script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>


    </head>
    <body onload="init()">
        <div id="map" style="width:600px; height:550px"></div>
        <div id="lista">
            <input type="button" name="Save" value="Save" onClick="saveMapCenter();window.close()"/>
        </div>

    </body>
</html>