var bLgsat, bLgclasic, bLblank, bLwhite

function LiGeobaseLayers(ligeo,baseLayersNames){
    
   
    bLgsat = new OpenLayers.Layer.Google(
        "Google Satellite",
        {
            type: G_SATELLITE_MAP,
            'sphericalMercator': true,
            visibility: false,
            format: "image/png",
            opacity: 1,
            isBaseLayer: true,
            numZoomLevels: 21
        })

    bLgclasic = new OpenLayers.Layer.Google(
        "Google Clasic",
        {
            type: G_NORMAL_MAP,
            'sphericalMercator': true,
            visibility: false,
            format: "image/png",
            opacity: 1,
            isBaseLayer: true,
            numZoomLevels: 21
        })
        
    
    bLwhite = new OpenLayers.Layer.Image(
        "None",
        '../img/white.png',
        //ligeo.ligeoMap.map.maxExtent,
        new OpenLayers.Bounds(-180,-90,180,90),
        new OpenLayers.Size(10, 10),
        {
            isBaseLayer: true,
            visibility: false
        }
        );
   
   
   
   
    
    var visibleBaseLayer = "";
    var baseLayerName = [];  
    var defaultLayer = baseLayersNames[0];
  
  
    for(i=0; i<baseLayersNames.length; i++){
        baseLayerName.push(eval(baseLayersNames[i]))
    }
   
   
    var baseLayerChooser = document.getElementById("baseLayers");
  
  
    for(i=0; i<baseLayersNames.length; i++)    {
        baseLayerChooser.innerHTML += "<input type='radio' name='base' value=" + baseLayersNames[i] + " id=" + baseLayersNames[i] + " onchange=\"ligeo.baselayers.changeBaseLayer(this.value)\"><label for="+ baseLayersNames[i] +">" +   baseLayerName[i].name + "</label></input></br>"
    }
    
    this.changeBaseLayer = function(layer){
        eval(visibleBaseLayer+".setVisibility(false)");
        visibleBaseLayer = layer
        eval(layer+".setVisibility(true)");
    }
  
  
  
    ligeo.ligeoMap.map.addLayers(baseLayerName);
    
    baseLayerName[baseLayersNames.indexOf(defaultLayer)].setVisibility(true)
    var visibleBaseLayer = defaultLayer
    document.getElementById(defaultLayer).checked = true
    
        
    
   
   
   
   
   
   
}