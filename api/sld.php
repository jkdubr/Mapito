<?php
/**
 * využívá se pro SLD
 */
require_once '../lib/php/main.lib.php';


$api = new LGAPI();


echo($api->styleSld($_REQUEST["layerId"]));

?>