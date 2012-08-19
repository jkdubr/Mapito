<?php

require_once 'LGRESTClient.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GeoserverAPI
 *
 * @author JakubDubrovsky
 */
class GeoserverAPI {

    /**
     *
     * @var LGRestClient
     */
    private $rest;

    public function __construct($root_url, $user_name, $password) {



        $this->rest = new LGRestClient($root_url, $user_name, $password);
    }

    function createWorkspace($name) {
        $temp1->namespace->prefix = $name;
        $temp1->namespace->uri = "http://www.mapito.org/" . $name;

       
        $this->rest->createRequest('namespaces', 'POST', json_encode($temp1), 'json', array("Accept:application/json"));

        $this->rest->sendRequest();
        
        $temp2->workspace->name = $name;
        $this->rest->createRequest('workspaces', 'POST', json_encode($temp2), 'json', array("Accept:application/json"));

        return $this->rest->sendRequest();
    }

    function removeWorkspace($name) {
        $this->rest->createRequest('workspaces/' . $name, 'DELETE');
        return $this->rest->sendRequest();
    }

    function createDatastore($name, $workspaceName) {

        $tempCon->host = $GLOBALS["LGSettings"]->postgis_host;
        $tempCon->port = 5432;
        $tempCon->database = $name;
        $tempCon->user = $GLOBALS["LGSettings"]->postgis_user;
        $tempCon->passwd = $GLOBALS["LGSettings"]->postgis_pass;
        $tempCon->dbtype = "postgis";


        $temp->dataStore->name = $name;
        $temp->dataStore->connectionParameters = $tempCon;

        $this->rest->createRequest('workspaces/' . $workspaceName . '/datastores', 'POST', json_encode($temp), 'json', array("Accept:application/json"));
        return $this->rest->sendRequest();
    }

    function removeDatastore($name, $workspaceName) {

        $this->rest->createRequest('workspaces/' . $workspaceName . '/datastores/' . $name, 'DELETE');
        return $this->rest->sendRequest();
    }

    function getDatastoresInWorkspace($workspaceName) {
        $this->rest->createRequest('workspaces/' . $workspaceName . '/datastores', 'GET', '', '', array("Accept:application/json"));
        return $this->rest->sendRequest();
    }

    function getDatastore($workspaceName, $datastoreName) {
        $this->rest->createRequest('workspaces/' . $workspaceName . '/datastores/' . $datastoreName, 'GET', '', '', array("Accept:application/json"));
        return $this->rest->sendRequest();
    }

    function getWorkspaces() {
        $this->rest->createRequest('workspaces', 'GET', '', '', array("Accept:application/json"));
        return $this->rest->sendRequest();
    }

    function getWorkspace($workspaceName) {
        $this->rest->createRequest('workspaces/' . $workspaceName, 'GET', '', '', array("Accept:application/json"));
        return $this->rest->sendRequest();
    }

    function getLayers($workspaceName, $datastoreName) {
        $this->rest->createRequest('workspaces/' . $workspaceName . '/datastores/' . $datastoreName . '/featuretypes', 'GET', '', '', array("Accept:application/json"));
        return json_decode($this->rest->sendRequest());
    }

    function createLayer($workspaceName, $datastoreName, $layerName, $layerTitle, $layerSrs = "EPSG:4326") {

        $temp->featureType->name = $layerName;
        $temp->featureType->nativeName = $layerName;
        $temp->featureType->title = $layerTitle;
        $temp->featureType->srs = $layerSrs;

        $this->rest->createRequest('workspaces/' . $workspaceName . '/datastores/' . $datastoreName . '/featuretypes', 'POST', json_encode($temp), 'json', array("Accept:application/json"));
        return $this->rest->sendRequest();
    }

    function getLayer($workspaceName, $datastoreName, $layerName) {
        $this->rest->createRequest('workspaces/' . $workspaceName . '/datastores/' . $datastoreName . '/featuretypes/' . $layerName, 'GET', '', '', array("Accept:application/json"));
        return json_decode($this->rest->sendRequest());
    }

    function removeLayer($workspaceName, $datastoreName, $layerName) {

        $this->rest->createRequest('workspaces/' . $workspaceName . '/datastores/' . $datastoreName . '/featuretypes/' . $layerName, 'DELETE');
        return $this->rest->sendRequest();
    }

    function createCoverage($workspaceName, $coverageName) {

        $temp->coverageStore->name = $coverageName;
        $temp->coverageStore->workspace = $workspaceName;
        $temp->coverageStore->enabled = "true";

        $this->rest->createRequest('workspaces/' . $workspaceName . '/coveragestores', 'POST', json_encode($temp), 'json', array("Accept:application/json"));
        $this->rest->sendRequest();

        $temp = "file:/home/ligeo_data/" . $coverageName . ".tif";

        $this->rest->createRequest('workspaces/' . $workspaceName . '/coveragestores/' . $coverageName . '/external.geotiff', 'PUT', $temp, 'json', array("Accept:text/plain"));
        $this->rest->sendRequest();
    }

    function removeCoverage($name, $workspaceName) {

        $this->rest->createRequest('workspaces/' . $workspaceName . '/coveragestores/' . $name . '?recurse=true', 'DELETE');
        return $this->rest->sendRequest();
    }

    function reloadServer() {
        $this->rest->createRequest('reload', "POST");
        return $this->rest->sendRequest();
    }

}

?>