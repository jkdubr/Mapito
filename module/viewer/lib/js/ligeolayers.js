function LiGeolayers(ligeo){
    this.name = "layers";
    this.tabMenuContent = "";
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









