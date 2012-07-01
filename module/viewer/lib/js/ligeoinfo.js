/*
 *všechny vrstvy v quearyLayers musí být ze stejného namespace (workspace)
 *
*/
function LiGeoinfo(ligeo){
    var that = this;
    this.name = "info";
    var knLayer;
    this.title = function(){
        //todo taday zjistim lokalizaci 
        return "Info";
    }
    
    this.toString = function(){
        return "";
    }
    
    this.activate = function(){
        document.getElementById("tabMenuContentinfo").style.display="block";
        //info.activate();  
        if(document.getElementById("hromadnyVypisKn"))
            this.showKn(document.getElementById("hromadnyVypisKn").checked);
    }


    this.deactivate = function(){
        document.getElementById("tabMenuContent"+this.name).style.display="none";
     
        info.deactivate();
        that.showKn(false);
        Kn.deactivate();
        featureInfo.deactivate();  
    }






    this.showKn = function(show){
        if(document.getElementById("knHromadne"))
            document.getElementById("knHromadne").innerHTML = "";
        knRowCount=1;
        if(show){
           
            
        }else if(knLayer){
            knLayer.removeAllFeatures();
            
        }
    }



    //kn//////////////////////////////////////////////////////
    knLayer = new OpenLayers.Layer.Vector("kn Layer",{
        displayInLayerSwitcher:false,
        styleMap: new OpenLayers.StyleMap({
            "default": {
                pointRadius: 20, 
                fillColor: "#FFE4C4", 
                fillOpacity: 0.7, 
                strokeColor: "black",
                label:"${name}",
                fontColor: "black",
                fontSize: "20px",
                fontFamily: "Arial",
                fontWeight: "bold",
                cursor:"pointer"
            },
            "select": {
                pointRadius: 20, 
                fillColor: "#DEB887", 
                fillOpacity: 0.7, 
                strokeColor: "black",
                label:"${name}",
                fontColor: "black",
                fontSize: "20px",
                fontFamily: "Arial",
                fontWeight: "bold",
                cursor:"pointer"
            }
        })
    }
    );
    ligeo.ligeoMap.map.addLayers([knLayer]);
    knLayer.setZIndex(999);
            
            
    // Interaction; not needed for initial display.
    var selectControl = new OpenLayers.Control.SelectFeature(knLayer);
    ligeo.ligeoMap.map.addControl(selectControl);
    selectControl.activate();
    knLayer.events.on({
        'featureselected': onFeatureSelect,
        'featureunselected': onFeatureUnselect,
        "beforefeatureadded" : onBeforefeatureadded
    });









    //informace
   
    
    
    
    
    var knFeatureSelected = false;//kliknul jsem zrovna na bod? -> nechci vkladat novy
    var knRowCount=1;//pocet bboduu
    /**
     *
     */
    


             
 
    // Needed only for interaction, not for the display.
    function onPopupClose(evt) {
    /*
        // 'this' is the popup.
        var feature = this.feature;
        if (feature.layer) { // The feature is not destroyed
            selectControl.unselect(feature);
        } else { // After "moveend" or "refresh" events on POIs layer all 
            //     features have been destroyed by the Strategy.BBOX
            this.destroy();
        }
         */
    }
    function onFeatureSelect(evt) {
        knFeatureSelected = true;
        window.open(document.getElementById("nahlizenidokn"+evt.feature['id']).href,"_blank");
    }
    
    function onFeatureUnselect(evt) {
        knFeatureSelected = false;
    //alert('unselected');
    /*feature = evt.feature;
        if (feature.popup) {
            popup.feature = null;
            ligeo.ligeoMap.map.removePopup(feature.popup);
            feature.popup.destroy();
            feature.popup = null;
        }*/
    }
    function  onBeforefeatureadded(evt) {
        /*
         *      feature = evt.feature;
        popup = new OpenLayers.Popup.FramedCloud("featurePopup",
            feature.geometry.getBounds().getCenterLonLat(),
            new OpenLayers.Size(100,100),
            "aaaa",
            null, true, onPopupClose);
        feature.popup = popup;
        popup.feature = feature;
        ligeo.ligeoMap.map.addPopup(popup, true);
         */
        if(knFeatureSelected)
            return false;

        var lonlat = ligeo.ligeoMap.map.getLonLatFromViewPortPx(evt.xy); //odečtení souřadnic z obrazovky a převadoní do 900913
        var lat = evt.feature['geometry'].y;               //mercator 900913
        var lon = evt.feature['geometry'].x;               //mercator 900913
                    
        var knLonLat = new OpenLayers.LonLat(lon,lat);      //defince bodu, potřeba pro transformaci
        var knWgs = knLonLat.transform(new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326"));       //transformace z 900913 do 4326
        var knJtsk = ligeo.ligeoMap.convertCoord.radToJTSK(ligeo.ligeoMap.convertCoord.deg2rad(knWgs.lat),ligeo.ligeoMap.convertCoord.deg2rad(knWgs.lon));       //transformace z 4326 do 102067(jtsk)
        var jtsky = Math.round(knJtsk.x);       //zaokrouhlení
        var jtskx = Math.round(knJtsk.y);       //zaokrouhlení     
        var knurl = "http://nahlizenidokn.cuzk.cz/MapaIdentifikace.aspx?&x=-"+jtskx+"&y=-"+jtsky;

        //pomucka pro odecet souradnic
        //alert (lon+" "+lat)
        
        document.getElementById("knHromadne").innerHTML += "<a href="+knurl+" target='_blank' id='nahlizenidokn"+evt.feature['id']+"'>("+knRowCount+") Odkaz na katastr nemovitostí</a><br />";
        
        knRowCount++;
        return true;
    }
    
    /**
     *
     */



    OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {
        initialize: function() {
            OpenLayers.Control.prototype.initialize.apply(
                this, arguments
                );
            this.handler = new OpenLayers.Handler.Click(
                this, {
                    'click': this.trigger
                });
        },
        trigger: function kn(e) {

            lonlat = ligeo.ligeoMap.map.getLonLatFromViewPortPx(e.xy); //odečtení souřadnic z obrazovky a převadoní do 900913
            lat = lonlat.lat;               //mercator 900913
            lon = lonlat.lon;               //mercator 900913

            if (document.getElementById('hromadnyVypisKn').checked)
            {               
                var knPoint1 = new OpenLayers.Geometry.Point(lon,lat);
                var knPoints = new OpenLayers.Feature.Vector(knPoint1);
               
                knPoints.attributes = {
                    name: knRowCount 
                };


                knLayer.addFeatures([knPoints]);
            }
            else{
                knLonLat = new OpenLayers.LonLat(lon,lat);      //defince bodu, potřeba pro transformaci
                knWgs = knLonLat.transform(new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326"));       //transformace z 900913 do 4326
                var knJtsk = ligeo.ligeoMap.convertCoord.radToJTSK(ligeo.ligeoMap.convertCoord.deg2rad(knWgs.lat),ligeo.ligeoMap.convertCoord.deg2rad(knWgs.lon));       //transformace z 4326 do 102067(jtsk)
                jtsky = Math.round(knJtsk.x);       //zaokrouhlení
                jtskx = Math.round(knJtsk.y);       //zaokrouhlení     
                var knurl = "http://nahlizenidokn.cuzk.cz/MapaIdentifikace.aspx?&x=-"+jtskx+"&y=-"+jtsky;

                window.open(knurl,"_blank");
            }
        }
    }
    );
      
    //kn////////////////////////////////////////////////















    var ie7 = (typeof document.addEventListener != 'function' && window.XMLHttpRequest) ? true : false;




    var info = new OpenLayers.Control.WMSGetFeatureInfo({
        infoFormat: "text/html",
        url: gfiURL,
        queryVisible: true,
        layerUrls: [wmsURL],
        title: 'Click',
        layers: ligeo.ligeoMap.queryableLayers,
        eventListeners: {
            getfeatureinfo: function(event) {
                alert("aaaaaaa")
                ligeo.activate("info",true);
                
                if (ie7==true) {
                    document.getElementById("gfiContent").innerHTML = event.text
                } else {
                    document.getElementById("gfiContent").innerHTML = event.text.replace(/<style.*>/,"<!--<style>").replace(/<\/style>/,"</style>-->");
                }
                
                ////////////////////////////////////////////////////////////////////////////////////////         uprava tabulky      /////////////////////////
                var layers = ligeo.ligeoMap.queryableLayers
                //identifikace požadované bunky
                for (i = 0; i < layers.length; i++) {
                    a = document.getElementById("gfiContent")
                    b = a.getElementsByTagName("caption");
 
                    if (b[i] == null)
                    {
                        break;
                    }
                    else {
                        var layerName=b[i].innerHTML;
                        var gid = 0;
                        
                        
                        ////////////////////////////////////////foto///////////////////////////////
                        if (true)
                        {
                            c = a.getElementsByTagName("tbody");
                            d = c[i]; 
                            e = d.getElementsByTagName("tr");
                            f = e[1];
                            g = f.getElementsByTagName("td");
                            h = e[0];
                            j = h.getElementsByTagName("th");
                            
                            for(var x=0; x<j.length;x++){
                                if(validate_url(g[x].innerHTML)){
                                    if(validate_file_extension(g[x].innerHTML)=="img"){
                                        g[x].innerHTML = "<a href='"+g[x].innerHTML+"' rel='prettyPhoto' title='"+g[x].innerHTML+"'><img src='"+g[x].innerHTML+"' width='60' height='60'></a>";
                                    }else{
                                        g[x].innerHTML = "<a href='"+g[x].innerHTML+"'  target='_blank' title='"+g[x].innerHTML+"'><img src='../img/ico_ext_"+validate_file_extension(g[x].innerHTML)+".png'> odkaz</a>";
                                    }
                                    
                                }
                                
                                if(j[x].innerHTML=="fid"){
                                    gid = g[x].innerHTML.replace(layerName+"\.","");
                                    j[x].innerHTML="";
                                    g[x].innerHTML="";
                                }
                            /*
                                if(j[x].innerHTML=="ligeoPicture"){
                                    j[x].innerHTML = "Foto"; 
                                    g[x].innerHTML = "<a href='data:image/jpg;base64,"+g[x].innerHTML+"' rel='prettyPhoto'><img src='data:image/jpg;base64,"+g[x].innerHTML+"' width='60' height='60'></a>";
                                }else if(j[x].innerHTML=="ligeoUrl" && g[x].innerHTML){
                                    j[x].innerHTML = "Odkaz";
                                    g[x].innerHTML = "<a href='"+g[x].innerHTML+"' target='_blank'>"+g[x].innerHTML+"</a>";
                                }
                                */
                                
                            }
                        
                            $(document).ready(function(){
                                $("a[rel^='prettyPhoto']").prettyPhoto({
                                    social_tools:false
                                });
                            })
                            
                        }
                    ////////////////////////////////konec foto/////////////////////////////////////
                    }
                    
                    if(ligeo.ligeoMap.planLayers[ligeo.ligeoMap.planLayersName.indexOf(ligeo.namespace +":"+b[i].innerHTML)].metadata.type != "RASTR"){
                        b[i].innerHTML="<img src='../img/style/" + ligeo.ligeoMap.planLayers[ligeo.ligeoMap.planLayersName.indexOf(ligeo.namespace +":"+b[i].innerHTML)].metadata.layerStyleId +".png' alt='legenda'> "+ligeo.ligeoMap.planLayers[ligeo.ligeoMap.planLayersName.indexOf(ligeo.namespace +":"+b[i].innerHTML)].name
                        if(ligeo.userHash && ligeo.privilege > 1){
                            b[i].innerHTML += " (<a target='_blank' href='http://admin.mapy.mostar.cz/page/pg_edit?planId="+ligeo.planId+"&dbtab="+layerName+"&pgItem="+gid+"&userHash="+ligeo.userHash+"#pgItem_"+gid+"'>edit</a>)" 
                        }    
                    }    
                }
            ////////////////////////////////////     konec foto     ///////////////////////
            }
        }
    });
    
    
    ligeo.ligeoMap.map.addControl(info);
    // build the getFeatureInfo
    var featureInfo = new OpenLayers.Control({
        displayClass: "olControlFeatureInfo",
        title: "Kliknutí zjistíte informace o místě"
    });
    
    featureInfo.events.register("activate", featureInfo, function() {
        that.showKn(false);
        ligeo.activate("info",true);
        info.activate();
        document.getElementById("tabMenuContentinfo").innerHTML = "Pro dotazování ve viditelné vrstvě klikněte na mapu.<br><br><div id='gfiContent' />";
    });
    featureInfo.events.register("deactivate", featureInfo, function() {
        info.deactivate();
        
    });  
    
    
    
    
    
    var Kn = new OpenLayers.Control.Click({
        displayClass: "olControlKn"
    });
    Kn.events.register("activate", Kn, function() {
        ligeo.activate("info",true);
        
        document.getElementById("tabMenuContentinfo").innerHTML="<form><input type='checkbox' id='hromadnyVypisKn' name='Hromadne_dotazovani' onClick='ligeo.modules[\"info\"].showKn(this.checked);'>Hromadný výpis?</form><div id='knHromadne'/>";            
    });
    Kn.events.register("deactivate", Kn, function() {

        });
   

    //alert(ligeo.ligeoMap.map.controls[3]);
    ligeo.ligeoMap.toolbar.addControls([featureInfo,Kn]);
}