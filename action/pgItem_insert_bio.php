<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();

$plan = new LGPlan($_GET["planId"]);



if ($user->getPrivilegeForPlan($plan->planId) > 1) {
    $dbname = 'ligeo_' . $plan->name;

    $pgsql_conn = pg_connect("host=" . $GLOBALS["LGSettings"]->postgis_host . " dbname=" . $dbname . " user=" . $GLOBALS["LGSettings"]->postgis_user . " password=" . $GLOBALS["LGSettings"]->postgis_pass . "");
    if (!$pgsql_conn) {
        die("Error in connection: " . pg_last_error());
    }


    
    
    
    $sql = 'INSERT INTO ' . $_GET["dbtab"] . ' ("group",name,editable_svd,editable_title,hyp_max)' . ' VALUES (\'' . $_GET["group"] . '\', \'ostatni\',1,1,(SELECT hyp_max FROM '. $_GET["dbtab"] . ' WHERE  "group" = \'' . $_GET["group"] .'\'  GROUP BY hyp_max))'. ' ;';

    $results = pg_query($pgsql_conn, $sql);
 
//vypocitej update


    pg_close($pgsql_conn);
}

header("Location: " . $_SERVER["HTTP_REFERER"]);
?>