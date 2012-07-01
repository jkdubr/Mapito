<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LGPlan
 *
 * @author JakubDubrovsky
 */
class LGPlan {

    var $name;
    var $txt;
    var $title;
    var $planId;
    var $mapCenterLat;
    var $mapCenterLon;
    var $mapZoom;
    var $private;

    public function __construct($planId) {
        $v = mydb_query("select * from plan where planId=" . (int) $planId . ";");

        while ($z = $v->fetch_array()) {
            $this->name = $z["name"];
            $this->txt = $z["txt"];
            $this->title = $z["title"];
            $this->mapCenterLat = $z["mapCenterLat"];
            $this->mapCenterLon = $z["mapCenterLon"];
            $this->mapZoom = $z['mapZoom'];
            $this->private = $z['private'];

            $this->planId = $planId;
        }
    }

    function isSplashScreen() {
        $cd = getcwd();
        chdir(dirname(__FILE__));
        $fileName = '../../module/viewer/' . $this->name . '/splashscreen.png';
        $temp=file_exists($fileName);
        chdir($cd);

        return $temp;
    }

}

?>