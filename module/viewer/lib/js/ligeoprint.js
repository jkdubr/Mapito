function LiGeoprint(ligeo){
    this.name = "print";
    var printJson = "";
    var printMapUrl = ""; //URL PDF vrácené ze srvr
    var printLayer;
    
    $("#tabMenuContent").append('<div class="tabMenuContent" id="tabMenuContentprint"><button id="printArea" onclick="ligeo.modules[\'print\'].activatePrintLayer();" style="width: 100%">Choose print extent.</button><br /><br />Map name:<input type="hidden" id="dpi_form" value="300"></input><input type="text" id="mapName" name="mapName" value="My map" size="20" /><br /><br /><form id="print_form" action="" method="POST" target="_blank" onsubmit="return ligeo.modules[\'print\'].printSelected();"><input type="hidden" name="spec" ><input type="submit" value="Print" id="buttonPrint"  style="width: 100%" /></form><br /><br /><div id="printInProgress" style="display: none"><table><tr><td align="center"><img src="../img/loading.gif" alt="loading"></img></td><td style="font-size: 20px">Print in progress.</td></tr></table></div><div id="printResult" style="display: none"><table><tr><td style="font-size: 20px;text-decoration: underline;cursor: pointer"><a onclick="ligeo.modules[\'print\'].openPrint();">Open printed map</a></td></tr></table></div></div>');
    
    this.title = function(){
        //todo taday zjistim lokalizaci 
        return "Print";
    }
    
    this.toString = function(){
        return "";
    }
    
    
    this.printSelected = function(){
        
        
        
        //document.getElementById("printResult").style.display="none";  
        
        if(printJson){
            document.getElementById("print_form").spec.value=printJson;
            document.getElementById("print_form").action=printURL;
           
        }else{
            alert("Choose print extent");
            return false;
        }
        this.deactivatePrintLayer();
        
        return true;

    }
    
    this.openPrint = function(){
        if(printMapUrl)
            document.location=printMapUrl;
        document.getElementById("printResult").style.display="none";  
        
    }
    
    this.activatePrintLayer = function(){
        document.getElementById("mapName").disabled = false;
        box.activate();
    }
    
    this.deactivatePrintLayer = function(){
        printJson="";
        printLayer.removeAllFeatures();
        box.deactivate();
    }


    this.activate = function(){
        this.tabMenuContent = document.getElementById("tabMenuContent"+this.name).style.display="block";
        printLayer.setVisibility(true);  
        document.getElementById("mapName").disabled = false;
        this.activatePrintLayer();
    }
    
    this.deactivate = function(){
        this.tabMenuContent = document.getElementById("tabMenuContent"+this.name).style.display="none";
        printLayer.setVisibility(false);      
        this.deactivatePrintLayer();
    }
    
    this.nazev = ligeo.page 



      

    printLayer = new OpenLayers.Layer.Vector("Polygon Layer",{
        displayInLayerSwitcher:false
    });
    
    
    ligeo.ligeoMap.map.addLayers([printLayer]);
    printLayer.setZIndex(900);

    var control = new OpenLayers.Control();
    OpenLayers.Util.extend(control, {
        draw: function () {
            // this Handler.Box will intercept the shift-mousedown
            // before Control.MouseDefault gets to see it
            box = new OpenLayers.Handler.Box( control,
            {
                "done": this.notice
            }
            );
                        
        },

        notice: function (bounds) {
            
            if(printLayer.features.length > 0) {
                printLayer.removeFeatures(printLayer.features[0])
            }
            var ll = ligeo.ligeoMap.map.getLonLatFromPixel(new OpenLayers.Pixel(bounds.left, bounds.bottom)); 
            var ur = ligeo.ligeoMap.map.getLonLatFromPixel(new OpenLayers.Pixel(bounds.right, bounds.top)); 
            
            var center = ligeo.ligeoMap.map.getLonLatFromPixel(new OpenLayers.Pixel((bounds.left + ((bounds.right-bounds.left)/2)), (bounds.top + ((bounds.bottom-bounds.top)/2)))); 

            
            
            var point = new OpenLayers.LonLat(center.lon, center.lat);
            //this.map.setCenter(point, ligeo.ligeoMap.zoom);
            
            
            var pA = new Array();
            pA[0] = new OpenLayers.Geometry.Point( ll.lon.toFixed(4), ll.lat.toFixed(4) );
            pA[1] = new OpenLayers.Geometry.Point( ll.lon.toFixed(4), ur.lat.toFixed(4) );
            pA[2] = new OpenLayers.Geometry.Point( ur.lon.toFixed(4), ur.lat.toFixed(4) );
            pA[3] = new OpenLayers.Geometry.Point( ur.lon.toFixed(4), ll.lat.toFixed(4) );
          
            var pointList = [];
            for(var p=0; p<4; p++) {
                pointList.push(pA[p])
            };

            var linearRing = new OpenLayers.Geometry.LinearRing(pointList);
            var newPolygon = new OpenLayers.Geometry.Polygon(linearRing);
            var selectBoxFeature = new OpenLayers.Feature.Vector(
                newPolygon);

            // add new polygon box feature to the box layer
            printLayer.addFeatures([selectBoxFeature]);  
           
            
            //nastavení tisku
            var dpi = document.getElementById('dpi_form').value


            var printable = new Array()
            var printableZix = new Array();
            
            var activeLayer = ligeo.ligeoMap.getLayersActive();
            for(var i = 0; i < activeLayer.length; i++){
                if (ligeo.ligeoMap.planLayers[ligeo.ligeoMap.planLayersName.indexOf(activeLayer[i])].metadata.printable=="0")
                {
                    notprint=""
                }
                else
                {
                    var zIx = ligeo.ligeoMap.planLayers[ligeo.ligeoMap.planLayersName.indexOf(activeLayer[i])].getZIndex();
                    printable[zIx]=activeLayer[i];
                    printableZix.push(zIx);
                }
            };
            
            printableZix.sort();
            
            var layersToPrint = new Array()
            for(var i = 0; i < printableZix.length; i++)
            {
  
                layers = 
                "{ \"type\": \""+ligeo.ligeoMap.planLayers[ligeo.ligeoMap.planLayersName.indexOf(printable[printableZix[i]])].metadata.type+"\","
                +"\"layers\": [\""+printable[printableZix[i]]+"\"],"
                +"\"baseURL\": \""+wmsURL+"\","
                +"\"opacity\":"+ ligeo.ligeoMap.planLayers[ligeo.ligeoMap.planLayersName.indexOf(printable[printableZix[i]])].opacity  +","
                +"\"singleTile\":\"true\","
                +"\"customParams\":{"
                +"\"ENV\":\"\","
                +"\"TRANSPARENT\":\"true\""
                +"},"
                +"\"format\": \"image/png\""
                +"}";
                layersToPrint.push(layers);
              
            }
            
            
            
            printJson = "{"
            +"\"layout\": \"Legal\","
            +"\"srs\": \"EPSG:900913\","
            +"\"units\": \"meters\","
            +"\"geodetic\": \"true\","
            +"\"outputFilename\": \""+replaceDiak(document.getElementById("mapName").value)+"\","
            +"\"outputFormat\": \"pdf\","
            +"\"layers\":["+layersToPrint+"],"
            +"\"pages\": ["
            +"{"
            +"\"center\":["+center.lon+","+center.lat+"],"
            +"\"zoom\":\""+ligeo.ligeoMap.map.zoom+"\","
            +"\"bbox\": [  "+ll.lon.toFixed(4)+", "+ll.lat.toFixed(4)+","+ ur.lon.toFixed(4)+","+ur.lat.toFixed(4)+"],"
            +"\"srs\": \"EPSG:900913\","
            +"\"dpi\": "+dpi+","
            +"\"geodetic\": \"true\","
            +"\"mapTitle\": \""+replaceDiak(document.getElementById("mapName").value)+"\","
            +"\"comment\": \"\""
            +"}"
            +"]"
            +"}";
        
        
            document.getElementById("mapName").disabled = true;
            box.deactivate();
        }
    });
    ligeo.ligeoMap.map.addControl(control);
}
