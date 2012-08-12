function LiGeobiotopy(ligeo){
    var ligeo = ligeo;
    this.name = "biotopy";
    this.panel;
     var layerName="biotopy2";
    
    
    
    $("#tabMenuContent").append('<div class="tabMenuContent" id="tabMenuContentbiotopy"><form><INPUT type="button" value="Hospodářská hodnota" onClick="ligeo.modules[\'biotopy\'].hosp()"><INPUT type="button" value="Společenská hodnota" onClick="ligeo.modules[\'biotopy\'].spol()"></form><div id="biotopyGfiContent"/></div>');
    
    
    this.title = function(){
        //todo taday zjistim lokalizaci 
        return "biotopy";
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
            document.getElementById("tabMenuContent"+this.name).innerHTML = "Pro editace musíte být přihlášen.";
        }
        click.activate();
        
    }
    
    this.deactivate = function(){
        if(this.panel)
            this.panel.deactivate();
        this.tabMenuContent = document.getElementById("tabMenuContent"+this.name).style.display="none"; 
        click.deactivate();
    }
    
    this.hosp = function(){
        window.open(adminURL+"page/pg_biotopy/hosp.php?planId="+ligeo.planId +"&dbtab=" + layerName + "&userHash=" + ligeo.userHash,"Hospodářská_hodnota")
        
    }
    this.spol = function(){
        window.open(adminURL+"page/pg_biotopy/spol.php?planId="+ligeo.planId +"&dbtab=" + layerName + "&userHash=" + ligeo.userHash,"Společenská_hodnota")
        
    }
    //////////////////////////////gfi
    
    
    
    
    var ie7 = (typeof document.addEventListener != 'function' && window.XMLHttpRequest) ? true : false;

             document.getElementById("biotopyGfiContent").innerHTML = "<p><b>Klikněte na biotop v mapě, pro získání informací o biotopu</b></p>";
   

    function gfi(x,y){
       
        var bioURL = "../proxy.php?"+adminURL+"/page/pg_biotopy/GFI.php"+"?x="+x+"&y="+y+"&planId=" + ligeo.planId + "&dbtab=" + layerName + "&userHash=" + ligeo.userHash;
        

        
        $.ajax({
            type: 'GET',
            url: bioURL,
            dataType: 'json',
            success: function(data) {
                document.getElementById("biotopyGfiContent").innerHTML= data.name + " "+ data.kod;
                document.getElementById("biotopyGfiContent").innerHTML += "(<a target='_blank' href='http://admin.mapy.mostar.cz/page/pg_biotopy/ek_hodnota.php?planId="+ligeo.planId+"&dbtab="+layerName+"&pgItem="+data.gid+"&userHash="+ligeo.userHash+"#pgItem_"+data.gid+"'>edit</a>)";
                
            },
            data: {},
            async: false
        });
    }
   
    OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {                
        defaultHandlerOptions: {
            'single': true,
            'double': false,
            'pixelTolerance': 0,
            'stopSingle': false,
            'stopDouble': false
        },

        initialize: function(options) {
            this.handlerOptions = OpenLayers.Util.extend(
            {}, this.defaultHandlerOptions
                );
            OpenLayers.Control.prototype.initialize.apply(
                this, arguments
                ); 
            this.handler = new OpenLayers.Handler.Click(
                this, {
                    'click': this.trigger
                }, this.handlerOptions
                );
        }, 

        trigger: function(e) {
            var lonlat = ligeo.ligeoMap.map.getLonLatFromPixel(e.xy);
            var  wgsLonLat = lonlat.transform(new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326"));
            gfi ( wgsLonLat.lat,wgsLonLat.lon);
          
        }

    });
    
   


    
           
    var click = new OpenLayers.Control.Click();
    ligeo.ligeoMap.map.addControl(click);
    
    
//////////////////////////////konec gfi
    
    
    
    
    
    
    
    
} 

