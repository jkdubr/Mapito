/*
 *je li typ vrstvy vektor, v legendě se objeví obrázek, který odpovídá zvolenému stylu
 *je li typ vrstvy rastr, v legendě se objeví odkaz na přiložený obrázek legendy
*/

function LiGeolegend(ligeo){
    this.name = "legend";
    
    this.title = function(){
        //todo taday zjistim lokalizaci 
        return "Legend";
    }
    
    this.toString = function(){
        return "";
    }
    
    this.activate = function(){
        this.tabMenuContent = document.getElementById("tabMenuContent"+this.name).style.display="block";

        var html ="";
       
        for(var i = 0; i < ligeo.ligeoMap.planLayers.length; i++) 
        {
           
            if (ligeo.ligeoMap.planLayers[i].metadata.inLegend!=1)
            {
                nic = "";
            
                html += nic;
            }
           
            else if (ligeo.ligeoMap.planLayers[i].metadata.type=="RASTR")
            {
                legendImage1 = "<a href='../img/legend/" + ligeo.ligeoMap.planLayers[i].metadata.layerId +".png' target='_blank'>Legenda</a>";
                legendName1 = ligeo.ligeoMap.planLayers[i].name;
                html += "<tr><td>"+legendImage1 + "</td><td> " + legendName1+ "</td></tr>";
            }
       

            else
            {
                legendImage = "<img src='../img/style/" + ligeo.ligeoMap.planLayers[i].metadata.layerStyleId +".png' alt='legenda'>";
                legendName = ligeo.ligeoMap.planLayers[i].name;
                html += "<tr><td>"+legendImage + "</td><td>" + legendName+ "</td></tr>";
      
            }

        }
        document.getElementById("tabMenuContent"+this.name).innerHTML="<table>"+html+"</table>";
    }
    
    this.deactivate = function(){
        this.tabMenuContent = document.getElementById("tabMenuContent"+this.name).style.display="none";
     
    }
}
