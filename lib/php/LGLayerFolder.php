<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LGLayerFolder
 *
 * @author JakubDubrovsky
 */
class LGLayerFolder {

    var $title;
    var $txt;
    var $planId;
    var $layerFolderId;
    var $layers;
    var $basic;

    /**
     *
     * @param type $layerFolderId
     * @param type $private defaultne da vsechny vrstvy, pokud private=false, da jen verejne
     */
    public function __construct($layerFolderId, $private=true) {
        $v = mydb_query("select * from layerFolder where layerFolderId=" . (int) $layerFolderId . ";");
        if ($z = $v->fetch_array()) {
            $this->title = $z["title"];
            $this->txt = $z["txt"];
            $this->planId = $z["planId"];
            $this->basic = $z["basic"];
            
            $this->layerFolderId = $layerFolderId;
            $this->layers = array();

            $v1 = mydb_query("select layerId from layer where layerFolderId=" . (int) $layerFolderId . "  " . ($private ? "" : " and private=0") . " ;");
           // $v1 = mydb_query("select layerId from layer where layerFolderId=" . (int) $layerFolderId . " ;");
            while ($z1 = $v1->fetch_array()) {
                $this->layers[] = $z1["layerId"];
            }
        }
    }

}

?>