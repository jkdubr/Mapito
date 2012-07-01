<?php

require_once 'main.lib.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GeoserverAPILigeo
 *
 * @author JakubDubrovsky
 */
class GeoserverAPILigeo {

    /**
     *
     * @var GeoserverAPI
     */
    var $api;
    var $wmsUrl;
    

    public function __construct() {
        $this->wmsUrl = $GLOBALS["LGSettings"]->geoserver_url."/wms?";
        $this->api = new GeoserverAPI($GLOBALS["LGSettings"]->geoserver_url . 'rest', $GLOBALS["LGSettings"]->geoserver_user, $GLOBALS["LGSettings"]->geoserver_pass);
    }

}
?>