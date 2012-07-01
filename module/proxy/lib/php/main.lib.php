<?php


$cd = getcwd();
chdir(dirname(__FILE__));
require_once '../../settings/main.php';
chdir($cd);
$GLOBALS["LGSettings"] = new LGSettings();

require_once 'LGConnect.php';

function __autoload($name) {
    $cd = getcwd();
    chdir(dirname(__FILE__));
    require_once './' . $name . '.php';
    chdir($cd);
}

function secure($txt, $type = "string") {
    if ($type == "bigint" || $type == "int" || $type == "smallint" || $type == "tinyint") {
        return (int) $txt;
    } elseif ($type == "decimal" || $type == "float") {
        return (float) $txt;
    } else {
        return htmlspecialchars($txt, ENT_QUOTES);
    }
}

?>