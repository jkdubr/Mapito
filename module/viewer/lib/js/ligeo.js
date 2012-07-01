
////stav modulu: active,sleep,init,
//HTML mapa bude pouze pregenerovana kdyz uzivatel v admin nastaveni zmeni nastaveni - vrstvy, moduly...
//prihlaseny uzivatel v JS bude udrzovat prihlasovaci udaje, automaticky po prihlaseni mu budou zobrazeny dalsi data a moznost editace vrstev (JSON komunikace)

/*
 *prihlaseni 
 * 1/ odeslu POST na srvr, zeptam se, zda jsou spravne prihlasovaci udaje
 * 2/ pokud TRUE - uloz hash (login + koreni + heslo) do cookies A reload web
 * 3/ pri nacitani webu v init overim zda je uvivatel prihlasen (ulozen cookies? -> odeslu na srvr zda hash je spravny)
 * 
*/
//document.write('<script type="text/javascript" src="lib/js/menu.js"></script>');

var ligeo = null;

var apiURL = "../proxyAPI.php";
var wmsURL;//ziskane z settings.php, 
var gfiURL = "../proxyGFI.php?"
var printURL ; //ziskane z settings.php, print.pdf docasne upraveno, popřípadě změnit,pro potřeby tisku
var planLayers;
var queryableLayers
var adminURL;//ziskane z settings.php, 



function ligeoInit(){
    ligeo = new LiGeo();

    
}

function LiGeo(){
    this.modulesTxt=new Array();
    this.userHash="";
    this.userName = "";
    
    this.page= location.pathname.slice(location.pathname.lastIndexOf("/",location.pathname.lastIndexOf("/")-1)+1,location.pathname.lastIndexOf("/"));
    this.namespace = "ligeo_"+this.page;
    this.baselayerNames = [];
    var that = this;
    
    
 
    /*
    jQuery.ajaxSetup({
        async:false
    });
    $.get("../settings.php",
        function(data){
            wmsURL = data.proxy_url+"/proxyWMS.php?";
            printURL = data.proxy_url+"/print.php";
            this.modulesTxt = data.viewer_modules;
    alert("aaaweeew"+this.modulesTxt.length);
        }, "json");
    */
    var modulesTxt;
    $.ajax({
        type: 'GET',
        url: '../settings.php',
        dataType: 'json',
        success: function(data) {
            wmsURL = data.proxy_url+"/proxyWMS.php?";
            printURL = data.proxy_url+"/print.php";
            modulesTxt = data.viewer_modules;
            adminURL = data.admin_url;
            that.baselayerNames = data.base_layers;
            
        },
        data: {},
        async: false
    });
    this.modulesTxt = modulesTxt;
    modulesTxt="";
    
    var json1 = '{"request":"user.hash","userHash":"'+getCookie("userHash")+'"}';

    var layersJson1 = apiURL+"?json="+json1;
    var res1 = OpenLayers.Request.issue({ 
        method: 'POST', 
        params: {
            json:json
        },
        url: layersJson1, 
        headers:{
            "Content-Type": 
            "application/x-www-form-urlencoded"
        }, 
        async: false
    }); 

    
    var g1 =  new OpenLayers.Format.JSON(); 
    var data1 = g1.read(res1.responseText); 
    if(data1.userHash){
        this.userHash=data1.userHash;
        this.userName=data1.name;
    }

    
    var json = '{"request":"plan.detail","userHash":"'+this.userHash+'","param":{"name":"'+this.page+'"}}';

    var layersJson = apiURL+"?json="+json;
    var res = OpenLayers.Request.issue({ 
        method: 'POST', 
        params: {
            json:json
        },
        url: layersJson, 
        headers:{
            "Content-Type": 
            "application/x-www-form-urlencoded"
        }, 
        async: false
    }); 

    
    var g =  new OpenLayers.Format.JSON(); 
    var data = g.read(res.responseText); 
    
    this.title=data.title;
    this.planId=data.planId;
    this.mapCenterLat=data.mapCenterLat;
    this.mapCenterLon=data.mapCenterLon;
    this.txt=data.txt;
    this.mapZoom = data.mapZoom;
    this.privilege = data.privilege;

    this.modules = new Array();

    
    
    document.title = this.title;
    
    
    
    
    
    
    
    var sketchSymbolizers, style, styleMap, measureControls;
    var printMapUrl = "";
    
    this.moduleActive = "";
    
    /////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////
    
    
    //this.modulesTxt = new Array("layers","info","legend","print","measurement","editor","biotopy","login");

    this.ligeoMap = new LiGeoMap(this);
    
    
    /**
     *load base layers
     */

    this.baselayers = new LiGeobaseLayers(this,this.baselayerNames);
    /**
     * end load base layers
     */   
    
   
    /**
     *definice funkci
     */
    this.isTabMenuContentOpen = function(){
        return document.getElementById("tabMenuContent").style.visibility == "visible";
    }
    
    this.activate = function(module,force){
        if((this.moduleActive && this.moduleActive != module)){
            this.modules[this.moduleActive].deactivate();
            document.getElementById("tabMenu"+this.moduleActive).className="";
        }
        if(!force){
            if(this.moduleActive == module && this.isTabMenuContentOpen()){
                document.getElementById("tabMenuContent").style.visibility = "hidden";
            }else if(!this.isTabMenuContentOpen()){
                document.getElementById("tabMenuContent").style.visibility = "visible";
            }
        }
        this.modules[module].activate();   
        document.getElementById("tabMenu"+module).className="active";
        this.moduleActive = module;  
    }
    /**
     *KONEC definice funkci
     */
    
    
    
       
    
    /**
     *priprava stranky
     */
    var htmlLi = "";

    for(var i =0; i<this.modulesTxt.length;i++){
        eval("this.modules['"+this.modulesTxt[i]+"'] = new LiGeo"+this.modulesTxt[i]+"(this);");
       
        htmlLi += '<li id="tabMenu'+this.modulesTxt[i]+'" onclick="ligeo.activate(\''+this.modulesTxt[i]+'\',false);" class="'+(i==0 ? 'active' : '')+'">'+this.modules[this.modulesTxt[i]].title()+'</li>';
    }
   
    document.getElementById("menuList").innerHTML = '<ul>'+htmlLi+'</ul>';
    htmlLi = "";
    
    for(var j =0; j<this.modulesTxt.length;j++){
        document.getElementById('tabMenu'+this.modulesTxt[j]).style.backgroundImage='url("../img/en_w/'+this.modulesTxt[j]+'.png")';
    }
    
    this.activate(this.modulesTxt[0]);
    
    /**
     *KONEC priprava stranky
     */
    



    this.login = function(tlogin,tpass){
        var json = '{"request":"user.login","param":{"mail":"'+tlogin+'","password":"'+tpass+'"}}';
        $.post(apiURL, {
            "json": json
        },
        function(data){
            if (data.err) {
                alert("Login error");
            } else {
                setCookie("userHash", data.userHash, 31);
                window.location.reload();
            }
        }, "json");
        return false;
    }
    
    this.logout = function(){
        var json = '{"request":"user.logout","userHash":"'+this.userHash+'"}';
        $.post(apiURL, {
            "json": json
        },
        function(data){
            this.userHash="";
            setCookie("userHash", "", 31);
            window.location.reload();
        }, "json");        
    }
    
       
    
}













function LiGeoMap(ligeo) {
    
    var that = this;
    this.toolbar = new OpenLayers.Control.Panel({
        div: document.getElementById("panel")
    });
    this.map = null;

    this.point = new OpenLayers.LonLat(ligeo.mapCenterLon, ligeo.mapCenterLat);
    
    this.zoom = ligeo.mapZoom; //stupen zoomu pri nacteni


    var options = {
        projection: new OpenLayers.Projection("EPSG:900913"),
        displayProjection: new OpenLayers.Projection("EPSG:4326"),
        units: "m",
        
        maxExtent: new OpenLayers.Bounds(-20037508, -20037508, 20037508, 20037508.34),
        allOverlays: true,
        controls: [
        new OpenLayers.Control.Navigation(),
        new OpenLayers.Control.KeyboardDefaults(),
        new OpenLayers.Control.PanZoom()
        //new OpenLayers.Control.ArgParser(),
        //new OpenLayers.Control.Attribution()    
        
        ],
        //tile
        maxResolution: 156543.0339,
                
        numZoomLevels: 22
    };

    this.map = new OpenLayers.Map('map', options);

    this.getLayersActive = function(){
        var temp = new Array();
        for(var i =0;i<$.jstree._focused().get_checked(null,true).length ;i++){
            if($($.jstree._focused().get_checked(null,true)[i]).attr("id"))
                temp.push($($.jstree._focused().get_checked(null,true)[i]).attr("id"));
        }
        return temp;
    }
 
    var json = '{"request":"layer.forJSTree","userHash":"'+ligeo.userHash+'","param":{"planId":'+ligeo.planId+'}}';
    var layersJson = apiURL+"?json="+json;
 
    var res = OpenLayers.Request.issue({ 
        method: 'POST', 
        params: {
            json:json
        },
        url: layersJson, 
        headers:{
            "Content-Type": 
            "application/x-www-form-urlencoded"
        }, 
        async: false
    }); 

    var g =  new OpenLayers.Format.JSON(); 
    var data = g.read(res.responseText); 
    
      
    this.planLayers = new Array();
    this.planLayers.length=0;
    
    this.planLayersName = new Array();
    
    this.queryableLayers = new Array();
    
    this.queryableLayersName = new Array();
    
    this.zIndexLayers = new Array();
    
    this.printableLayers = new Array();
    
    this.inlegendLayers = new Array();
    
    //var name = "ligeo_ostresany:gp_domy"
    //  var sld = '<StyledLayerDescriptor><NamedLayer><Name>ligeo_ostresany:gp_domy</Name><UserStyle><FeatureTypeStyle><Rule><LineSymbolizer><Stroke><CssParameter name="stroke">#0000FF</CssParameter><CssParameter name="stroke-width">3</CssParameter></Stroke></LineSymbolizer></Rule></FeatureTypeStyle></UserStyle></NamedLayer></StyledLayerDescriptor>';
 
 

 
 

    for(var i = 0; i < data.length; i++)
    {
        if(data[i].children)
            for(var j = 0; j<data[i].children.length; j++){
             
                if(data[i].children[j].attr.id){
                    //todo title a jestli muzu editovat
                    var metadata = 
                    {
                        "type":data[i].children[j].type,
                        "legendImage":data[i].children[j].legendImage.replace("\\/", "/"),
                        "inLegend":data[i].children[j].inLegend,
                        "queryable":data[i].children[j].queryable,
                        
                        "printable":data[i].children[j].printable,
                        "layerStyleId":data[i].children[j].layerStyleId,
                        "layerId":data[i].children[j].layerId,
                        "isLockedForGeometry":data[i].children[j].isLockedForGeometry
        
                    }
                    
                    if(data[i].children[j].type == "xyz"){
                        var layer = new OpenLayers.Layer.XYZ(data[i].children[j].attr.id, data[i].children[j].url, {
                            transitionEffect: 'resize',
                            
                            metadata: metadata,
                            opacity: data[i].children[j].opacity,
                            visibility: (data[i].children[j].visibility==1),
                            isBaseLayer: false
                        });
                    }else{
                        var layer = new OpenLayers.Layer.WMS(data[i].children[j].data, data[i].children[j].url,
                        {
                            layers:data[i].children[j].attr.id,
                            format:data[i].children[j].format,
                            palette:data[i].children[j].palette,
                            transparent: data[i].children[j].transparent,
                            id:data[i].children[j].attr.id,
                            name:data[i].children[j].data
                        },
                        {
                            metadata: metadata,
                            opacity: data[i].children[j].opacity,
                            visibility: (data[i].children[j].visibility==1),
                            singleTile: true,
                            isBaseLayer: false,
                            buffer: 0,
                            tileLoadingDelay: 0,
                            ratio: 1
                        }
                        );
     
                    }
      
                
                    this.planLayers.push(layer);
                    this.planLayersName.push(data[i].children[j].attr.id);
                    if (data[i].children[j].queryable=="1") {
                        this.queryableLayers.push(layer);
                    };
                    this.zIndexLayers.push(data[i].children[j].zIndex);
                    if (data[i].children[j].printable=="1") {
                        this.printableLayers.push(layer);
                    };
                    
 
                    this.map.addLayer(layer);
                }
            } 
    }

    //pruhlednost spatne nactenych vrstev a tilu 

    OpenLayers.IMAGE_RELOAD_ATTEMPTS = 2;
    OpenLayers.Util.onImageLoadErrorColor = "transparent";
    OpenLayers.Util.onImageLoadError =function(){
        this.src='http://www.openlayers.org/api/img/blank.gif';
    }
    OpenLayers.Tile.Image.useBlankTile=false;


    // LAYER TREE
           
    $(function () {
        $("#LayerTreeSearch").keyup(function () {
            $("#LayerTree").jstree("search",this.value);
        });
        $("#LayerTreeSearch").change(function () {
            $("#LayerTree").jstree("search",this.value);
        });
    });
                 
                 
                 
    $(function () {
      
        $("#LayerTree").jstree({
                            
                 
            "json_data" : {
                "data" :data   
            },
            "crrm" : { 
                "move" : {
                    "check_move" : function (m) { 
                        var p = this._get_parent(m.o);
                        if(!p) return false;
                        p = p == -1 ? this.get_container() : p;
                        if(p === m.np) return true;
                        if(p[0] && m.np[0] && p[0] === m.np[0]) return true;
                        return false;
                    }
                }
            },
            "dnd" : {
                "drop_target" : false,
                "drag_target" : false
            },

            hotkeys: {
                "del" : function () {
                    return false;
                }
            },
            
            checkbox:{
                "override_ui": true
            },

                            
            "themes": {
                "theme": "default",
                "dots": true,
                "icons": true
            },
                    
            "types" : {
                "valid_children" : "all",
                "types" : {
                    "root" : {
                        "icon" : { 
                            "image" : "http://static.jstree.com/v.1.0rc/_docs/_drive.png" 
                        },
                        "valid_children" : [ "default" ],
                        "max_depth" : 1,                    
                        "delete_node"	: function(){
                            alert("Složku nelze smazat");
                            return false;
                        }
                    
                    },
                    "default" : {
                                          
                        "valid_children" : [ "default" ],
                        "max_depth" : 0
                    }
                }
            },
                
                
   
            //dalsi: ,contextmenu,dnd
            "plugins" : ["themes","ui","crrm","hotkeys","search","types","json_data","checkbox"]
		
      
        })
                        
    
        .bind("loaded.jstree", function () {

            })
                     
        .bind("check_node.jstree uncheck_node.jstree", function () {
         
                           
                   
            for(var i =0;i<$.jstree._focused().get_checked(null,true).length ;i++){
                if($($.jstree._focused().get_checked(null,true)[i]).attr("id")){
                    ligeo.ligeoMap.planLayers[ligeo.ligeoMap.planLayersName.indexOf($($.jstree._focused().get_checked(null,true)[i]).attr("id"))].setVisibility(true);
                }
            }
                                                   
            for(var i =0;i<$.jstree._focused().get_unchecked(null,true).length ;i++){ 
                if($($.jstree._focused().get_unchecked(null,true)[i]).attr("id")){
                    ligeo.ligeoMap.planLayers[ligeo.ligeoMap.planLayersName.indexOf($($.jstree._focused().get_unchecked(null,true)[i]).attr("id"))].setVisibility(false);
                }
            }
        })               
    });
       
    // LAYER TREE KONEC
    
    
    
    // Priradi Zindex
    
    for(var i =0;i<this.planLayers.length ;i++){
        this.planLayers[i].setZIndex(this.zIndexLayers[i])                
    }
    // konec Zindex
    
    
    
    
    
  
    //konec vrstvy json

    //konec pridani vrstev
    //vytvoření parametru get URL u vrstvy DKM, RST_KMD        
    function get_wms_url(bounds) {
        // recalculate bounds from Google to WGS
        var proj = new OpenLayers.Projection("EPSG:4326");
        bounds.transform(this.map.getProjectionObject(), proj);

        var url = this.url;
        url += "&REQUEST=GetMap";
        url += "&SERVICE=WMS";
        url += "&VERSION=1.1.1";
        url += "&LAYERS=" + this.layers;
        url += "&FORMAT=" + this.format;
        url += "&TRANSPARENT=TRUE";
        url += "&SRS=" + "EPSG:4326";  //todo automaticky
        url += "&BBOX=" + bounds.toBBOX();
        url += "&WIDTH=" + this.tileSize.w;
        url += "&HEIGHT=" + this.tileSize.h;
        return url;
    }
    //konec vytvoření funkce
    //transfotmace souradnic bodu centru pri nacteni
    //point.transform(new OpenLayers.Projection("EPSG:4326"), this.map.getProjectionObject());
    this.map.setCenter(this.point, this.zoom);
    //konec transformace
  

 


       
       
    






    //pohyb po mapě
    var dragMap = new OpenLayers.Control.DragPan({
        title:'Navigation', 
        displayClass: 'olControlPanMap'
    });

    this.toolbar.addControls([dragMap]);
    this.map.addControl(this.toolbar);







    /////////////////////////////////////////////////////////prevod souradnic

    function to_deg (la, lo) {
        var a = convertCoord.rad2deg(la);
        var o = convertCoord.rad2deg(lo);
        function resToStr(v,l,f) {
            var ret=convertCoord.resToString(v,l,f);
            return ''+ ret.locNum+ret.locStr ;
        }
        document.getElementById('dmsla').innerHTML = resToStr(a,'lat',2);
        document.getElementById('dmslo').innerHTML = resToStr(o,'lot',2);
        document.getElementById('dmla').innerHTML = resToStr(a,'lat',1);
        document.getElementById('dmlo').innerHTML = resToStr(o,'lot',1);
        document.getElementById('dla').innerHTML = resToStr(a,'lat',0);
        document.getElementById('dlo').innerHTML = resToStr(o,'lot',0);
    }

    function to_seznam (la,lo) {
        var ret = convertCoord.radToSeznam(la,lo);
        document.getElementById('seznamx').innerHTML = ret.x;
        document.getElementById('seznamy').innerHTML = ret.y;
    }

    function to_jtsk (la,lo) {
        var ret = convertCoord.radToJTSK(la,lo);
        document.getElementById('jtskx').innerHTML = ret.x;
        document.getElementById('jtsky').innerHTML = ret.y;
    }

    function to_s42 (la,lo) {
        var ret = convertCoord.radToS42(la,lo);
        document.getElementById('s42x').innerHTML = ret.x;
        document.getElementById('s42y').innerHTML = ret.y;
    }

    function to_utm (la,lo) {
        var ret = convertCoord.radToUTM(la,lo);
        document.getElementById('utmn').innerHTML = ret.north;
        document.getElementById('utme').innerHTML = ret.east;
        document.getElementById('utmz').innerHTML = ret.zone;
    }

    function result (la, lo) {
        to_deg(la, lo);
        to_seznam(la, lo);
        to_jtsk(la, lo);
        to_s42(la, lo);
        to_utm(la, lo);
        var el = document.getElementById('ret');
        el.textContent = "Radians: lat:"+convertCoord.roundoff(la,8)+" lon:"+convertCoord.roundoff(lo,8);
    }

    function readformpos(f) {
        var x = f.x.value;
        var y = f.y.value;
        if (x.match( /[^0-9]/ )) {
            alert("x is not a number");
            return;
        }
        if (y.match( /[^0-9]/ )) {
            alert("y is not a number");
            return;
        }
        x = +x;
        y = +y;
        return {
            x:x, 
            y:y
        };
    }

    function seznam () {
        var r =  readformpos(document.forms.seznamf);
        r = convertCoord.seznamToRad(r.x,r.y);
        result(r.lat, r.lot);
        return;
    }
    function jtsk () {
        var r = readformpos(document.forms.jtskf);
        r = convertCoord.JTSKToRad(r.y,r.x);
        result(r.lat, r.lot);
        return;
    }
    function utm () {
        var f = document.forms.utmf;
        var e = f.east.value;
        var n = f.north.value;
        var z = f.zone.value;
        if (e.match( /[^0-9]/ )) {
            alert("east is not a number");
            return;
        }
        if (n.match( /[^0-9]/ )) {
            alert("north is not a number");
            return;
        }
        if (z.match( /[^0-9]/ )) {
            alert("zone is not a number");
            return;
        }
        e = +e;
        n = +n;
        z = +z;
        r = convertCoord.UTMToRad(n,e,z);
        result(r.lat, r.lot);
        return;
    }
    function s42 () {
        var r =  readformpos(document.forms.s42f);
        r = convertCoord.s42ToRad(r.y,r.x);
        result(r.lat, r.lot);
        return;
    }
    function lalo() {
        var f = document.forms.lalof;
        var a = f.lat.value;
        var o = f.lon.value;
        var la = convertCoord.deg2rad(convertCoord.StringToRes(a));
        var lo = convertCoord.deg2rad(convertCoord.StringToRes(o));
        result(la, lo);
        return;
    }
    //////////////////////////////////////////////////////////////////////////////
    /*
 Conversions between various (czech) coordinate systems.
 Based on various codes, see comments.
 Put together and modified by Tomas Ebenlendr (ebik@ucw.cz)
     */

    this.convertCoord = {
        /*
 =============================== BASICS =============================
    Does basic conversions between radians and degrees,dm,dms format.
         */

        // =====
        // degrees -> radians
        deg2rad:function(num) {
            var num=num*Math.PI/180;
            return num
        },

        // =====
        // radians -> degrees
        rad2deg:function(num2) {
            var num2=num2*180/Math.PI;
            return num2
        },

        // =====
        // rounds x to y digits after decimal point
        roundoff:function(x,y) {
            x=parseFloat(x);
            y=parseFloat(y);
            x=Math.round(x*Math.pow(10,y))/Math.pow(10,y);
            return x
        },

        // =====
        // degrees value -> D / DM / DMS string;
        // returns object with locNum:value in D/DM/DMS and locStr: one of N,S,W,E
        // spec is string containing either 'la' or 'lo' and 'd' or 'dm' or 'dms'
        resToString:function(num,spec){
            var secsize=3;
            var minsize=5;
            var degsize=7;
            if ((! spec) || (!spec.match)) {
                spec = "";
            }
            if (! spec.match(/d/i)) {
                spec = spec + 'dms'; //default
            }

            if(spec.match(/lo/i)){
                if(num<0){
                    var str='W';
                    num=Math.abs(num)
                }else{
                    var str='E'
                }
            }else{
                if(num<0){
                    var str='S';
                    num=Math.abs(num)
                }else{
                    var str='N';
                }
            }
            var degree=0;
            var minut =0;
            var second=0;

            var y=Math.abs(parseFloat(num));
            if (spec.match(/dm/i)) {
                degree=parseInt(y+1)-1;
                y=(y-degree)*60;
            } else {
                degree=this.roundoff(y,degsize);
                y=0;
            }
            if (spec.match(/dms/i)) {
                minut=parseInt(y+1)-1;
                y=(y-minut)*60;
                second=this.roundoff(y,secsize);
            } else {
                minut=this.roundoff(y,minsize);
                y = 0;
            }

            if(second>=60){
                second=second - 60;
                minut=minut+1;
            }
            if(minut>=60){
                minut=minut - 60;
                degree=degree+1
            }
            value=""+degree;
            if (spec.match(/dm/i)) {
                value = value + "\260"+minut+"\'"
            }
            if (spec.match(/dms/i)) {
                value = value +second+'\"';
            }
            return{
                locNum:value,
                locStr:str
            };
        },

        // =====
        //accepts lat, lot in degrees,
        // if dms contains 'p' it returns 'Nddmmss Eddmmss' otherwise 'ddmmssNddmmssE'
        // where 'ddmmss' depends on what dms contains. (dms should NOT contain 'la' nor 'lo')
        degStr:function(lat, lot, dms){
            var la = this.resToString(lat,'lat'+dms);
            var lo = this.resToString(lot,'lot'+dms);
            return (dms && dms.match && dms.match(/p/i)) ?
            '' + la.locStr+la.locNum + ' ' + lo.locStr+lo.locNum :
            '' + la.locNum+la.locStr + ' ' + lo.locNum+lo.locStr ;
        },

        // =====
        // as degStr, but accepts lat and lot in radians
        radStr:function (lat, lot, dms, pre) {
            return this.degStr(this.rad2deg(lat),this.rad2deg(lot),dms,pre);
        },

        // =====
        // parses [N|S|W|E] [-] ddmmss [N|S|W|E] string to number
        StringToRes:function(str) {
            var parse = str.match("^ *" +
                "([NSWE]?) *" + //1
                "(-?) *"+ //2
                "([0-9][0-9]*(\\.[0-9]*)?) *" + //3
                "([dDo\260 ] *"+
                "(([0-9][0-9]*(\\.[0-9]*)?) *" + //7
                "([' ] *" +
                "(([0-9][0-9]*(\\.[0-9]*)?) *" + //11
                "\"? *"+
                ")?)?" +
                ")?)?" +
                "([NSWE]?) *$"); //13
            if (parse === null) {
                return 1 / 0; //NaN (not parsed)
            }
            if (parse[1].length+parse[13].length >=2) {
                return 1 / 0; //NaN (double NSWE specification
            }
            if (parse[7] === undefined) {
                parse[7] = 0
            }
            if (parse[11] === undefined) {
                parse[11] = 0
            }
            return     (parse[1].match(/[SW]/)?-1:1)*
            (parse[2].length       ?-1:1)*
            (parse[13].match(/[SW]/)?-1:1)*
            (parseFloat(parse[3])+parseFloat(parse[7])/60+parseFloat(parse[11])/3600);
        },


        /*
 =============================== JTSK =============================
    Converts JTSK-95 coordinates in area of Czech Republic
    based on code by Zdenek Hrdina (c) 2001,2002
    zhrdina@zs.koop.cz, zhrdina@koop.cz

    modified by Tomas Ebenlendr
    ebik@ucw.cz
         */


        // =====
        // accepts jtsk-95: Y,X,height
        // returns lat,lot,alt (radians, meters)
        JTSKToRad:function (Y,X,H)
        {
            /* PĹ™epoÄŤet vstupĂ­ch ĂşdajĹŻ */
            if (H === undefined) {
                H = 300;// Relativne rozumna vyska
            }
            H+=45;
            var deg2rad = this.deg2rad;

            /*Vypocet zemepisnych souradnic z rovinnych souradnic*/
            var zemsour = (function (X,Y) {
                // var a=6377397.15508;
                var e=0.081696831215303;
                var n=0.97992470462083;
                var konst_u_ro=12310230.12797036;
                var sinUQ=0.863499969506341;
                var cosUQ=0.504348889819882;
                var sinVQ=0.420215144586493;
                var cosVQ=0.907424504992097;
                var alfa=1.000597498371542;
                var k=1.003419163966575;
                var ro=Math.sqrt(X*X+Y*Y);
                var epsilon=2*Math.atan(Y/(ro+X));
                var D=epsilon/n;
                var S=2*Math.atan(Math.exp(1/n*Math.log(konst_u_ro/ro)))-Math.PI/2;
                var sinS=Math.sin(S);
                var cosS=Math.cos(S);
                var sinU=sinUQ*sinS-cosUQ*cosS*Math.cos(D);
                var cosU=Math.sqrt(1-sinU*sinU);
                var sinDV=Math.sin(D)*cosS/cosU;
                var cosDV=Math.sqrt(1-sinDV*sinDV);
                var sinV=sinVQ*cosDV-cosVQ*sinDV;
                var cosV=cosVQ*cosDV+sinVQ*sinDV;
                var Ljtsk=2*Math.atan(sinV/(1+cosV))/alfa;
                var t=Math.exp(2/alfa*Math.log((1+sinU)/cosU/k));
                var pom=(t-1)/(t+1);
                var sinB;
                do {
                    sinB=pom;
                    pom=t*Math.exp(e*Math.log((1+e*sinB)/(1-e*sinB)));
                    pom=(pom-1)/(pom+1);
                } while (Math.abs(pom-sinB)>1e-15);
                var Bjtsk=Math.atan(pom/Math.sqrt(1-pom*pom));
                return {
                    L:Ljtsk, 
                    B:Bjtsk
                };
            })(X,Y);

            /* PravoĂşhlĂ© souĹ™adnice ve S-JTSK */
            var pravjtsk = (function (Bjtsk, Ljtsk, H) {
                var a=6377397.15508;
                var f_1=299.152812853;
                var e2=1-(1-1/f_1)*(1-1/f_1);
                ro=a/Math.sqrt(1-e2*Math.sin(Bjtsk)*Math.sin(Bjtsk));
                var x=(ro+H)*Math.cos(Bjtsk)*Math.cos(Ljtsk);
                var y=(ro+H)*Math.cos(Bjtsk)*Math.sin(Ljtsk);
                var z=((1-e2)*ro+H)*Math.sin(Bjtsk);
                return {
                    x:x, 
                    y:y, 
                    z:z
                }
            })(zemsour.B, zemsour.L, H)

            /* PravoĂşhlĂ© souĹ™adnice v WGS-84*/
            var pravwgs = (function (x, y, z) {
                var dx=570.69;
                var dy=85.69;
                var dz=462.84;
                var wz=deg2rad(-5.2611/3600);
                var wy=deg2rad(-1.58676/3600);
                var wx=deg2rad(-4.99821/3600);
                var m=3.543e-6;
                var xn=dx+(1+m)*(x+wz*y-wy*z);
                var yn=dy+(1+m)*(-wz*x+y+wx*z);
                var zn=dz+(1+m)*(wy*x-wx*y+z);
                return {
                    x: xn, 
                    y: yn, 
                    z: zn
                };
            })(pravjtsk.x, pravjtsk.y, pravjtsk.z);

            /* GeodetickĂ© souĹ™adnice v systĂ©mu WGS-84*/
            var geowgs = (function (xn, yn, zn) {
                var a=6378137.0;
                var f_1=298.257223563;
                var a_b=f_1/(f_1-1);
                var p=Math.sqrt(xn*xn+yn*yn);
                var e2=1-(1-1/f_1)*(1-1/f_1);
                var theta=Math.atan(zn*a_b/p);
                var st=Math.sin(theta);
                var ct=Math.cos(theta);
                var t=(zn+e2*a_b*a*st*st*st)/(p-e2*a*ct*ct*ct);
                var B=Math.atan(t);
                var L=2*Math.atan(yn/(p+xn));
                var H=Math.sqrt(1+t*t)*(p-a/Math.sqrt(1+(1-e2)*t*t));
                return {
                    B:B, 
                    L:L, 
                    H:H
                };
            })(pravwgs.x, pravwgs.y, pravwgs.z);

            return {
                lat:geowgs.B, 
                lot:geowgs.L, 
                alt:geowgs.H-45
            }
        },


        // =====
        // accepts lat,lot,alt, (radians, meters)
        // returns jtsk-95 y,x,z (z is altitude in meters)
        radToJTSK:function(lat, lot, alt)
        {
            var B=lat;
            var L=lot;
            var H=alt;
            var deg2rad = this.deg2rad;

            if (alt === undefined) {
                H = 300;// Relativne rozumna vyska
            }

            /* PravoĂşhlĂ© souĹ™adnice ve WGS-84 */
            var pravwgs = (function (B, L, H) {
                var a=6378137.0;
                var f_1=298.257223563;
                var e2=1-(1-1/f_1)*(1-1/f_1);
                var ro=a/Math.sqrt(1-e2*Math.sin(B)*Math.sin(B));
                var x=(ro+H)*Math.cos(B)*Math.cos(L);
                var y=(ro+H)*Math.cos(B)*Math.sin(L);
                var z=((1-e2)*ro+H)*Math.sin(B);
                return {
                    x: x, 
                    y: y, 
                    z: z
                };
            })(B, L, H);

            /* PravoĂşhlĂ© souĹ™adnice v S-JTSK */
            var pravjtsk = (function (x, y, z) {
                var dx=-570.69;
                var dy=-85.69;
                var dz=-462.84;
                var wz=deg2rad(5.2611/3600); //FIXME use deg2rad
                var wy=deg2rad(1.58676/3600);
                var wx=deg2rad(4.99821/3600);
                var m=-3.543e-6;
                var xn=dx+(1+m)*(x+wz*y-wy*z);
                var yn=dy+(1+m)*(-wz*x+y+wx*z);
                var zn=dz+(1+m)*(wy*x-wx*y+z);
                return {
                    x: xn, 
                    y: yn, 
                    z: zn
                };
            })(pravwgs.x, pravwgs.y, pravwgs.z);

            /* GeodetickĂ© souĹ™adnice v systĂ©mu S-JTSK */
            var geojtsk = (function (xn, yn, zn) {
                var a=6377397.15508;
                var f_1=299.152812853;
                var a_b=f_1/(f_1-1);
                var p=Math.sqrt(xn*xn+yn*yn);
                var e2=1-(1-1/f_1)*(1-1/f_1);
                var theta=Math.atan(zn*a_b/p);
                var st=Math.sin(theta);
                var ct=Math.cos(theta);
                var t=(zn+e2*a_b*a*st*st*st)/(p-e2*a*ct*ct*ct);
                var B=Math.atan(t);
                var L=2*Math.atan(yn/(p+xn));
                var H=Math.sqrt(1+t*t)*(p-a/Math.sqrt(1+(1-e2)*t*t));
                return {
                    B: B, 
                    L: L, 
                    H: H
                };
            })(pravjtsk.x, pravjtsk.y, pravjtsk.z);

            /* RovinnĂ© souĹ™adnice v systĂ©mu S-JTSK */
            var rovjtsk = (function (B, L, H) {
                var a=6377397.15508;
                var e=0.081696831215303;
                var n=0.97992470462083;
                var konst_u_ro=12310230.12797036;
                var sinUQ=0.863499969506341;
                var cosUQ=0.504348889819882;
                var sinVQ=0.420215144586493;
                var cosVQ=0.907424504992097;
                var alfa=1.000597498371542;
                var k_2=1.00685001861538;
                var sinB=Math.sin(B);
                var t=(1-e*sinB)/(1+e*sinB);
                t=(1+sinB)*(1+sinB)/(1-sinB*sinB)*Math.exp(e*Math.log(t));
                t=k_2*Math.exp(alfa*Math.log(t));
                var sinU=(t-1)/(t+1);
                var cosU=Math.sqrt(1-sinU*sinU);
                var V=alfa*L;
                var sinV=Math.sin(V);
                var cosV=Math.cos(V);
                var cosDV=cosVQ*cosV+sinVQ*sinV;
                var sinDV=sinVQ*cosV-cosVQ*sinV;
                var sinS=sinUQ*sinU+cosUQ*cosU*cosDV;
                var cosS=Math.sqrt(1-sinS*sinS);
                var sinD=sinDV*cosU/cosS;
                var D=Math.atan(sinD/Math.sqrt(1-sinD*sinD));
                var epsilon=n*D;
                var ro=konst_u_ro*Math.exp(-n*Math.log((1+sinS)/cosS));
                var X=ro*Math.cos(epsilon);
                var Y=ro*Math.sin(epsilon);
                return {
                    X: X, 
                    Y: Y, 
                    H: H
                };
            })(geojtsk.B, geojtsk.L, geojtsk.H);

            return {
                y: rovjtsk.Y, 
                x:rovjtsk.X, 
                z:rovjtsk.H
            };
        },

        /*
 =============================== S42 =============================
    Converts S-42 coordinates in area of Czech Republic
    based on code by Gabor Timar (Majster V1.0, prevody.xls)
    timar@ludens.elte.hu

    javascripted by Tomas Ebenlendr
    ebik@ucw.cz
         */

        // =====
        // S42 datum
        datumS42:
        {
            a1: 6378245,
            f1: 0.00335232986925913,
            a2: 6378137,
            f2: 0.00335281066474748,

            // Tunable parameters
            dx: 26,
            dy: -121,
            dz: -78
        },

        // =====
        // abridged Mologensky, accuracy 2m
        abridgedMologensky:function (FI, LA, pars, rev) {
            if (rev) {
                var a1 = pars.a2;
                var f1 = pars.f2;
                var a2 = pars.a1;
                var f2 = pars.f1;

                var dx = -pars.dx;
                var dy = -pars.dy;
                var dz = -pars.dz;
            } else {
                var a1 = pars.a1;
                var f1 = pars.f1;
                var a2 = pars.a2;
                var f2 = pars.f2;

                var dx = pars.dx;
                var dy = pars.dy;
                var dz = pars.dz;
            }

            var sFI = Math.sin(FI);
            var cFI = Math.cos(FI);
            var sLA = Math.sin(LA);
            var cLA = Math.cos(LA);
            var e1_2 = 2*f1 - f1*f1;
            var t1 = 1 - e1_2*sFI*sFI;
            var M = pars.a1*(1 - e1_2)/Math.pow(t1,1.5);
            var N = a1/Math.sqrt(t1);
            var cs = Math.sin(this.deg2rad(1/3600));
            var dFIs = ( - dx*sFI*cLA - dy*sFI*sLA + dz*cFI
                + (a1*(f2-f1) + f1*(a2-a1))*Math.sin(2*FI)) / (M*cs);
            var dLAs = (-dx*sLA + dy*cLA) / (N*cFI*cs);
            return {
                fi:FI + this.deg2rad(dFIs/3600), 
                lambda: LA + this.deg2rad(dLAs/3600)
            };
        },

        // =====
        // accepts y, x in S42 coordinate system (i.e. northing, easting)
        // returns lat, lot (radians)
        s42ToRad:function (Y,X)
        {
            /* centimeter accuracy ? */
            var deg2rad = this.deg2rad;
            var gk=(function (Y, X) {
                var zone=Math.floor(X/1000000)

                var a=6378245;
                var e=0.0818133340169312;
                var e2=e*e;
                var FE=500000+zone*1000000;
                var FN=0;
                var lambda0=deg2rad(6*zone-3);
                /* var fi0=0; */
                var k0=1;
                var x = X-FE;
                var y = Y-FN;
                var e1 = (1-Math.sqrt(1-e2))/(1+Math.sqrt(1-e2));
                var t1 = 1 - e2/4 - 3*e2*e2/64 - 5*e2*e2*e2/256;
                /*
      var t2 = 3*e2/8 + 3*e2*e2/32 + 45*e2*e2*e2/1024;
      var t3 = 15*e2*e2/256+45*e2*e2*e2/1024;
      var t4 = 35*e2*e2*e2/3072;
      var M0 = a * (fi0 * t1
                    - Math.sin(2*fi0)*t2
                    + Math.sin(4*fi0)*t3
                    - Math.sin(6*fi0)*t4);
                 */
                var M0 = 0;
                var M = M0 + y/k0;
                var mu = M / (a * t1);
                var fi1 = mu + Math.sin(2*mu)*(3*e1/2 - 27*Math.pow(e1,3)/32)
                + Math.sin(4*mu)*(21*e1*e1/16 - 55*Math.pow(e1,4)/32)
                + Math.sin(6*mu)*151*Math.pow(e1,3)/96
                + Math.sin(8*mu)*1079*Math.pow(e1,4)/512;
                var e_2 = e2/(1-e2);
                var C1 = e_2*Math.pow(Math.cos(fi1),2);
                var T1 = Math.pow(Math.tan(fi1),2);
                var t5 = 1-e2*Math.pow(Math.sin(fi1),2);
                var N1 = a / Math.sqrt(t5);
                var R1 = a * (1-e2) / Math.pow(t5,1.5);
                var D = x / (N1 * k0);
                var fi = fi1 - (N1*Math.tan(fi1)/R1) * (
                    D*D/2
                    - (5 + 3*T1 + 10*C1 - 4*C1*C1 - 9*e_2)*Math.pow(D,4)/24
                    + (61 + 90*T1 + 298*C1 + 45*T1*T1 - 252*e_2 - 3*C1*C1)*Math.pow(D,6)/720);
                var lambda = lambda0 + (
                    D
                    - (1 + 2*T1 + C1)*Math.pow(D,3)/6
                    + (5 - 2*C1 + 28*T1 - 3*C1*C1 + 8*e_2 + 24*T1*T1)*Math.pow(D,5)/120
                    )/Math.cos(fi1);
                /*
      alert('fi1:' + fi1 + '\n' +
            'lambda0:' + lambda0 + '\n' +
            'N1:' + N1 + '\n' +
            'R1:' + R1 + '\n' +
            'D:' + D + '\n' +
            'T1:' + T1 + '\n' +
            'C1:' + C1 + '\n' +
            'e_2:' + e_2 + '\n' +
            't5:' + t5 + '\n' +
            '\n' +
            'fi:' + fi + '\n' +
            'lambda:' + lambda);
                 */
                return {
                    fi:fi, 
                    lambda:lambda
                };
            })(Y, X);

            var am = this.abridgedMologensky( gk.fi, gk.lambda, this.datumS42);

            return {
                lat:am.fi, 
                lot:am.lambda
            }
        },


        // =====
        // accepts lat, lot (radians)
        // returns y, x in S42 coordinate system (i.e. northing, easting)
        radToS42:function(lat, lot)
        {
            var am = this.abridgedMologensky( lat, lot, this.datumS42, true);
            var deg2rad = this.deg2rad;
            var rad2deg = this.rad2deg;

            var s = (function (FI, LA){
                var zone=Math.floor(rad2deg(LA)/6)

                var a=6378245;
                var e=0.0818133340169312;
                var e2=e*e;
                var FE=1500000+zone*1000000;
                var FN=0;
                var lambda0=deg2rad(6*zone+3);
                /*var fi0=0;*/
                var t1 = 1 - e2/4 - 3*e2*e2/64 - 5*e2*e2*e2/256;
                var M0 = 0;
                var k0=1;
                var e_2 = e2/(1-e2);
                var cFI = Math.cos(FI);
                var tFI = Math.tan(FI);
                var N = a / Math.sqrt(1 - e2*Math.pow(Math.sin(FI),2));
                var T = tFI*tFI;
                var C = e_2 * cFI * cFI;
                var A = (LA - lambda0)*cFI;
                var t2 = 3*e2/8 + 3*e2*e2/32 + 45*e2*e2*e2/1024;
                var t3 = 15*e2*e2/256+45*e2*e2*e2/1024;
                var t4 = 35*e2*e2*e2/3072;
                var M = a*( FI * t1
                    - Math.sin(2*FI)*t2
                    + Math.sin(4*FI)*t3
                    - Math.sin(6*FI)*t4);
                var x = k0*N*(A + (1 - T + C)*Math.pow(A,3)/6
                    + (5 - 18*T + T*T + 72*C - 85*e_2)*Math.pow(A,5)/120);
                var y = k0*(M - M0 + N*tFI*(
                    A*A/2 + (5 - T  + 9*C + 4*C*C)*Math.pow(A,4)/24
                    + (61 - 58*T + T*T + 600*C - 330*e_2)*Math.pow(A,6)/720));
                /*
      alert('FI:' + FI + '\n' +
            'LA:' + LA + '\n' +
            'lambda0:' + lambda0 + '\n' +
            'M:' + M + '\n' +
            'N:' + N + '\n' +
            'A:' + A + '\n' +
            'T:' + T + '\n' +
            'C:' + C + '\n' +
            'e_2:' + e_2 + '\n' +
            '\n' +
            'x:' + x + '\n' +
            'y:' + y + '\n' +
            'FE:' + FE);
                 */
                return {
                    Y: FN + y, 
                    X: FE + x
                };
            })(am.fi, am.lambda);

            return {
                y:s.Y, 
                x:s.X
            };
        },


        /*
 =============================== UTM =============================
    Converts UTM and seznam coordinates in area of Czech Republic.
    (The functions are inconsistent by 2'' at Portugal for zone 33)
    (I know Portugal is zone 30, but mapy.seznam.cz maps are whole in zone 33)
    Based on code from mapy.seznam.cz.
         */

        // =====
        // accepts x, y in mapy.seznam.cz
        // returns lat, lot (radians)
        seznamToRad:function(x,y) {
            return this.UTMToRad(
                y/32 + 1300000,
                x/32 - 3700000,
                33
                );
        },

        // =====
        // accepts lat, lot (radians)
        // returns x, y in mapy.seznam.cz
        radToSeznam:function(la, lo) {
            var ret = this.radToUTM(la,lo,33);
            return {
                x:(ret.east+3700000)*32, 
                y:(ret.north-1300000)*32
            };
        },

        // =====
        // accepts northing, easting, zone in UTM
        // returns lat, lot (radians)
        UTMToRad:function(north, east, zone){
            var units=1;
            var k=0.9996;
            var a=6378137;
            var f=1/298.257223563;
            var b=a*(1-f);
            var e2=(a*a-b*b)/(a*a);
            var e=Math.sqrt(e2);
            var ei2=(a*a-b*b)/(b*b);
            var ei=Math.sqrt(ei2);
            var n=(a-b)/(a+b);
            var G=this.deg2rad(a*(1-n)*(1-n*n)*(1+(9/4)*n*n+(255/64)*Math.pow(n,4)));
            var northu=(north-0)*units;
            var eastu=(east-500000)*units;
            var m=northu/k;
            var sigma=this.deg2rad(m/G);
            var footlat=sigma+((3*n/2)-(27*Math.pow(n,3)/32))*Math.sin(2*sigma)+((21*n*n/16)-(55*Math.pow(n,4)/32))*Math.sin(4*sigma)+(151*Math.pow(n,3)/96)*Math.sin(6*sigma)+(1097*Math.pow(n,4)/512)*Math.sin(8*sigma);
            var rho=a*(1-e2)/Math.pow(1-(e2*Math.sin(footlat)*Math.sin(footlat)),(3/2));
            var nu=a/Math.sqrt(1-(e2*Math.sin(footlat)*Math.sin(footlat)));
            var psi=nu/rho;
            var t=Math.tan(footlat);
            var x=eastu/(k*nu);

            var laterm1=(t/(k*rho))*(eastu*x/2);
            var laterm2=(t/(k*rho))*(eastu*Math.pow(x,3)/24)*(-4*psi*psi+9*psi*(1-t*t)+12*t*t);
            var laterm3=(t/(k*rho))*(eastu*Math.pow(x,5)/720)*(8*Math.pow(psi,4)*(11-24*t*t)-12*Math.pow(psi,3)*(21-71*t*t)+15*psi*psi*(15-98*t*t+15*Math.pow(t,4))+180*psi*(5*t*t-3*Math.pow(t,4))+360*Math.pow(t,4));
            var laterm4=(t/(k*rho))*(eastu*Math.pow(x,7)/40320)*(1385+3633*t*t+4095*Math.pow(t,4)+1575*Math.pow(t,6));
            var latrad=footlat-laterm1+laterm2-laterm3+laterm4;

            var seclat=1/Math.cos(footlat);
            var loterm1=x*seclat;
            var loterm2=(Math.pow(x,3)/6)*seclat*(psi+2*t*t);
            var loterm3=(Math.pow(x,5)/120)*seclat*(-4*Math.pow(psi,3)*(1-6*t*t)+psi*psi*(9-68*t*t)+72*psi*t*t+24*Math.pow(t,4));
            var loterm4=(Math.pow(x,7)/5040)*seclat*(61+662*t*t+1320*Math.pow(t,4)+720*Math.pow(t,6));
            var w=loterm1-loterm2+loterm3-loterm4;
            var longrad=this.deg2rad((zone-30)*5)+w;

            return {
                lat:latrad, 
                lot:longrad
            };
        },

        // =====
        // accepts lat, lot (radians), forcezone (for not autodetecting utm zone)
        // returns northing, easting, zone in UTM
        radToUTM:function(la,lo,forcezone){
            function roundoff(x,y){
                var x=parseFloat(x);
                var y=parseFloat(y);
                x=Math.round(x*Math.pow(10,y))/Math.pow(10,y);
                return x
            };
            var units=1;
            var distsize=3;
            var latrad=la;
            var lonrad=lo;
            var latddd=this.rad2deg(la);
            var londdd=this.rad2deg(lo);
            var zone=Math.round((londdd+183)/6);
            if (forcezone !== undefined) {
                zone = forcezone;
            }
            var k=0.9996;
            var a=6378137;
            var f=1/298.257223563;
            var b=a*(1-f);
            var e2=(a*a-b*b)/(a*a);
            var e=Math.sqrt(e2);
            var ei2=(a*a-b*b)/(b*b);
            var ei=Math.sqrt(ei2);
            var n=(a-b)/(a+b);
            var G=this.deg2rad(a*(1-n)*(1-n*n)*(1+(9/4)*n*n+(255/64)*Math.pow(n,4)));
            var w=londdd-parseFloat(zone*6-183);
            w=this.deg2rad(w);
            var t=Math.tan(latrad);
            var rho=a*(1-e2)/Math.pow(1-(e2*Math.sin(latrad)*Math.sin(latrad)),(3/2));
            var nu=a/Math.sqrt(1-(e2*Math.sin(latrad)*Math.sin(latrad)));
            var psi=nu/rho;
            var coslat=Math.cos(latrad);
            var sinlat=Math.sin(latrad);
            var A0=1-(e2/4)-(3*e2*e2/64)-(5*Math.pow(e2,3)/256);
            var A2=(3/8)*(e2+(e2*e2/4)+(15*Math.pow(e2,3)/128));
            var A4=(15/256)*(e2*e2+(3*Math.pow(e2,3)/4));
            var A6=35*Math.pow(e2,3)/3072;
            var m=a*((A0*latrad)-(A2*Math.sin(2*latrad))+(A4*Math.sin(4*latrad))-(A6*Math.sin(6*latrad)));
            var eterm1=(w*w/6)*coslat*coslat*(psi-t*t);
            var eterm2=(Math.pow(w,4)/120)*Math.pow(coslat,4)*(4*Math.pow(psi,3)*(1-6*t*t)+psi*psi*(1+8*t*t)-psi*2*t*t+Math.pow(t,4));
            var eterm3=(Math.pow(w,6)/5040)*Math.pow(coslat,6)*(61-479*t*t+179*Math.pow(t,4)-Math.pow(t,6));
            var dE=k*nu*w*coslat*(1+eterm1+eterm2+eterm3);
            var east=roundoff(parseFloat(500000)+(dE/units),distsize);
            var nterm1=(w*w/2)*nu*sinlat*coslat;
            var nterm2=(Math.pow(w,4)/24)*nu*sinlat*Math.pow(coslat,3)*(4*psi*psi+psi-t*t);
            var nterm3=(Math.pow(w,6)/720)*nu*sinlat*Math.pow(coslat,5)*(8*Math.pow(psi,4)*(11-24*t*t)-28*Math.pow(psi,3)*(1-6*t*t)+psi*psi*(1-32*t*t)-psi*2*t*t+Math.pow(t,4));
            var nterm4=(Math.pow(w,8)/40320)*nu*sinlat*Math.pow(coslat,7)*(1385-3111*t*t+543*Math.pow(t,4)-Math.pow(t,6));
            var dN=k*(m+nterm1+nterm2+nterm3+nterm4);
            var north=roundoff(parseFloat(0)+(dN/units),distsize);
            return{
                zone:zone,
                east:east,
                north:north
            };
        }
    }
////////////////////////////////////konec prevod
};







function removeLayersOutOfRange(layerArray){
    var retArray = new Array();
    for(var i = 0; i < layerArray.length; i++) {
        if(layerArray[i].calculateInRange() == true){
            retArray.push(layerArray[i]);
        }
    }
        
    return retArray;
}


if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(obj, start) {
        for (var i = (start || 0), j = this.length; i < j; i++) {
            if (this[i] === obj) {
                return i;
            }
        }
        return -1;
    }
}


function setCookie(c_name,value,exdays)
{
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}


function getCookie(c_name)
{
    var i,x,y,ARRcookies=document.cookie.split(";");
    for (i=0;i<ARRcookies.length;i++)
    {
        x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
        y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
        x=x.replace(/^\s+|\s+$/g,"");
        if (x==c_name)
        {
            return unescape(y);
        }
    }
}

function replaceDiak(txt){
    var ret="";
    var sdiak = "áäčďéěíĺľňóô öŕšťúů üýřžÁÄČĎÉĚÍĹĽŇÓÔ ÖŔŠŤÚŮ ÜÝŘŽ"; 
    var bdiak = "aacdeeillnoo orstuu uyrzAACDEEILLNOO ORSTUU UYRZ"; 
    for(var p = 0; p < txt.length; p++) 
    { 
        if (sdiak.indexOf(txt.charAt(p)) != -1) 
        { 
            ret += bdiak.charAt(sdiak.indexOf(txt.charAt(p))); 
        } 
        else ret += txt.charAt(p); 
    } 
    return ret;
} 






var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-24999566-1']);
_gaq.push(['_trackPageview']);

(function() {
    var ga = document.createElement('script');
    ga.type = 'text/javascript';
    ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ga, s);
})();


$(document).ready(function(){
    function showSplashScreen()
    {
                    
        var splashImg='splashscreen.png';
                
        $.ajax({
            url:splashImg,
            type:'HEAD',
            error:
            function(){
                return false;
            },
            success:
            function(){

                setTimeout(function(){
                    $.fn.prettyPhoto({
                        theme:"dark_square", //u prettyPhoto.css byl odstraněn v řádku 65 pp_content(nezobrazuje se černé pozadí)
                        show_title:false,
                        social_tools:false,
                        ie6_fallback: true,
                        markup: '<div class="pp_pic_holder"> \
                                                <div class="ppt">&nbsp;</div> \
                                                <div class="pp_content_container"> \
                                                        <div class="pp_content"> \
                                                                        <div class="pp_loaderIcon"></div> \
                                                                        <div class="pp_fade"> \
                                                                                <div id="pp_full_res"></div> \
                                                                                <div class="pp_details"></div> \
                                                                        </div> \
                                                        </div> \
                                                </div> \
                                        </div>'
				


                    });
                    $.prettyPhoto.open(splashImg,'splash','splash');
                },250);
                window.setTimeout(function() {
                    $.prettyPhoto.close()
                }, 5000);
                    
            }
        });
    }
                
    showSplashScreen();
                
});

function validate_url(val)
{
    var tomatch= /http:\/\/[A-Za-z0-9\.-]{3,}\.[A-Za-z]{2}/

    if (tomatch.test(val)){
        return true;
    }
    else
    {
        return false;
    }
}

function validate_file_extension(val){
    var type = val.substr(val.lastIndexOf('.')+1) ;
    
    if(type=="png" || type=="jpg" || type=="jpeg" || type=="gif"){
        return "img";
    }else if(type == "pdf"){
        return "pdf";
    }else if(type == "doc" || type == "rtf"){
        return "doc";
    }else if(type=="xls" || type=="cvs"){
        return "xls";
    }else if(type == "zip" || type=="7zip" || type=="rar" || type =="tar"){
        return "zip";
    }else{
        return "other";
    }
    
}
