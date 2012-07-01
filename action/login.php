<?php
require_once '../lib/php/main.lib.php';

$user = new LGUser();
$user->login($_POST["mail"], $_POST["password"]);


header("Location: ".$_SERVER["HTTP_REFERER"]);

?>
