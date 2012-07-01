<?php

require_once '../lib/php/main.lib.php';


$api = new LGAPI();

$api->requestJSON($_REQUEST["json"]);

echo($api->result());

//print_r($_SESSION);

?>