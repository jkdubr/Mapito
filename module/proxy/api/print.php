<?php
$spec = json_decode(str_replace('\"', '"', $_POST["spec"]));
?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <title><?php echo($spec->outputFilename); ?></title>
        
        

        <script src="../../../viewer/lib/js/openlayers/OpenLayers.js"></script>
        <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAA27tAnK9H0ScdWdR-wUE6uBT12xgyeiOZmLY-ivXV0TnI_2oYyRS2D0MHXs0b3hsez9PXCXIkJ6G-9Q"  type="text/javascript"></script>

        <script type="text/javascript">
            var map;
            var distance

            function init() {

                var options = {
                    projection: new OpenLayers.Projection("<?php echo($spec->pages[0]->srs); ?>"),
                    displayProjection: new OpenLayers.Projection("EPSG:4326"),
                    units: "m",
        
                    maxExtent: new OpenLayers.Bounds(-20037508, -20037508, 20037508, 20037508.34),
                    numZoomLevels: 22,
                    allOverlays: true,
                    controls: null
                };
    
                map = new OpenLayers.Map('print_map',options);
                //map.addControl(new OpenLayers.Control.LayerSwitcher());
            
                var gphy = new OpenLayers.Layer.Google(
                "Google Physical",
                {type: G_PHYSICAL_MAP}
            );
            
                var gsat = new OpenLayers.Layer.Google(
                "Google Satellite",
                {
                    type: G_SATELLITE_MAP,
                    'sphericalMercator': true,
                    visibility: true,
                    format: "image/png",
                    opacity: 1,
                    isBaseLayer: true,
                    numZoomLevels: 21
                })
                map.addLayers([gsat]);
<?php
/**
 *                 "{ \"type\": \"WMS\","
  +"\"layers\": [\""+printable[printableZix[i]]+"\"],"
  +"\"baseURL\": \""+wmsURL+"\","
  +"\"opacity\":"+ ligeo.ligeoMap.obecLayers[ligeo.ligeoMap.obecLayersName.indexOf(printable[printableZix[i]])].opacity  +","
  +"\"singleTile\":\"true\","
  +"\"customParams\":{"
  //       +"\"sld\":\"http://ligeo.mostar.cz/admin/api/?json={%22request%22:%22style.sld%22,%22userHash%22:%22%22,%22param%22:{%22layerId%22:124},%22format%22:%22string%22}\", "
  //+"\"sld\":\"http://ligeo.mostar.cz/admin/api/?json={%22request%22:%22style.sld%22,%22userHash%22:%22%22,%22param%22:{%22layerId%22:124},%22format%22:%22string%22}\", "
  +"\"ENV\":\"\","
  //               // +"\"SLD\":\"http://ligeo.mostar.cz/test.xml\", "

  +json_sld
  //+"\"SLD_BODY\":\"<StyledLayerDescriptor><NamedLayer><Name>ligeo_ostresany:gp_domy</Name><UserStyle><FeatureTypeStyle><Rule><LineSymbolizer><Stroke><CssParameter name='stroke'>#0000FF</CssParameter><CssParameter name='stroke-width'>9</CssParameter></Stroke></LineSymbolizer></Rule></FeatureTypeStyle></UserStyle></NamedLayer></StyledLayerDescriptor>\","

  +"\"TRANSPARENT\":\"true\""
  +"},"
  +"\"format\": \"image/png\""
  +"}";
 */
foreach ($spec->layers as $layer) {
    //print_r($layer);
    $i++;
    if ($layer->type == "xyz") {
        ?>
                    var layer<?php echo($i); ?> = new OpenLayers.Layer.XYZ("Technick mapa", "http://www.gobec.cz/tilestache/<?php echo($layer->layers[0]); ?>/${z}/${x}/${y}.png", {
                        transitionEffect: 'resize',
                                    
                        opacity: <?php echo($layer->opacity); ?>,
                        
                        isBaseLayer: false
                    });
                    map.addLayer(layer<?php echo($i); ?>);
    <?php } else {
        ?>
                                                                                            
                        var layer<?php echo($i); ?> = new OpenLayers.Layer.WMS("<?php echo($layer->layers[0]); ?>", "<?php echo($layer->baseURL); ?>",
                        {
                                                                                    
                            layers:"<?php echo($layer->layers[0]); ?>",
                            format:"<?php echo($layer->format); ?>",
        <?php if ($layer->customParams->sld) { ?>
                                sld:"<?php echo($layer->customParams->sld); ?>",
        <?php } ?>
                            name:"<?php echo($layer->layers[0]); ?>",
                            id:"<?php echo($layer->layers[0]); ?>",
                            transparent: true
                        },
                        {
                            opacity: <?php echo($layer->opacity); ?>,
                            singleTile: true,
                            isBaseLayer: false,
                            buffer: 0,
                            tileLoadingDelay: 0,
                            ratio: 1
                        }
                    );
                        map.addLayer(layer<?php echo($i); ?>);
        <?php
    }
}
?>
            

        

        map.setCenter(new OpenLayers.LonLat(<?php echo($spec->pages[0]->center[0]); ?>, <?php echo($spec->pages[0]->center[1]); ?>), <?php echo($spec->pages[0]->zoom); ?>);


        function meritko(){
            var distance = null;
            var meritkoImage = "../../img/meritko.png"
            
            var p1px = new OpenLayers.Pixel(0,0);
            var p1 = map.getLonLatFromViewPortPx(p1px)
                
            var p2px = new OpenLayers.Pixel(700,0);
            var p2 = map.getLonLatFromViewPortPx(p2px)
                
            var p1p = new OpenLayers.Geometry.Point(p1.lon,p1.lat);
            var p2p = new OpenLayers.Geometry.Point(p2.lon,p2.lat);
                
            var proj = new OpenLayers.Projection("EPSG:900913");
            var line = new OpenLayers.Geometry.LineString([p1p, p2p]); 
            var dist = Math.round(line.getGeodesicLength(proj));
        
            //var dist = line.getLength(proj);
           
        
            //////////////////////////////////////////////////////
        
            
            if (dist<70)
            {distance = 50
                var imagaPx = 700*distance/dist    
            }
            
            else if (70<=dist && dist<140)
            {distance = 50
                meritkoText = "50 m"
                var imagaPx = Math.round(700*distance/dist)}
               
            else if (140<=dist && dist<300)
            {distance = 100
                meritkoText = "100 m"
                var imagaPx = Math.round(700*distance/dist)}
                
            else if (300<=dist && dist<600)
            {distance = 200
                meritkoText = "200 m"
                var imagaPx = Math.round(700*distance/dist)}
                
            else if (600<=dist && dist<1500)
            {distance = 500
                meritkoText = "0,5 Km"
                var imagaPx = Math.round(700*distance/dist)}
                
            else if (1500<=dist && dist<2500)
            {distance = 500
                meritkoText = "0,5 Km"
                var imagaPx = Math.round(700*distance/dist)}
            
            else if (2500<=dist && dist<4500)
            {distance = 1000
                meritkoText = "1 Km"
                var imagaPx = Math.round(700*distance/dist)}
            
            else if (4500<=dist && dist<9000)
            {distance = 2000
                meritkoText = "2 Km"
                var imagaPx = Math.round(700*distance/dist)}
        
            else if (9000<=dist && dist<18000)
            {distance = 5000
                meritkoText = "5 Km"
                var imagaPx = Math.round(700*distance/dist)}

            else 
            {distance = 10000
                meritkoText = "10 Km"    
            
                var imagaPx = Math.round(700*distance/dist)}
            
            var outputDistanceElement = document.getElementById('image');
            var outputDistance = "<p><img src='"+ meritkoImage+"' alt='Smiley face' height='20' width='"+ imagaPx+ "' />" + meritkoText +"</p>";
            outputDistanceElement.innerHTML = outputDistance;
           
        }

        meritko()
        map.events.register('zoomend', this, function (event) {
            meritko()
            
        });
    
    
        /*       //dist vraci vzdalenost mezi rohy mapy potreba dat do funkce po preklesleni             
        var element = document.getElementById('output');
                
        var p1px = new OpenLayers.Pixel(0,0);
        var p1 = map.getLonLatFromViewPortPx(p1px)
                
        var p2px = new OpenLayers.Pixel(700,0);
        var p2 = map.getLonLatFromViewPortPx(p2px)
                
        var p1p = new OpenLayers.Geometry.Point(p1.lon,p1.lat);
        var p2p = new OpenLayers.Geometry.Point(p2.lon,p2.lat);
                
        var proj = new OpenLayers.Projection("EPSG:900913");
        var line = new OpenLayers.Geometry.LineString([p1p, p2p]); 
        var dist = line.getGeodesicLength(proj);
                
        out = "<p>" + dist + "</p>";
        element.innerHTML = out;
         */                
                
        //   setTimeout("window.print()",2000);
    }
        </script>
    </head>
    <body onload="init()">

        <h1 id="print_map_name"><?php echo($spec->outputFilename); ?></h1>
        <div id="print_map" style="width: 700px;height: 500px">
            <div id="output">
            </div>
            <div id="image">
            </div>

        </div>

    </body>
</html>