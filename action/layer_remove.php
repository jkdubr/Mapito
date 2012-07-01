<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();

$layerManager=new LGLayerManager($user);
$layerManager->removeLayer($_GET["layerId"]);

header("Location: ".$_SERVER["HTTP_REFERER"]);
?>