<?php
require_once 'lib/php/main.lib.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$rawpostdata = file_get_contents("php://input");

echo send_request($GLOBALS["LGSettings"]->geoserver_url."/wfs?", $rawpostdata);

function send_request($onlineresource, $data) {

    
        $curl = curl_init($onlineresource); 
        
        curl_setopt($curl, CURLOPT_URL, $onlineresource); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, array( 
                'POST HTTP/1.0', 
                'Content-type: text/xml;charset="UTF-8"', 
                'Accept: text/xml', 
                'Cache-Control: no-cache', 
                'Pragma: no-cache' 
            )); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
        curl_setopt($curl, CURLOPT_POST, TRUE); 
        
        
       
       
       
        $theData = curl_exec($curl); 
        
        curl_close($curl); 

        return $theData; 
        
        
//    file_put_contents("log.txt", $onlineresource."-".$GLOBALS["LGSettings"]->geoserver_user."-$LGSettings->geoserver_pass\n", FILE_APPEND);

    $ch = curl_init();
    $timeout = 0; // set to zero for no timeout
    // fix to allow HTTPS connections with incorrect certificates
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
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