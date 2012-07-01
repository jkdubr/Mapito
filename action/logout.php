<?php
require_once '../lib/php/main.lib.php';

$user = new LGUser();
$user->logout();
header("Location: ../index.php");
        
?>
