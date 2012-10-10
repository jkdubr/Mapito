#### Mapito API description

API URL: <server URL>/api?json=[JSON format]

JSON format: 
{"request":"[function]","userHash":[user hash],"param":{[params and values]}}

function: function name
user hash: user's session id
params and values: list "param name":"value","param name2":"value2"

example: http://server.org/api/?json={"request":"layersForJSTree","userHash":"d3d6bf4f91c68668b36df1ab45297b19","param":{"planId":9}}

Output is mostly JSON format
        
## Layer functions

### layer.forJSTree
params: planId
    
Return JSON info about plan. JSON is optimalized for JS library JSTree.
    
###layer.list
param: planId
        
Return JSON info about plan.
        
###layer.basic
param: layerIds
        
Return basic information about layer. 
        
## Form functions

###form.detail
param: formId
        
Return information about form.
        

###form.list
params: none
        
Return list of user's form with basic description. 
        
###form.byPlan.list
param: planId
        
Return list of the layers for plan
        
###form.collectPOST
param: formId
        
Save date from the form to the PostGIS database.
        
###form.upload
param: formId
        
Upload file into form's layer folder. Return URL of the uploaded file.
        
##User functions
###user.login
params: mail,password
        
Sign in user. Return [user hash].
        
###user.logout
params: none
        
Log out user.
        
###user.hash
params: none

## Layer`s style
###style.sld
param: layerId
        
Return SLD style for the layer.
        
##Plan
###plan.list
params: none
        
Return list of user's plan with basic description. 
        
###plan.public.list
params: none
        
Return list of all public plans with basic description. 
        
###plan.public.kml
params: none
        
Return list of all public plans with basic description in KML.
        
###plan.detail
param->name
        
Return detail information about plan.