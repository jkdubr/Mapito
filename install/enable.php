<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../lib/php/main.lib.php';
$user = new LGUser();
if ($user->isPrivilegeSuperAdmin()) {

    $LGInstall = new LGInstall();
    $LGInstall->enable();
    ?>
    Setup page is enabled, you can <a href="settings.php">configure Mapito</a> 
    <?php
}
?>
