<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LGLayerCol
 *
 * @author jakubdubrovsky
 */
class LGLayerCol {

    //put your code here
    var $title;
    var $name;
    var $type;
    var $length;
    var $layerId;

    public function __construct($layerColId) {
        $v = mydb_query('select * from layerCol where layerColId=' . (int) $layerColId . ';');
        while ($z = $v->fetch_array()) {
            $this->title = $z["title"];
            $this->name = $z["name"];
            $this->type = $z["type"];
            $this->length = $z["length"];
            $this->layerId = $layerId;
        }
    }

}

?>
