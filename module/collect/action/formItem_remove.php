<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();

$form = new LGForm($_GET['formId']);
$form->removeFormItem($_GET['formItemId']);

header("Location: " . $_SERVER["HTTP_REFERER"]);
?>