<?php

class LGSettingsModule {

    var $viewer_modules;
    var $base_layers = array("bLgsat", "bLgclasic", "bLwhite");

    public function __construct() {
        $cd = getcwd();
        chdir(dirname(__FILE__));

        $this->viewer_modules = split(",", file_get_contents("mapito_viewer_modules"));

        chdir($cd);
    }

}

?>