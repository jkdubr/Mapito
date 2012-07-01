<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();
$layerManager = new LGLayerManager($user);
$layerManager->removeLayerFolder($_GET["layerFolderId"]);

header("Location: ".$_SERVER["HTTP_REFERER"]);
?>