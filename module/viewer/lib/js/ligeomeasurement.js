function LiGeomeasurement(ligeo){
    this.name = "measurement";
    
    this.title = function(){
        //todo taday zjistim lokalizaci 
        return "Measurement";
    }
    
    this.toString = function(){
        return "";
    }
    
    this.activate = function(){
        this.tabMenuContent = document.getElementById("tabMenuContent"+this.name).style.display="block";
    }
    
    var sketchSymbolizers = {
        "Point": {
            pointRadius: 4,
            graphicName: "square",
            fillColor: "white",
            fillOpacity: 1,
            strokeWidth: 1,
            strokeOpacity: 1,
            strokeColor: "#FF0000"
        },
        "Line": {
            strokeWidth: 3,
            strokeOpacity: 1,
            strokeColor: "#FF0000",
            strokeDashstyle: "dash"
        },
        "Polygon": {
            strokeWidth: 2,
            strokeOpacity: 1,
            strokeColor: "#FF0000",
            fillColor: "white",
            fillOpacity: 0.4
        }
    };
    var style = new OpenLayers.Style();
    style.addRules([
        new OpenLayers.Rule({
            symbolizer: sketchSymbolizers
        })
        ]);
    var styleMap = new OpenLayers.StyleMap({
        "default": style
    });
    
    this.deactivate = function(){
        if(measureControls.polygon.active)
            measureControls.polygon.deactivate();
        if(measureControls.line.active)
            measureControls.line.deactivate();
        
        document.getElementById('mapOutput').innerHTML = "";
        document.getElementById("tabMenuContent"+this.name).style.display="none";     

    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // build the measure controls
    var measureControls = {
        line: new OpenLayers.Control.Measure(
            OpenLayers.Handler.Path, {
                geodesic: true,
                persist: true,
                displayClass: "olControlMeasureDistance",
                title: "Length measurement",
                handlerOptions: {
                    layerOptions: {
                        styleMap: styleMap
                    }
                }
            }
            ),
        polygon: new OpenLayers.Control.Measure(
            OpenLayers.Handler.Polygon, {
                geodesic: true,
                persist: true,
                displayClass: "olControlMeasureArea",
                title: "Area measurement",
                handlerOptions: {
                    layerOptions: {
                        styleMap: styleMap
                    }
                }
            }
            )
    };

    for(var key in measureControls) {
        var control = measureControls[key];
        control.events.on({
            "measure": handleMeasurements,
            "measurepartial": handleMeasurements
        });
    }

    function handleMeasurements(event) {
        var geometry = event.geometry;
        var units = event.units;
        var order = event.order;
        var measure = event.measure;
        var element = document.getElementById('mapOutput');
        var out = "";
        if(order == 1) {
            out += "Length: " + measure.toFixed(3) + " " + units;
               
        } else {
            out += "<span class='mapAreaOutput'>Area: " + measure.toFixed(3) + " " + units + "<sup style='font-size:6px'>2</" + "sup></span>";
        }
        element.innerHTML = out;
    }
    
    measureControls.line.events.register("activate", measureControls.line, function() {
        ligeo.activate("measurement",true);
        measureControls.line.activate();
    });
    measureControls.polygon.events.register("activate", measureControls.polygon, function() {
        ligeo.activate("measurement",true);
        measureControls.polygon.activate();
    });
    
    measureControls.line.events.register("deactivate", measureControls.line, function() {
        //   ligeo.activate("measurement",true);
        measureControls.line.deactivate();
    });
    measureControls.polygon.events.register("deactivate", measureControls.polygon, function() {
        //    ligeo.activate("measurement",true);
        measureControls.polygon.deactivate();
    });
    
    ligeo.ligeoMap.toolbar.addControls([
        measureControls.line,
        measureControls.polygon
        ]);

}
