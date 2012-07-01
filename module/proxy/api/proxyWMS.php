<?php

require_once '../lib/php/main.lib.php';

$onlineresource = split("\?", $_SERVER['QUERY_STRING'], 1);

$onlineresource = $onlineresource[0];



$layerName = split(":", $_GET["LAYERS"]);
if (count($layerName) != 2)
    exit("Chyba v parametru LAYERS");


$v = mydb_query('select planId, private,layerId,layerStyleId from layer where namespace="' . secure($layerName[0]) . '" and name = "' . secure($layerName[1]) . '"');
$z = $v->fetch_array();


if ($z["private"]) {
    if ($_SESSION["userId"]) {
        $v1 = mydb_query('select 1 from privilege where userId=' . (int) $_SESSION["userId"] . ' and planId=' . (int) $z["planId"] . ' ');
        if (!mysqli_num_rows($v1))
            exit("no privilege");
    } else {
        exit("no user");
    }
} else {
    //ok
}

if ($z["layerStyleId"])
    $onlineresource = 'SLD=' . urlencode($LGSettings->admin_url . '/api/sld.php?layerId=' . $z["layerId"]) . '&' . $onlineresource;




$onlineresource = $LGSettings->geoserver_url . '/wms?' . $onlineresource;


//header('Content-Type: ' . ($_GET['FORMAT'] ? $_GET['FORMAT'] : 'image/png'));

echo send_request($onlineresource);

function send_request($onlineresource) {
    file_put_contents("log_wms.txt", "$onlineresource\n", FILE_APPEND);
    
    $ch = curl_init();
    $timeout = 0; // set to zero for no timeout
    // fix to allow HTTPS connections with incorrect certificates
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_USERPWD, $GLOBALS["LGSettings"]->geoserver_user . ':' . $GLOBALS["LGSettings"]->geoserver_pass);
    curl_setopt($ch, CURLOPT_URL, $onlineresource);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    // curl_setopt ($ch, CURLOPT_ENCODING , "gzip, deflate");

    $file_contents = curl_exec($ch);

    return $file_contents;
}

?>