<?php

/* set the cache limiter to 'private' 

  session_cache_limiter('private');
  $cache_limiter = session_cache_limiter();

  /* set the cache expire to 30 minutes
  session_cache_expire(30);
  $cache_expire = session_cache_expire();
 */
//session_set_cookie_params('3600', '/', '.mapy.mostar.cz');

session_start();

$cd = getcwd();
chdir(dirname(__FILE__));
if(!is_file('../../settings/main.php'))
    header ('Location: install/settings.php');
require_once '../../settings/main.php';
require_once 'LGConnect.php';
chdir($cd);

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