<?php

require_once '../lib/php/main.lib.php';

$user = new LGUser();


$plan = new LGPlan($_POST["planId"]);
if ($user->getPrivilegeForPlan($plan->planId) > 1) {
    $dbname = 'ligeo_' . $plan->name;


    $pgsql_conn = pg_connect("host=" . $GLOBALS["LGSettings"]->postgis_host . " dbname=" . $dbname . " user=" . $GLOBALS["LGSettings"]->postgis_user . " password=" . $GLOBALS["LGSettings"]->postgis_pass . "");
    if (!$pgsql_conn) {
        die("Error in connection: " . pg_last_error());
    }



    $v = mydb_query('select * from layerWPS where layerId =' . $plan->planId . ' ');
    $z = $v->fetch_array();

    $wps = json_encode($z['content']);


    $results = pg_query($pgsql_conn, 'SELECT * FROM  ' . secure($_POST["dbtab"]) . ' LIMIT 1 ;');
    $sqlArray = array();
    for ($gt = 0; $gt < pg_num_fields($results); $gt++) {
        if (pg_field_type($results, $gt) == "geometry") {
            
        } elseif ($_FILES[pg_field_name($results, $gt)]["tmp_name"]) {
            @mkdir('../../data/plan/' . $plan->planId . '/');
            $filePath = 'data/plan/' . $plan->planId . '/' . time() . '.' . $_FILES[pg_field_name($results, $gt)]["name"];
            file_put_contents('../../' . $filePath, file_get_contents($_FILES[pg_field_name($results, $gt)]["tmp_name"]));

            $sqlArray[] = '"' . pg_field_name($results, $gt) . '"=\'' . $GLOBALS["LGSettings"]->map_url . $filePath . '\'';
        } elseif (secure($_POST[pg_field_name($results, $gt)]) == "") {
            
        } else {
            $sqlArray[] = '"' . pg_field_name($results, $gt) . '"=\'' . secure($_POST[pg_field_name($results, $gt)]) . '\'';
        }
    }
    $sql = 'UPDATE  ' . secure($_POST["dbtab"]) . ' SET ' . implode(",", $sqlArray) . ' WHERE gid=' . (int) $_POST["gid"] . ' ;';
//echo $sql;
    $results = pg_query($pgsql_conn, $sql);


//vypocitej update


    pg_close($pgsql_conn);
}

header("Location: " . $_SERVER["HTTP_REFERER"]);
?>