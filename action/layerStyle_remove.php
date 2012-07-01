<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();


$layerStyleManager=new LGLayerStyleManager($user);
$layerStyleManager->removeStyle($_REQUEST['layerStyleId']);

header("Location: " . $_SERVER["HTTP_REFERER"]);
?>