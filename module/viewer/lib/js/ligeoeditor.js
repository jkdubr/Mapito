var wfsu = "../proxyWFS.php";    //vojta
var map, wfs, selectControl;                                //vojta

function LiGeoeditor(ligeo){
    var ligeo = ligeo;
    this.name = "editor";
    this.panel;
    that = this;    
    
    $("#tabMenuContent").append('<div class="tabMenuContent" id="tabMenuContenteditor"><form onsubmit="return ligeo.modules[\'editor\'].addLayer(document.getElementById(\'tabMenuContenteditorTitle\').value,document.getElementById(\'tabMenuContenteditorType\').value);"><input id="tabMenuContenteditorTitle" type="text" placeholder="Layer name" name="title" /><select name="type" id="tabMenuContenteditorType"><option value="POINT">Point</option><option value="MULTIPOLYGON">Polygon</option><option value="LINESTRING">Line</option></select><input type="submit" value="Save" /></form><div id="editingPanel" class="olControlEditingToolbar"></div><fieldset><select onchange="ligeo.modules[\'editor\'].editLayer(this.value);" id="tabMenuContenteditorSelect"><option value="0">== Layers ==</option></select></fieldset></div>');
    
    
    this.title = function(){
        //todo taday zjistim lokalizaci 
        return "Editor";
    }
    
    this.toString = function(){
        return "";
    }
    
    this.activate = function(){
        if(this.panel)
            this.panel.activate();
        document.getElementById("tabMenuContent"+this.name).style.display="block";
        if(! ligeo.userHash ){
            //alert(ligeo.userName+ligeo.userHash);
            document.getElementById("tabMenuContent"+this.name).innerHTML = "This section is only visible logged users.";
        }
        
        var selectbox = document.getElementById("tabMenuContenteditorSelect");
        
        for(var j = 0;j<100; j++){
            
            selectbox.remove(0);
        }
        
        selectbox.add(new Option("== Layers ==", "0"), null); 
        
        for(var i =0;i<ligeo.ligeoMap.planLayersName.length;i++){
            if(ligeo.ligeoMap.planLayers[i].metadata.isLockedForGeometry == "0"){
                
                var name = ligeo.ligeoMap.planLayersName[i].replace(ligeo.namespace+":","");
                var title = ligeo.ligeoMap.planLayers[i].name;
                var type = ligeo.ligeoMap.planLayers[i].metadata.type;
                
                selectbox.add(new Option(title, name), null); 
            }
        }
    }
    
    this.deactivate = function(){
        //console.log($("olControlEditingToolbar").html());
        that.restartEditSelectbox();
        //console.log($("#editingPanel").html());
        if(that.panel)
        {
            that.panel.deactivate();
        //console.log(that.panel);
        }
        
        //alert("aaa"+this.)
        this.tabMenuContent = document.getElementById("tabMenuContent"+this.name).style.display="none"; 

    //$("olControlEditingToolbar").empty();
    
        
    //console.log(ligeo.ligeoMap.planLayers[ligeo.ligeoMap.planLayersName.indexOf("wfs-edit")])
    }

    $("#tabMenuContenteditorSelect").change(function() { 
        if ($("#tabMenuContenteditorSelect").val()=="0")
        {
            $("#editingPanel").hide();
        }
        else{
            $("#editingPanel").show();
        }
    });

    
    this.restartEditSelectbox = function(){
        //console.log(wfs)
        if(wfs){
            wfs.destroyFeatures()
        // wfs.destroy();
        }
        $("#tabMenuContenteditorSelect").val(0);
        $("#editingPanel").hide();

            
    }
    
    this.addLayer = function(tname, ttype){ 
        if(!tname || ! ttype){
            alert("Insert name");
            return;
        }
        
        var json = '{"request":"layer.add","param":{"planId":"'+ligeo.planId+'","layerName":"'+tname+'","folderId":"", "layerType":"'+ttype+'"}}';
        $.post(apiURL, {
            "json": json
        },
        function(data){
            if (data.err) {
                alert("Error");
            } else {
                alert("Layer added");
                window.location.reload();
            }
            return false;
        }, "json");
        
        return false;
    }
    
    
    this.editLayer = function(wfsLayer){
      
        for(var i =0;i<ligeo.ligeoMap.planLayersName.length;i++){
            if(ligeo.ligeoMap.planLayersName[i] ==  ligeo.namespace +":"+wfsLayer){
                wfsLayerType = ligeo.ligeoMap.planLayers[i].metadata.type;
            }   
        }
           
        var saveStrategy = new OpenLayers.Strategy.Save();
        saveStrategy.events.register('success', null, saveSuccess);
        saveStrategy.events.register('fail', null, saveFail);
        
        function saveSuccess(event) {
            alert('Changes saved')
            //wfs.removeAllFeatures();
            that.restartEditSelectbox()
        }
        function saveFail(event) {
            alert('Error! Changes not saved');
            //wfs.removeAllFeatures();
            that.restartEditSelectbox()
            
        } 
        if(wfs)
            wfs.destroyFeatures()

           //wfs-t editable overlay
        /*    wfs = new OpenLayers.Layer.Vector("WFS", {
                   strategies: [new OpenLayers.Strategy.BBOX(), saveStrategy], //casem pouzit new OpenLayers.Strategy.Fixed()
                   projection: new OpenLayers.Projection("EPSG:4326"),
            styleMap: new OpenLayers.StyleMap({
                pointRadius: 10,
                fillColor: "red",
                fillOpacity: 0.7,
                strokeColor: "black"
            }),
                   protocol: new OpenLayers.Protocol.WFS({
                           url: wfsu,
                           featureType: wfsLayer,
                           featureNS :  "http://www.mapito.org/ligeo_" + ligeo.page,
                           srsName: "EPSG:4326",
                           geometryName: "the_geom",
                           version: "1.1.0",
                            outputFormat:"GML2",
                           extractAttributes: false
                       }),
                   visibility:true
           });
        */
       
       
        protocol = new OpenLayers.Protocol.WFS({
            url: wfsu,
                       featureType: wfsLayer,
                       featureNS :  "http://www.mapito.org/ligeo_" + ligeo.page,
                       srsName: "EPSG:4326",
                       geometryName: "the_geom",
                       version: "1.0.0",
//            outputFormat:"GML2",
                       extractAttributes: false
        });

        wfs = new OpenLayers.Layer.Vector("WFS", {
                   strategies: [new OpenLayers.Strategy.BBOX(), saveStrategy], //casem pouzit new OpenLayers.Strategy.Fixed()
            protocol: protocol,
                   projection: new OpenLayers.Projection("EPSG:4326"),
            styleMap: new OpenLayers.StyleMap({
                pointRadius: 10,
                fillColor: "red",
                fillOpacity: 0.7,
                strokeColor: "black"
            }),
             visibility:true
        });

        var _Callback = function(resp) {
            console.log(resp);
            //console.log(resp.error);

            try {
                var gmlOptions = {
                    featureType: "feature",
                    featureNS: "http://www.mapito.org/ligeo_" + ligeo.page
                };
                var gmlOptionsIn = OpenLayers.Util.extend(
                    OpenLayers.Util.extend({}, gmlOptions)
                    );

                var gmlParser = new OpenLayers.Format.GML(gmlOptionsIn);
                gmlParser.extractAttributes = true;
                //var features = gmlParser.read(resp.features);
                var features = gmlParser.read(resp.priv.responseText);
                console.log(resp.priv.responseText);
                console.log(wfs);
            //wfs.addFeatures(resp.features);
            
                
                
            } catch(e) {
                alert("Error: " + e);
            }
        };

        response = protocol.read({
            maxFeatures: 100,
            callback: _Callback
        });
        
        ligeo.ligeoMap.map.addLayer(wfs);
        wfs.refresh()
   
   
        var highlightCtrl = new OpenLayers.Control.SelectFeature(wfs, {
            hover: true,
            highlightOnly: true,
            renderIntent: "temporary"
        });
        
        var selectCtrl = new OpenLayers.Control.SelectFeature(wfs,
        {
            clickout: true
        }
        );
        
        ligeo.ligeoMap.map.addControl(highlightCtrl);
        ligeo.ligeoMap.map.addControl(selectCtrl);
        
        highlightCtrl.activate();
        selectCtrl.activate();
        
        /* END HIGHLIGHT */
        
        /* SNAPPING */
        // ConfiguraciÃ³n del Snapping
        var snap = new OpenLayers.Control.Snapping({
            layer: wfs
        });
        ligeo.ligeoMap.map.addControl(snap);
        snap.activate();
        
        // ConfiguraciÃ³n del Split
        var split = new OpenLayers.Control.Split({
            layer: wfs,
            source: wfs,
            tolerance: 1000,
            deferDelete: true,
            eventListeners: {
                aftersplit: function(event) {
                    var msg = "Split resulted in " + event.features.length + " features.";
                    flashFeatures(event.features);
                }
            }
        });
        
        ligeo.ligeoMap.map.addControl(split);
        split.activate();
        
        /* PANEL */
        // crear un panel y aÃ±adirle iconos con funcionalidad
        
        var container = document.getElementById("editingPanel");
        this.panel = new OpenLayers.Control.Panel(
        {
            'displayClass': 'customEditingToolbar',
            div: container
        }
        );
        
        
        var type;
        var styleEdit;
        if(wfsLayerType == "POINT" || wfsLayerType == "MULTIPOINT"){
            type = OpenLayers.Handler.Point;
            styleEdit = "olControlDrawFeaturePoint";
        }else if(wfsLayerType=="LINESTRING" || wfsLayerType=="MULTILINESTRING"){
            type = OpenLayers.Handler.Path;
            styleEdit = "olControlDrawFeaturePath";
        }else{
            type = OpenLayers.Handler.Polygon;
            styleEdit = "olControlDrawFeaturePolygon";
        }
        // Control para digitalizar puntos (draw)
        var draw = new OpenLayers.Control.DrawFeature(
            wfs, type,
            {
                title: "Draw Feature",
                displayClass: styleEdit,
                handlerOptions: {
                    freehand: false, 
                    multi: true
                },
                div:container
            
            }
            );
        
       
        // Control para modificar elementos. En el caso de puntos sÃ³lo se pueden desplazar
        // a otra posiciÃ³n
        var modify = new OpenLayers.Control.ModifyFeature(
            wfs, {
                displayClass: "olControlModifyFeature", 
                title: "Modify Feature",
                div:container
            }
            );
        
        // Control para borrar elementos
        var del = new DeleteFeature(wfs, {
            title: "Delete Feature",
            div:container
        });
        
        // Control para salvar la sesiÃ³n de digitalizaciÃ³n
        var save = new OpenLayers.Control.Button({
            title: "Save Changes",
            trigger: function() {
                if(modify.feature) {
                    modify.selectControl.unselectAll();
                }
                saveStrategy.save();
            },
            displayClass: "olControlSaveFeatures",
            div:container
        });
         
        this.panel.addControls([  new OpenLayers.Control.Navigation({
            div:container
        }),save,del,modify,draw]);
        
        // Definimos el control de navegaciÃ³n como el activo por defecto
        this.panel.defaultControl = this.panel.controls[0];
        ligeo.ligeoMap.map.addControl(this.panel);
        
    }

}

//set up the modification tools
var DeleteFeature = OpenLayers.Class(OpenLayers.Control, {
    initialize: function(layer, options) {
        OpenLayers.Control.prototype.initialize.apply(this, [options]);
        this.layer = layer;
        this.handler = new OpenLayers.Handler.Feature(
            this, layer, {
                click: this.clickFeature
            }
            );
    },
    clickFeature: function(feature) {
        // if feature doesn't have a fid, destroy it
        if(feature.fid == undefined) {
            this.layer.destroyFeatures([feature]);
        } else {
            feature.state = OpenLayers.State.DELETE;
            this.layer.events.triggerEvent("afterfeaturemodified", 
            {
                feature: feature
            });
            feature.renderIntent = "select";
            this.layer.drawFeature(feature);
        }
    },
    setMap: function(map) {
        this.handler.setMap(map);
        OpenLayers.Control.prototype.setMap.apply(this, arguments);
    },
    CLASS_NAME: "OpenLayers.Control.DeleteFeature"
});
