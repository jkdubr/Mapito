<?php

require_once '../lib/php/LGInstall.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$LGInstall = new LGInstall();
if ($LGInstall->isEnabled()){
    $LGInstall->createSettings($_POST);
    $LGInstall->installMySQL();
    $LGInstall->installModules();
    $LGInstall->sendMailAfterInstall($_POST["mail"],$_POST["admin_url"]);
    header('Location: ready.php');
   
}else{
    exit("Editing is disabled. You can enabled it in settings.");
}
?>