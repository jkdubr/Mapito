<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();
$layerManager=new LGLayerManager($user);
$layerManager->addLayerPublicToPlan($_POST['layerId'], $_POST['planId']);

header("Location: " . $_SERVER["HTTP_REFERER"]);
?>