<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();


$layerColManager = new LGLayerColManager($user);
$layerColManager->removeCol($_POST["planId"], $_POST["layerId"], $_POST["colName"]);

header("Location: " . $_SERVER["HTTP_REFERER"]);
?>