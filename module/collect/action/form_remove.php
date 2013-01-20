<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();
$formManager = new LGFormManager($user);
$formManager->removeForm($_GET['formId']);


header("Location: " . $_SERVER["HTTP_REFERER"]);
?>