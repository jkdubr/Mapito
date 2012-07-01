<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class LGSettingsModule {

    //var $viewer_modules = array("layers", "info", "legend", "measurement","biotopy", "editor", "login");
    var $viewer_modules = array("layers", "info", "legend", "print", "measurement", "editor", "login");
    var $base_layers = array("bLgclasic","bLgsat", "bLwhite");

    // var $viewer_modules = array("layers","info","legend","measurement","editor","login");

    public function __construct() {
        ;
    }

}

?>
