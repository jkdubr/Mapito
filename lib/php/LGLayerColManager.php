<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LGLayerColManager
 *
 * @author jakubdubrovsky
 */
class LGLayerColManager {

    var $user;

    public function __construct($user) {
        $this->user = $user;
    }

    function addCol($planId, $layerId, $colName, $colType) {

        $plan = new LGPlan($planId);

        $layer = new LGLayer($layerId);
        $dbtab = $layer->name;

        if ($this->user->getPrivilegeForPlan($plan->planId) > 1) {
            $dbname = 'ligeo_' . $plan->name;

            $pgsql_conn = pg_connect("host=" . $GLOBALS["LGSettings"]->postgis_host . " dbname=" . $dbname . " user=" . $GLOBALS["LGSettings"]->postgis_user . " password=" . $GLOBALS["LGSettings"]->postgis_pass . "");
            if (!$pgsql_conn) {
                die("Error in connection: " . pg_last_error());
            }

            $sql = 'ALTER TABLE ' . $dbtab . '  ADD COLUMN "' . secure($colName) . '" ' . secure($colType) . ';';

            $results = pg_query($pgsql_conn, $sql);

            pg_close($pgsql_conn);
        }


        $geoserverApi = new GeoserverAPILigeo();
        $geoserverApi->api->reloadServer();
    }

    function removeCol($planId, $layerId, $colName) {

        $layer = new LGLayer($layerId);
        $dbtab = $layer->name;

        $plan = new LGPlan($planId);
        if ($this->user->getPrivilegeForPlan($plan->planId) > 1) {
            $dbname = 'ligeo_' . $plan->name;


            $pgsql_conn = pg_connect("host=" . $GLOBALS["LGSettings"]->postgis_host . " dbname=" . $dbname . " user=" . $GLOBALS["LGSettings"]->postgis_user . " password=" . $GLOBALS["LGSettings"]->postgis_pass . "");
            if (!$pgsql_conn) {
                die("Error in connection: " . pg_last_error());
            }

            $sql = 'ALTER TABLE ' . $dbtab . '  DROP COLUMN "' . secure($colName) . '" ;';

            $results = pg_query($pgsql_conn, $sql);

            pg_close($pgsql_conn);
        }

        $geoserverApi = new GeoserverAPILigeo();
        $geoserverApi->api->reloadServer();
    }

    function reloadCol($layerId) {
        //vezmu vsechny pole z PostGISu a porovnám s MySQL
        $layer = new LGLayer($layerId);
        $plan = new LGPlan($layer->planId);

        $dbname = 'ligeo_' . $plan->name;
        $dbtab = $layer->name;

        $pgsql_conn = pg_connect("host=" . $GLOBALS["LGSettings"]->postgis_host . " dbname=" . $dbname . " user=" . $GLOBALS["LGSettings"]->postgis_user . " password=" . $GLOBALS["LGSettings"]->postgis_pass . "");
        if (!$pgsql_conn) {
            die("Error in connection: " . pg_last_error());
        }
        $table_info = pg_tableInfo($dbtab, $pgsql_conn);

        foreach ($table_info as $col) {
            $v = mydb_query('select 1 from ligeoCol where layerId=' . (int) $layerId . ' and name="' . $col["name"] . '";');
            if (@mysqli_num_rows($v)) {
                mydb_query('update layerCol set type="' . $col["type"] . '", length=' . (int) $col["len"] . '  where layerId=' . (int) $layerId . ' and name="' . $col["name"] . '";');
            } else {
                mydb_query('insert  layerCol set type="' . $col["type"] . '", length=' . (int) $col["len"] . ', layerId=' . (int) $layerId . ' , name="' . $col["name"] . '", title="' . $col["name"] . '";');
            }
        }
        mydb_query('delete from layerCol where layerId=' . (int) $layerId . ' and name not in ("' . implode('","', array_keys($table_info)) . '");');
    }

    function getColsByLayerId($layerId) {
        $temp = array();

        $v = mydb_query('select layerColId from layerCol where layerId=' . (int) $layerId . ';');
        while ($z = $v->fetch_array()) {
            $temp[] = $z["layerColId"];
        }
        return $temp;
    }

}

?>
