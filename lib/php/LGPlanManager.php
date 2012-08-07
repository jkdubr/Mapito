<?php

require_once 'LGConnect.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LGPlanManager
 *
 * @author JakubDubrovsky
 */
class LGPlanManager {

    /**
     *
     * @var LGUser
     */
    var $user;

    /**
     *
     * @var GeoserverAPILigeo 
     */
    var $geoserverAPI;

    public function __construct($user) {
        $this->user = $user;
        $this->geoserverAPI = new GeoserverAPILigeo();
    }

    /*
     * @todo create account and database on PostGIS and account on geoserver
     */

    function addPlan($array) {

        if ($this->user->isPrivilegeAdmin() || TRUE) {
            $planName = secure($array["name"]);
            mydb_query("insert into plan set mapZoom='" . (int) $array['mapZoom'] . "',mapCenterLon='" . (float) secure($array['mapCenterLon']) . "',mapCenterLat='" . (float) secure($array['mapCenterLat']) . "', title='" . secure($array["title"]) . "',name='" . $planName . "',txt='" . secure($array["txt"]) . "' ;");

            $planId = mydb_insert_id();
            if ($planId || true) {


                $userManager = new LGUserManager($this->user);
                $userManager->updateUserPrivilege($this->user->userId, $planId, 3);

                $layerManager = new LGLayerManager($this->user);
                $layerManager->addLayerFolderBasic($planId);
 
               $pg = pg_connect("host=" . $GLOBALS["LGSettings"]->postgis_host . " dbname=" . $GLOBALS["LGSettings"]->postgis_template . " user=" . $GLOBALS["LGSettings"]->postgis_user . " password=" . $GLOBALS["LGSettings"]->postgis_pass . "");

                if (!$pg) {

                    die("Error in connection: " . pg_last_error());
                }

                $sql = "CREATE DATABASE ligeo_" . $planName . "  WITH ENCODING='UTF8'       OWNER=" . $GLOBALS["LGSettings"]->postgis_user . "       TEMPLATE=" . $GLOBALS["LGSettings"]->postgis_template . "       CONNECTION LIMIT=-1;";

                pg_query($pg, $sql);

            //    $sql = "GRANT ALL ON DATABASE ligeo_" . $planName . " TO GROUP ligeo_user;";

              //  pg_query($pg, $sql);

//GRANT ALL ON DATABASE ligeo_r TO GROUP ligeo_user;                
// close connection
                pg_close($pg);

                $gsName = "ligeo_" . secure($array["name"]);
                $this->geoserverAPI->api->createWorkspace($gsName);
                $this->geoserverAPI->api->createDatastore($gsName, $gsName);

                
                $cd = getcwd();
                chdir(dirname(__FILE__));
                
                if (is_dir("../../module/viewer")) {
                    mkdir('../../module/viewer/' . $planName);
                    copy('../../data/plan.template.html', '../../module/viewer/' . $planName . '/index.html');
                    if ($_FILES["fileSplashscreen"]["tmp_name"])
                        $this->uploadSplashscreen($_FILES["fileSplashscreen"], $planName);
                }
                

                chdir($cd);



                return $planId;
            }
        }
    }

    /*
     * @todo update geoserver / postgis
     */

    function updatePlan($array) {
        if ($array["splashscreenRemove"])
            $this->removeSplashscreen($array["name"]);
        if ($_FILES["fileSplashscreen"]["tmp_name"])
            $this->uploadSplashscreen($_FILES["fileSplashscreen"], $array["name"]);
        mydb_updateValuesInTab($array, 'plan');
    }

    /*
     * @todo remove from postgis and geoserver
     */

    function removePlan($planId) {
        if ($this->user->isPrivilegeAdmin() || true) {
            $plan = new LGPlan($planId);

            $gsName = "ligeo_" . $plan->name;
            $this->geoserverAPI->api->removeWorkspace($gsName);

            mydb_query("delete from plan  where planId=" . (int) $planId . ";");
            mydb_query('delete from privilege where planId=' . (int) $planId . ';');
            mydb_query("delete from layerFolder where planId=" . (int) $planId . ";");
            mydb_query("delete from layer where planId=" . (int) $planId . ";");
        }
    }

    function getPlansPublic() {
        $temp = array();
        $v = mydb_query("select planId from plan where private=0");
        while ($z = $v->fetch_array()) {
            $temp[] = $z["planId"];
        }
        return $temp;
    }

    function getPlans() {
        $temp = array();
        $v = mydb_query('select distinct planId from privilege ' . ($this->user->systemUser->isPrivilegeAdmin() ? '' : ' where userId=' . (int) $this->user->userId ) . ';');
        while ($z = $v->fetch_array()) {
            $temp[] = $z["planId"];
        }
        return $temp;
    }

    private function removeSplashscreen($name) {
        $filename = '../../module/viewer/' . $name . '/splashscreen.png';

        $cd = getcwd();
        chdir(dirname(__FILE__));
        if (file_exists($filename))
            unlink($filename);

        chdir($cd);
    }

    private function uploadSplashscreen($file, $name) {

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
                $cd = getcwd();
                chdir(dirname(__FILE__));
                file_put_contents('../../module/viewer/' . $name . '/splashscreen.png', file_get_contents($file["tmp_name"]));

                chdir($cd);
            }
        } else {
            echo "Invalid file of img";
        }
    }

}

?>