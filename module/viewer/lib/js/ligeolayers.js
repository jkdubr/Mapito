function LiGeolayers(ligeo){
    this.name = "layers";
    this.tabMenuContent = "";
    
    $("#tabMenuContent").append('<div class="tabMenuContent" id="tabMenuContentlayers"><div><strong>Base layers</strong><form id="baseLayers"></form><br/><br/></div><label for="LayerTreeSearch">Search:</label>  <input type="search" id="LayerTreeSearch" placeholder="Search"></input><br/><br/><div id="LayerTree" class="demo">kk</div></div>');
    
    
    
    
    
    
    
    
    
    
    $("#LayerTree").jstree({
        "json_data" : {
            "data" :ligeo.ligeoMap.data
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
        "plugins" : ["themes","ui","crrm","hotkeys","search","types","json_data","checkbox"]
    }).bind("loaded.jstree", function () {
        ;
        }).bind("check_node.jstree uncheck_node.jstree", function () {
         
                   
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
    }); 
    
    
    
    
    
    
    
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









