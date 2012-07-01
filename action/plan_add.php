<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();
$planManager = new LGPlanManager($user);
$planManager->addPlan($_POST);


header("Location: ".$_SERVER["HTTP_REFERER"]);
?>