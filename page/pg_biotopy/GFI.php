<?php

require_once '../../lib/php/main.lib.php';

//returns an array with infos of every field in the table (name, type, length, size)

$user = new LGUser();
$planManager = new LGPlanManager($user);
$layerManager = new LGLayerManager($user);
$planId = (int) $_GET["planId"];
$plan = new LGPlan($planId);
if ($user->getPrivilegeForPlan($plan->planId) > 1)
    $dbname = 'ligeo_' . $plan->name;
$dbtab = $_GET["dbtab"];


if ($plan->name) {

    $pgsql_conn = pg_connect("host=" . $GLOBALS["LGSettings"]->postgis_host . " dbname=" . $dbname . " user=" . $GLOBALS["LGSettings"]->postgis_user . " password=" . $GLOBALS["LGSettings"]->postgis_pass . "");
    if (!$pgsql_conn) {
        die("Error in connection: " . pg_last_error());
    }
    pg_query($pgsql_conn, "SET NAMES 'utf8'");
}

$x = $_GET["x"];
$y = $_GET["y"];



$pgsql_conn = pg_connect("host=" . $GLOBALS["LGSettings"]->postgis_host . " dbname=" . $dbname . " user=" . $GLOBALS["LGSettings"]->postgis_user . " password=" . $GLOBALS["LGSettings"]->postgis_pass . "");
if (!$pgsql_conn) {
    die("Error in connection: " . pg_last_error());
}
pg_query($pgsql_conn, "SET NAMES 'utf8'");
$layerId = $layerManager->getLayerIdByPlanAndLayerName($planId, $dbtab);
$layer = new LGLayer($layerId);
$table_info = pg_tableInfo($dbtab, $pgsql_conn);
$sql = 'SELECT gid,biotop,kodbio FROM ' . $dbtab . ' WHERE St_Intersects(ST_SetSRID(ST_point(' . $y . ',' . $x . '),4326),' . $dbtab . ' .the_geom);';
$results = pg_query($pgsql_conn, $sql);
//$row = pg_fetch_row($results);

for ($lt = 0; $lt < pg_num_rows($results); $lt++) {
    $temp["gid"] = pg_fetch_result($results, $lt, "gid");
    $temp["name"] = pg_fetch_result($results, $lt, "biotop");
    $temp["kod"] = pg_fetch_result($results, $lt, "kodbio");
}



//echo $sql;



echo json_encode($temp);
//echo $row[0];
//echo $row[1];
pg_close($pgsql_conn);
?>

