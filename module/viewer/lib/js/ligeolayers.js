function LiGeolayers(ligeo){
    this.name = "layers";
    this.tabMenuContent = "";
    
    $("#tabMenuContent").append('<div class="tabMenuContent" id="tabMenuContentlayers"><div><strong>Base layers</strong><form id="baseLayers"></form><br/><br/></div><label for="LayerTreeSearch">Search:</label>  <input type="search" id="LayerTreeSearch" placeholder="Search"></input><br/><br/><div id="LayerTree" class="demo"></div></div>');
    
    this.title = function(){
        //todo taday zjistim lokalizaci 
        return "Layers";
    }
    
    this.toString = function(){
        return "";
    }
    
    this.activate = function(){
        this.tabMenuContent = document.getElementById("tabMenuContent"+this.name).style.display="block";
    }
    
    this.deactivate = function(){
        this.tabMenuContent = document.getElementById("tabMenuContent"+this.name).style.display="none";
    }
/*
    ligeo.ligeoMap.map.addControl(new OpenLayers.Control.LayerSwitcher({
        div:document.getElementById('tabMenuContent'+this.name)
    }
    ));
*/
}









