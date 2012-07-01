<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();

$layerStyleManager=new LGLayerStyleManager($user);
$layerStyleManager->addStyle($_REQUEST);

header("Location: " . $_SERVER["HTTP_REFERER"]);
?>