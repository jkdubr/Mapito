
function LiGeoabout(ligeo){
    this.name = "about";
    
    document.getElementById("tabMenuContentabout").innerHTML = ligeo.txt;
    
    this.title = function(){
        //todo taday zjistim lokalizaci 
        return "O mapě";
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
}