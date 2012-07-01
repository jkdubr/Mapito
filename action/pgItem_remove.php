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

    $sql = 'delete from ' . secure($_GET["dbtab"]) . '  where gid = ' . (int) $_GET["gid"] . ' ;';

    $results = pg_query($pgsql_conn, $sql);


    pg_close($pgsql_conn);
}

header("Location: " . $_SERVER["HTTP_REFERER"]);
?>