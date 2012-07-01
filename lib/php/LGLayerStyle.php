<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LGLayerStyle
 *
 * @author jakubdubrovsky
 */
class LGLayerStyle {

    var $title;
    var $layerStyleId;
    var $content;
    var $contentFormated;
    var $public;
    var $userCreatorId;
    var $txt;
    var $parentPublicLayer;

    public function __construct($layerStyleId) {
        $v = mydb_query('select * from  layerStyle where layerStyleId=' . (int) $layerStyleId . ';');
        while ($z = $v->fetch_array()) {
            $this->title = $z['title'];
            $this->layerStyleId = $z['layerStyleId'];
            $this->content = $z['content'];
            $this->contentFormated = $z['contentFormated'];
            $this->txt = $z['txt'];
            $this->parentPublicLayer=$z['parentPublicLayer'];
            $this->userCreatorId=$z['userCreatorId'];
            $this->public=$z['public'];
        }
    }

}

?>