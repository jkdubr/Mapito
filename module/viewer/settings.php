<?php

require_once 'settings/main.php';

$settings->geoserver_url = $LGSettings->geoserver_url;
$settings->lang_default = $LGSettings->lang_default;
$settings->viewer_modules = $LGSettings->module->viewer_modules;
$settings->proxy_url = $LGSettings->admin_url . '/module/proxy/api/';
$settings->admin_url = $LGSettings->admin_url;
$settings->base_layers = $LGSettings->module->base_layers;



echo(json_encode($settings));
?>

