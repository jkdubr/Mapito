<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();

$layerManager=new LGLayerManager($user);
$layerManager->updateLayersFromPostgis($_GET['planId']);

header("Location: " . $_SERVER["HTTP_REFERER"]);
?>