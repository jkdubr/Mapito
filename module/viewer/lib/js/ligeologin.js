function LiGeologin(ligeo){
    var ligeo = ligeo;
    this.name = "login";
    
    this.title = function(){
        //todo taday zjistim lokalizaci 
        return "Login";
    }
    
    this.toString = function(){
        return "";
    }
    
    this.activate = function(){
        document.getElementById("tabMenuContent"+this.name).style.display="block";
        if(ligeo.userHash ){
            //alert(ligeo.userName+ligeo.userHash);
            document.getElementById("tabMenuContent"+this.name).innerHTML = "<p>"+ligeo.userName+" ("+(ligeo.privilege ==1 ? "viewing" : (ligeo.privilege==2 ? "editation" : (ligeo.privilege==3 ? "administration" : "")))+")</p><input type='button' onClick='ligeo.logout()'  value='Logout'>"
        }
    }
    
    this.deactivate = function(){
        this.tabMenuContent = document.getElementById("tabMenuContent"+this.name).style.display="none";    
    }
}