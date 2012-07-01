<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LGLayer
 *
 * @author JakubDubrovsky
 */
class LGLayer {

    var $title;
    var $name;
    var $url;
    var $namespace;
    var $format;
    var $opacity;
    var $palete;
    var $type;
    var $legendImageUrl;
    var $txt;
    var $isInDb;
    var $layerPublicId;
    var $layerFolderId;
    var $planId;
    var $layerId;
    var $rank;
    var $private;
    var $queryable;
    var $visibility;
    var $style;
    var $isInLegend;
    var $isActive; //je zobrazovatelna na mape
    var $printable;
    var $new;
    var $layerStyleId;
    var $isLocked;
    var $isLockedForGeometry;
    var $isPublic;
    var $transparent;

    public function __construct($layerId) {
        $v = mydb_query("select * from layer where layerId=" . (int) $layerId . ";");
        if ($z = $v->fetch_array()) {
            $this->title = $z["title"];
            $this->name = $z["name"];
            $this->url = $z["url"];
            $this->namespace = $z["namespace"];
            $this->format = $z["format"];
            $this->opacity = $z["opacity"];
            $this->transparent = $z["transparent"];
            $this->palete = $z["palete"];
            $this->type = $z["type"];
            $this->legendImageUrl = $z["legendImageUrl"];
            $this->txt = $z["txt"];
            $this->isInDb = $z["isInDb"];
            $this->layerPublicId = $z["layerPublicId"];
            $this->layerFolderId = $z["layerFolderId"];
            $this->planId = $z["planId"];
            $this->layerId = $layerId;
            $this->rank = $z["rank"];
            $this->private = $z["private"];


            $this->queryable = $z["queryable"];
            $this->visibility = $z["visibility"];
            $this->isInLegend = $z["isInLegend"];
            $this->printable = $z["printable"];

            $this->isActive = $z["isActive"];
            $this->isLocked = $z["isLocked"];
            $this->isLockedForGeometry = $z["isLockedForGeometry"];

            $this->new = $z["new"];

            $this->layerStyleId = $z["layerStyleId"];

            $this->isPublic = (bool) $z["planId"];
        }
    }

}

?>