<!DOCTYPE html>
<html>
    <head>
        <title>LiGeo | API</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>

        <h2>API description</h2>

        <p>API URL: http://ligeo.mostar.cz/admin/api?json=[JSON format]</p>
        <p>
            JSON format: 
            {"request":"[function]","userHash":[user hash],"param":{[params and values]}}
        </p>
        <p>function: function name</p>
        <p>user hash: user's session id</p>
        <p>params and values: list "param name":"value","param name2":"value2",</p>
        <blockquote>
            example: http://ligeo.mostar.cz/admin/api/?json={%22request%22:%22layersForJSTree%22,%22userHash%22:%22d3d6bf4f91c68668b36df1ab45297b19%22,%22param%22:{%22planId%22:9}}
        </blockquote>
        <p>Output is mostly in JSON format</p>
        <h3>Functions</h3>

        <h4>layer.forJSTree</h4>
        params: planId
        <p>
            Return JSON info about plan. JSON is optimalized for JS library JSTree.
        </p>

        <h4>layer.list</h4>
        param: planId
        <p>
            Return JSON info about plan.
        </p>

        <h4>layer.basic</h4>
        param: layerIds
        <p>
            Return basic information about layer. 
        </p>

        <h4>form.detail</h4>
        param: formId
        <p>
            Return information about form.
        </p>

        <h4>form.list</h4>
        params: none
        <p>
            Return list of user's form with basic description. 
        </p>

        <h4>form.byPlan.list</h4>
        param: planId
        <p>
            Return list of the layers for plan
        </p>

        <h4>form.collectPOST</h4>
        param: formId
        <p>
            Save date from the form to the PostGIS database.
        </p>

        <h4>form.upload</h4>
        param: formId
        <p>
            Upload file into form's layer folder. Return URL of the uploaded file.
        </p>

        <h4>user.login</h4>
        params: mail,password
        <p>
            Sign in user. Return [user hash].
        </p>


        <h4>user.logout</h4>
        params: none
        <p>
            Log out user.
        </p>

        <h4>user.hash</h4>
        params: none


        <h4>style.sld</h4>
        param: layerId
        <p>
            Return SLD style for the layer.
        </p>

        <h4>plan.list</h4>
        params: none
        <p>
            Return list of user's plan with basic description. 
        </p>

        <h4>plan.public.list</h4>
        params: none
        <p>
            Return list of all public plans with basic description. 
        </p>

        <h4>plan.public.kml</h4>
        params: none
        <p>
            Return list of all public plans with basic description in KML.
        </p>

        <h4>plan.detail</h4>
        param->name
        <p>
            Return detail information about plan.
        </p>


    </body>
</html>
