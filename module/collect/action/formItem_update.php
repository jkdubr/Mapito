<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();


$form=new LGForm($_POST['formId']);
$form->updateFormItem($_POST);

header("Location: " . $_SERVER["HTTP_REFERER"]);
?>