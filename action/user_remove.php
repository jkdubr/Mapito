<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();
$userManager = new LGUserManager($user);
$userManager->removeUser($_GET["userId"]);

header("Location: ".$_SERVER["HTTP_REFERER"]);
?>