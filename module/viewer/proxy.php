<?php

require_once 'settings/main.php';

$onlineresource = split("\?", $_SERVER['QUERY_STRING'], 1);

echo send_request($onlineresource[0]);

function send_request($onlineresource) {
    $ch = curl_init();
    $timeout = 5; // set to zero for no timeout
    // fix to allow HTTPS connections with incorrect certificates
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    curl_setopt($ch, CURLOPT_URL, $onlineresource);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    // curl_setopt ($ch, CURLOPT_ENCODING , "gzip, deflate");

    $file_contents = curl_exec($ch);

    return $file_contents;
}

?> 