<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();
$planManager = new LGPlanManager($user);
$planManager->removePlan($_GET["planId"]);

header("Location: ".$_SERVER["HTTP_REFERER"]);
?>