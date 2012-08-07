<?php

/**
 * Description of LGLayerManager
 *
 * při změnách vrstev se bude zasílat CURL na geosrvr
 * 
 * @author JakubDubrovsky
 */
class LGLayerManager {

    /**
     *
     * @var LGUser 
     */
    var $user;
    var $geoserverApi;

    /**
     *
     * @param LGUser $user 
     */
    public function __construct($user) {
        $this->user = $user;
        $this->geoserverApi = new GeoserverAPILigeo();
    }

    function updateLayersFromPostgis($planId) {

        $layerColManager = new LGLayerColManager($this->user);

        $plan = new LGPlan($planId);

        $pg = pg_connect("host=" . $GLOBALS["LGSettings"]->postgis_host . " dbname=ligeo_" . $plan->name . " user=" . $GLOBALS["LGSettings"]->postgis_user . " password=" . $GLOBALS["LGSettings"]->postgis_pass . "");
        if (!$pg) {
            die("Error in connection: " . pg_last_error());
        }

        // execute query
        $sql = "SELECT f_table_name,type  FROM geometry_columns  ";
        $result = pg_query($pg, $sql);
        if (!$result) {
            die("Error in SQL query: " . pg_last_error());
        }

        while ($row = pg_fetch_array($result)) {

            $temp["title"] = $temp["name"] = $row["f_table_name"];
            $temp["new"] = 1;
            $temp["type"] = $row['type'];
            $temp["namespace"] = "ligeo_" . $plan->name;
            $temp["planId"] = $planId;
            $temp["url"] = $GLOBALS["LGSettings"]->admin_url . '/proxyWMS.php?';

            $v = mydb_query('select layerId from layer where name="' . $temp["name"] . '" and planId=' . $planId . ';');
            if (mysqli_num_rows($v)) {
                $z = $v->fetch_array();
                $layerId = $z["layerId"];
            } else {
                $layerId = $this->addLayer($temp);
            }

            $layerColManager->reloadCol($layerId);
        }

        // free memory
        pg_free_result($result);

        // close connection
        pg_close($pg);
    }

    /*
     * @todo addlayer on geoserver
     */

    function addLayer($array) {
        if (true || $this->user->isPrivilegeAdmin()) {
            if ((!$array["layerFolderId"] || $array["layerFolderId"] == 0) && $array["planId"])
                $array["layerFolderId"] = $this->getLayerFolderBasic($array["planId"]);

            if ($array["type"] == "RASTR") {
                $array["title"] = $array["name"];
            }
            

            $layerId = mydb_insertValuesInTab($array, 'layer');

            if ($layerId && $array["planId"] != 0) {
                if ($array["type"] == "RASTR") {
                    $plan = new LGPlan($array["planId"]);
                    $ws = "ligeo_" . $plan->name;
                    $this->geoserverApi->api->createCoverage($ws, $array["name"]);
                } else {
                    $geoserverAPI = new GeoserverAPILigeo();
                    $geoserverAPI->api->createLayer(secure($array["namespace"]), secure($array["namespace"]), secure($array["name"]), secure($array["title"]));
                }
                if ($_FILES["fileLegend"]["tmp_name"])
                    $this->uploadLayerLegend($_FILES["fileLegend"], $layerId);
            }
            
            $this->geoserverApi->api->reloadServer();
            
            return $layerId;
        }
    }

    function createLayerOnDB($array) {


        $plan = new LGPlan($array["planId"]);

        $array["namespace"] = "ligeo_" . $plan->name;

        $type = ($array["type"] == "POINT" ? "MULTIPOINT" : ($array["type"] == "MULTIPOLYGON" ? "MULTIPOLYGON" : ($array["type"] == "LINESTRING" ? "MULTILINESTRING" : "")));

        $sql1 = "CREATE TABLE " . $array["name"] . "(gid serial NOT NULL, PRIMARY KEY (gid))";
        $sql2 = "SELECT AddGeometryColumn('" . $array["name"] . "', 'the_geom', '4326', '" . $type . "', 2 );";


        $dbname = 'ligeo_' . $plan->name;
        $pgsql_conn = pg_connect("host=" . $GLOBALS["LGSettings"]->postgis_host . " dbname=" . $dbname . " user=" . $GLOBALS["LGSettings"]->postgis_user . " password=" . $GLOBALS["LGSettings"]->postgis_pass . "");
        if (!$pgsql_conn) {
            die("Error in connection: " . pg_last_error());
        }

        pg_query($sql1);
        pg_query($sql2);

        pg_close($pgsql_conn);


        /**
         * CREATE TABLE table_name (
           gid serial NOT NULL ,
           PRIMARY KEY (gid)
          );
         * 
         *
          SELECT AddGeometryColumn('table_name', 'the_geom', '4326', 'MULTIPOLYGON', 2 );
          /*

          AddGeometryColumn ( Schema_name (neni povine), Table_name (stejne jako create table), Geom_column (standartne pouzivat the_geom), SRID, Geometri_type (POINT,LINESTRING,POLYGON,MULTIPOINT,MULTILINESTRING,MULTIPOLYGON,GEOMETRYCOLLECTION), Dimension (budeme pouzivat 2) )

         */
        $this->addLayer($array);
    }

    /*
     * @todo update layer on geoserver
     */

    function updateLayer($array) {
        if ($this->user->isPrivilegeAdmin() || true) {
            $array['new'] = 0;
            unset($array["name"]);
            if ($_FILES["fileLegend"]["tmp_name"])
                $this->uploadLayerLegend($_FILES["fileLegend"], $array["layerId"]);
            mydb_updateValuesInTab($array, 'layer');
        }
    }

    /*
     * @todo remove layer from geoserver
     */

    function removeLayer($layerId) {
        if ($this->user->isPrivilegeAdmin() || true) {


            $layer = new LGLayer($layerId);
            $plan = new LGPlan($layer->planId);
            $ws = "ligeo_" . $plan->name;
            if (!$layer->isPublic) {
                if ($layer->type == "RASTR") {

                    $ds = $layer->name;

                    $this->geoserverApi->api->removeCoverage($ds, $ws);
                } else {
                    $this->geoserverApi->api->removeLayer($ws, $ws, $layer->name);
                }
            }
            mydb_query("delete from layer where layerId=" . (int) $layerId . ";");
        }
    }

    function addLayerFolder($array) {
        if ($this->user->isPrivilegeAdmin() || true) {

            mydb_query("insert layerFolder set title='" . secure($array["title"]) . "',txt='" . secure($array["txt"]) . "',planId='" . (int) $array["planId"] . "',basic='" . (int) $array["basic"] . "' ;");
            return mydb_insert_id();
        }
    }

    function addLayerFolderBasic($planId) {
        $array["title"] = "Folder";
        $array["planId"] = $planId;
        $array["basic"] = 1;

        $this->addLayerFolder($array);
    }

    function getLayerFolderBasic($planId) {
        $v = mydb_query("select layerFolderId from layerFolder where planId= " . (int) $planId . " and basic=1;");
        while ($z = $v->fetch_array())
            return $z["layerFolderId"];
    }

    function updateLayerFolder($array) {
        if ($this->user->isPrivilegeAdmin() || true) {
            mydb_updateValuesInTab($array, 'layerFolder');
//            mydb_query("update layerFolder set title='" . secure($array["title"]) . "',txt='" . secure($array["txt"]) . "',planId='" . (int) $array["planId"] . "' where layerFolderId=" . (int) $array["layerFolderId"] . ";");
        }
    }

    function removeLayerFolder($layerFolderId) {
        if ($this->user->isPrivilegeAdmin() || true) {

            mydb_query('update layer as l set layerFolderId=(select layerFolderId from layerFolder where planId= l.planId and basic=1) where layerFolderId=' . (int) $layerFolderId . ';');
            mydb_query("delete from layerFolder where layerFolderId=" . (int) $layerFolderId . ";");
        }
    }

    /**
     * @todo rozlisovat prihlasen/neporihlasen
     * @param type $planId
     * @return type 
     */
    function getLayersByPlan($planId) {
        $temp = array();

        $v = mydb_query("select layerId from layer where planId= " . (int) $planId . " " . ($this->user->isUser() ? "" : " and private=0 ") . ";");
        while ($z = $v->fetch_array())
            $temp[] = $z["layerId"] = $z["layerId"];

        return $temp;
    }

    function getLayerIdByPlanAndLayerName($planId, $layerName) {
        $v = mydb_query('select layerId from layer where planId=' . (int) $planId . ' and name="' . secure($layerName) . '";');
        while ($z = $v->fetch_array())
            return $z["layerId"];
    }

    /**
     * @return type 
     */
    function getLayersPublic() {
        $temp = array();

        $v = mydb_query("select layerId from layer where planId= 0 ;");
        while ($z = $v->fetch_array())
            $temp[] = $z["layerId"] = $z["layerId"];

        return $temp;
    }

    function addLayerPublicToPlan($layerId, $planId) {

        $v = mydb_query('select * from`layer` where layerId=' . (int) $layerId . ';');
        while ($z = $v->fetch_array()) {

            unset($z["layerId"]);
            $z["planId"] = $planId;
            $z["layerFolderId"] = $this->getLayerFolderBasic($planId);
            mydb_insertValuesInTab($z, "layer");
            //mydb_query('insert into layer set `title`="' . secure($z['title']) . '", `url`="' . secure($z['url']) . '", `namespace`="' . secure($z['namespace']) . '", `name`="' . secure($z['name']) . '", `format`="' . secure($z['format']) . '", `opacity`="' . secure($z['opacity']) . '", `palete`="' . secure($z['palete']) . '", `type`="' . secure($z['type']) . '", `legendImageUrl`="' . secure($z['legendImageUrl']) . '", `txt`="' . secure($z['txt']) . '", `isInDb`="' . secure($z['isInDb']) . '",  `layerFolderId`="' . $this->getLayerFolderBasic($planId) . '", `planId`="' . $planId . '",  `rank`="' . secure($z['rank']) . '", `private`="' . secure($z['private']) . '", `queryable`="' . secure($z['queryable']) . '", `checked`="' . secure($z['checked']) . '", `style`="' . secure($z['style']) . '", `isInLegend`="' . secure($z['isInLegend']) . '", `printable`="' . secure($z['printable']) . '" ');
        }
    }

    function getNumberOfNewLayersByPlan($planId) {

        $v = mydb_query("select count(*) as c from layer where planId= " . (int) $planId . " and new=1 ;");
        while ($z = $v->fetch_array())
            return $z["c"];
    }

    function getLayerFoldersByPlan($planId) {
        $temp = array();

        $v = mydb_query("select layerFolderId from layerFolder where planId= " . (int) $planId . "  ;");
        while ($z = $v->fetch_array())
            $temp[] = $z["layerFolderId"];

        return $temp;
    }

    function collectDataFromForm($formId, $post) {

        $form = new LGForm($formId);

        $layer = new LGLayer($form->layerId);
        if ($this->user->getPrivilegeForPlan($layer->planId) < 2 || $layer->isLocked)
            return 0;

        $layerColManager = new LGLayerColManager($this->user);

        $sqlCols = array();
        $sqlValues = array();
        foreach ($layerColManager->getColsByLayerId($form->layerId) as $layerColId) {
            $layerCol = new LGLayerCol($layerColId);


            if (in_array($layerCol->type, array("file", "picture", "video", "audio")) && $_FILES[$layerCol->name]["tmp_name"]) {
                $cd = getcwd();
                chdir(dirname(__FILE__));

                mkdir('../../../data/plan/' . $layer->planId . '/');
                $filePath = 'data/plan/' . $layer->planId . '/' . time() . '.' . $_FILES[$layerCol->name]["name"];
                file_put_contents('../../../' . $filePath, file_get_contents($_FILES[$layerCol->name]["tmp_name"]));
                chdir($cd);

                $post[$layerCol->name] = $GLOBALS["LGSettings"]->map_url . $filePath;
            }

            if ($post[$layerCol->name]) {
                $sqlCols[] = '"' . $layerCol->name . '"';

                $sqlValues[] = '\'' . secure($post[$layerCol->name], $layerCol->type) . '\'';
            }
        }
        if (count($sqlCols) == 0)
            return 0;

        $plan = new LGPlan($layer->planId);

        //insert into gp_hydrant (the_geom) values (ST_SetSRID(ST_Point( 15, 50), 4326)) 
        // if($post['ligeoLan'] && $post['ligeoLng']){
        $sqlCols[] = '"the_geom"';

        $sqlValues[] = 'ST_SetSRID(ST_Point(' . (float) $post['ligeoLng'] . ', ' . (float) $post['ligeoLat'] . '), 4326)';
        //  }

        $sql = "insert into $layer->name  (" . implode(",", $sqlCols) . ") VALUES (" . implode(",", $sqlValues) . ")";



        $dbname = 'ligeo_' . $plan->name;

        $pgsql_conn = pg_connect("host=" . $GLOBALS["LGSettings"]->postgis_host . " dbname=" . $dbname . " user=" . $GLOBALS["LGSettings"]->postgis_user . " password=" . $GLOBALS["LGSettings"]->postgis_pass . "");
        if (!$pgsql_conn) {
            die("Error in connection: " . pg_last_error());
        }

        pg_query($sql);

        pg_close($pgsql_conn);

        return 1;
    }

    function uploadFile($formId) {


        $form = new LGForm($formId);

        $layer = new LGLayer($form->layerId);
        if ($this->user->getPrivilegeForPlan($layer->planId) < 2 || $layer->isLocked)
            return 0;

        if ($_FILES["file"]["tmp_name"]) {
            $cd = getcwd();
            chdir(dirname(__FILE__));

            @mkdir('../../../data/plan/' . $layer->planId . '/');
            $filePath = 'data/plan/' . $layer->planId . '/' . time() . '.' . $_FILES["file"]["name"];
            file_put_contents('../../../' . $filePath, file_get_contents($_FILES["file"]["tmp_name"]));
            chdir($cd);

            return $GLOBALS["LGSettings"]->map_url . $filePath;
        }
    }

    /*
      function importLayersFromGeoserver($planId) {
      $planName = "ligeo" . $planId;

      $geoserverAPI = new GeoserverAPILigeo();
      //   $geoserverAPI->api->getLayers($planName, $planName);
      $import = $geoserverAPI->api->getLayers("ostresany", "ostresany_vektor_01");


      foreach ($import->featureTypes->featureType as $layerNameJson) {

      //    echo("2222".$layerNameJson["name"]);
      //            $importLayer = $geoserverAPI->api->getLayer($planId, $planId, $layerNameJson->name);
      $importLayer = $geoserverAPI->api->getLayer("ostresany", "ostresany_vektor_01", $layerNameJson->name);

      $array["new"] = 1;
      $array["title"] = $importLayer->featureType->title;
      $array["name"] = $importLayer->featureType->name;
      $array["planId"] = $planId;
      $array["layerFolderId"] = 0;
      $array["namespace"] = $planName;
      $array["url"] = $geoserverAPI->wmsUrl;



      $this->addLayer($array);
      }
      } */

    private function uploadLayerLegend($file, $id) {
        if ((($file["type"] == "image/gif")
                || ($file["type"] == "image/jpeg")
                || ($file["type"] == "image/png")
                || ($file["type"] == "image/jpg"))
                && ($file["size"] < 1000000)) {
            if ($file["error"] > 0) {
                echo "Error: " . $file["error"] . "<br />";
            } else {
                /*
                  $fileName = '../../img/style/'.(int)$id.'.png';
                  echo "Upload: " . $file["name"] . "<br />";
                  echo "<a href='".$fileName."'>: " . $fileName . "</a><br />";
                  echo "Type: " . $file["type"] . "<br />";
                  echo "Size: " . ($file["size"] / 1024) . " Kb<br />";
                  echo "Stored in: " . $file["tmp_name"];
                 */
                file_put_contents('../../img/legend/' . $id . '.png', file_get_contents($file["tmp_name"]));
            }
        } else {
            echo "Invalid file of img";
        }
    }

}

?>