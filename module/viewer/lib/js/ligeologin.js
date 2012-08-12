function LiGeologin(ligeo){
    var ligeo = ligeo;
    this.name = "login";
    
    $("#tabMenuContent").append('<div class="tabMenuContent" id="tabMenuContentlogin"><fieldset><form action="" method="GET" onsubmit="return ligeo.login(document.getElementById(\'f_login\').value, document.getElementById(\'f_password\').value);">  <label for="f_login">Login</label><br /><input type="text" placeholder="Login" id="f_login" name="login" style="width: 150px"></input><br /><label for="f_password">Password</label><br /><input type="password" placeholder="Password" id="f_password" name="password" style="width: 150px"></input><br /><input type="submit" value="Login"  style="width: 150px"></input></form></fieldset></div>');
    
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