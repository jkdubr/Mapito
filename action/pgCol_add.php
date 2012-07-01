<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();
$layerColManager = new LGLayerColManager($user);
$layerColManager->addCol($_POST["planId"], $_POST["layerId"], $_POST["colName"], $_POST["colType"]);



header("Location: " . $_SERVER["HTTP_REFERER"]);
?>