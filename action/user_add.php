<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();
$userManager= new LGUserManager($user);
$userManager->addUser($_POST);

header("Location: ".$_SERVER["HTTP_REFERER"]);
?>