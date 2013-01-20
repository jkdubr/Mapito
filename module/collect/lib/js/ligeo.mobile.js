  
var formList;
var formId;
var formDetail;

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

var userHash = getCookie("userHash");

        


//   var json1 = '{"request":"user.hash","userHash":"'+getCookie("userHash")+'"}';

// var json2 = '{"request":"plan.detail","userHash":"'+this.userHash+'","param":{"name":"'+this.page+'"}}';



function formSelectboxDisplay(){
    
    if(userHash){
    
        var json = '{"request":"form.list","userHash":"'+userHash+'"}';
        $.post("../../api/", {
            "json": json
        },
        function(data){
            if (data.err) {
                alert("Chyba");
            } else {
                formList = data;
            
                var html ="";      
                for(var j = 0; j<formList.length; j++){
                    html += '<li><a onclick="formDetailReload('+formList[j].formId+')">'+formList[j].title+'</a> <a href="map.html?layerIds='+formList[j].layerId+'" data-ajax="false"></a></li>';
    
                }
                document.getElementById("formListSelectBox").innerHTML = html;
                //$(html).appendTo("#formListSelectBox");
    
                $('#formListSelectBox').listview('refresh');
            }
        }, "json");
    }  
}

function formDetailSubmit(form){
    
    if (navigator.geolocation) 
    {
        navigator.geolocation.getCurrentPosition( 
            
            function (position) {  
                
                form.ligeoLng.value=position.coords.longitude;
                form.ligeoLat.value=position.coords.latitude;
                form.submit();
            
            
            }, 
            // next function is the error callback
            function (error)
            {
                switch(error.code) 
                {
                    case error.TIMEOUT:
                        alert ('Timeout');
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert ('Position unavailable');
                        break;
                    case error.PERMISSION_DENIED:
                        alert ('Permission denied');
                        break;
                    case error.UNKNOWN_ERROR:
                        alert ('Unknown error');
                        break;
                }
            }
            );
    }
    
    return false;
}

function formDetailDisplay(){
    
    
    var html='<input type="hidden" name="json" value=\'{"request":"form.collectPOST","userHash":"'+userHash+'","param":{"formId":'+formId+'}}\' />';
    
    for(var i=0;i<formDetail.length;i++){
        if(formDetail[i].type == "slider"){
            
            html += formDetailSlider(formDetail[i]);   
        } else if(formDetail[i].type == "flipSwitch")
            html += formDetailFlipSwitch(formDetail[i]);
        else if(formDetail[i].type == "selectbox")
            html += formDetailSelectbox(formDetail[i]);
        else if(formDetail[i].type == "checkbox")
            html += formDetailCheckbox(formDetail[i]);
        else if(formDetail[i].type == "radio")
            html += formDetailRadiobox(formDetail[i]);
        else if(formDetail[i].type == "textfield")
            html += formDetailTextfield(formDetail[i]);
        else{
            html += formDetailInput(formDetail[i]);    
        }
    }
    
    document.getElementById("formDetailForm").innerHTML = html;
    //$(html).appendTo( "#formDetailForm");
    $("#pageFormDetail" ).trigger('create');

}

function formDetailInput(item){
    return  '<input type="'+item.type+'" '+(item.required!="0" ? 'required' : '')+' placeholder="'+item.placeholder+'" name="'+item.name+'" value="'+item['default']+'"  />';    
}

function formDetailSlider(item){
    return '<label for="ligeoForm_'+item.name+'">'+item.title+'</label><input type="range" name="'+item.name+'" id="ligeoForm_'+item.name+'" value="'+item['default']+'" min="'+item.options.min+'" max="'+item.options.max+'" step="'+item.options.step+'"  />'
}

function formDetailFlipSwitch(item){
    var html = '<label for="ligeoForm_'+item.name+'">'+item.title+'</label><select name="'+item.name+'" id="ligeoForm_'+item.name+'"  data-role="slider">'
    
    for(var i=0;i<item.options.options.length; i++){
        
        html += '<option value="'+item.options.options[i].value+'">'+item.options.options[i].title+'</option>'
    }
    
    html += '</select>';
    
    return html;
}

function formDetailSelectbox(item){
    var html = '<label for="ligeoForm_'+item.name+'">'+item.title+'</label><select name="'+item.name+'" id="ligeoForm_'+item.name+'">'
    
    for(var i=0;i<item.options.options.length; i++){
        
        html += '<option value="'+item.options.options[i].value+'">'+item.options.options[i].title+'</option>'
    }
    
    html += '</select>';
    
    return html;
}

function formDetailCheckbox(item){
    return '<input type="checkbox"  name="'+item.name+'" '+(item['default'] ? 'checked' : '')+' id="ligeoForm_'+item.name+'" /><label for="ligeoForm_'+item.name+'">'+item.title+'</label>';
}

function formDetailRadiobox(item){
    var html = '<fieldset data-role="controlgroup" '+(item.options.type=="horizontal" ? 'data-type="horizontal"' : '')+'><legend>'+item.title+'</legend>';
    
    for(var i=0;i<item.options.options.length; i++){
        
        html += '<input type="radio" id="'+item.name+i+'"  value="'+item.options.options[i].value+'" name="'+item.name+'"><label for="'+item.name+i+'">'+item.options.options[i].title+'</label>';
    }
    
    html += '</fieldset>';
    
    return html;    
}

function formDetailTextfield(item){
    return '<label>'+item.title+'</label> <textarea placeholder="'+item.placeholder+'" name="'+item.name+'">'+item.value+'</textarea>';
}

function formDetailBack(){
    
    document.getElementById("pageFormList").style.display="block";
    document.getElementById("pageFormDetail").style.display="none";
    document.getElementById("pageHeaderButtonBack").style.display="none";

}

function formDetailReload(tformId){
    if(tformId){
        formId = tformId;
        var json = '{"request":"form.detail","userHash":"'+userHash+'","param":{"formId":'+formId+'}}';
        $.post("../../api/", {
            "json": json
        },
        function(data){
            if (data.err) {
                alert("Chyba");
            } else {
                formDetail = data;
                
                document.getElementById("pageFormList").style.display="none";
                document.getElementById("pageFormDetail").style.display="block";
                document.getElementById("pageHeaderButtonBack").style.display="block";
                formDetailDisplay();
            
            }
        }, "json");  
    }    
}

function userLogin(tlogin,tpass){
    var json = '{"request":"user.login","param":{"mail":"'+tlogin+'","password":"'+tpass+'"}}';
    $.post("../../api/", {
        "json": json
    },
    function(data){
        if (data.err) {
            alert("Chyba v přihlašování");
        } else {
            
            setCookie("userHash", data.userHash, 31);
            
            document.location = "formList.html";
        }
    }, "json");
    return false;
}

function logout(){
    var json = '{"request":"user.logout","userHash":"'+userHash+'"}';
    $.post("../../api/", {
        "json": json
    },
    function(data){
        userHash="";
        setCookie("userHash", "", 31);
        $.mobile.changePage("index.html", null, false, true );
    }, "json");        
}

function logout(){
    var json = '{"request":"user.logout","userHash":"'+this.userHash+'"}';
    $.post("../../api/", {
        "json": json
    },
    function(data){
        this.userHash="";
        setCookie("userHash", "", 31);
        window.location.reload();
    }, "json");        
}
            
            
    
    
            /*
            var json3 = '{"request":"layer.forJSTree","userHash":"'+ligeo.userHash+'","param":{"planId":'+ligeo.planId+'}}';
    
            var json4 = '{"request":"form.list","userHash":"'+ligeo.userHash+'"}';
    
            var json5 = '{"request":"form.detail","userHash":"'+ligeo.userHash+'","param":{"formId":'+ligeo.planId+'}}';
             */