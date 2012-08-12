<?php

class LGInstall {

    private $enabledPage = "../../install/enabled";

    function __construct() {
        
    }

    function checkAll($print = false) {

        $ret1 = $this->checkMySQL($print);
        $ret2 = $this->checkPostGIS($print);

        return $ret1 || $ret2;
    }

    function checkMySQL($print = false) {
        $con = new mysqli($GLOBALS["LGSettings"]->db_host, $GLOBALS["LGSettings"]->db_user, $GLOBALS["LGSettings"]->db_pass, $GLOBALS["LGSettings"]->db_name);

        if ($con->connect_error) {
            if ($print)
                echo("<p>MySQL connection error (" . $con->connect_errno . ")</p>");
            return true;
        }
        return false;
    }

    function checkPostGIS($print = false) {

        $ret = false;

        $pgsql_conn = @pg_connect("host=" . $GLOBALS["LGSettings"]->postgis_host . " dbname=" . $GLOBALS["LGSettings"]->postgis_template . " user=" . $GLOBALS["LGSettings"]->postgis_user . " password=" . $GLOBALS["LGSettings"]->postgis_pass . "");
        if (!$pgsql_conn) {
            if ($print)
                echo("Error in PostGIS connection, PostGIS template database <b>" . $GLOBALS["LGSettings"]->postgis_template . "</b> is needed.");
            $ret = true;
        }else
            pg_close($pgsql_conn);
        return $ret;
    }

    function enable() {
        $cd = getcwd();
        chdir(dirname(__FILE__));
        file_put_contents($this->enabledPage, "1");
        chdir($cd);
    }

    function disable() {
        $cd = getcwd();
        chdir(dirname(__FILE__));
        file_put_contents($this->enabledPage, "0");
        chdir($cd);
    }

    function isEnabled() {
        $cd = getcwd();
        chdir(dirname(__FILE__));
        $return = (bool) file_get_contents($this->enabledPage);
        chdir($cd);
        return $return;
    }

    function isInstalled() {
        return is_file('../settings/main.php');
    }

    function createSettings($array) {
        $cd = getcwd();
        chdir(dirname(__FILE__));

        $mapito_viewer_modules;
        if (split(",", $array["mapito_viewer_modules"]) > 0)
            $mapito_viewer_modules = $array["mapito_viewer_modules"];
        else
            $mapito_viewer_modules = "layers,info,legend,print,measurement,login";
        file_put_contents("../../module/viewer/settings/mapito_viewer_modules", $mapito_viewer_modules);

        
        $data = '<?php

require_once "module.php";

class LGSettings {
    var $mail = "' . $array["mail"] . '";

    var $db_host = "' . $array["db_host"] . '";
    var $db_user = "' . $array["db_user"] . '";
    var $db_pass = "' . $array["db_pass"] . '";
    var $db_name = "' . $array["db_name"] . '";
    
    var $postgis_host = "' . $array["postgis_host"] . '";
    var $postgis_user = "' . $array["postgis_user"] . '";
    var $postgis_pass = "' . $array["postgis_pass"] . '";
    var $postgis_template = "' . $array["postgis_template"] . '";
    
    var $geoserver_url = "' . $array["geoserver_url"] . '";
    var $geoserver_user = "' . $array["geoserver_user"] . '";
    var $geoserver_pass = "' . $array["geoserver_pass"] . '";

    var $lang_default = "en";

    var $module;

    var $admin_url = "' . $array["admin_url"] . '";
    var $map_url = "' . $array["map_url"] . '";
    
    function __construct() {
        $this->module = new LGSettingsModule();
        
    }

}

$GLOBALS["LGSettings"] = new LGSettings();

?>';

        $filename = "../../settings/main.php";

        if (!file_put_contents($filename, $data)) {
            exit("<p>You have to change CHMOD to value 0777 for Mapito root folder. </p><p>chmod -R 777 /</p>");
        }

        chdir($cd);
    }

    function installMySQL() {
        require_once 'main.lib.php';


        $cd = getcwd();
        chdir(dirname(__FILE__));
        $query = file_get_contents('dump.sql');
        chdir($cd);


        $con = new mysqli($GLOBALS["LGSettings"]->db_host, $GLOBALS["LGSettings"]->db_user, $GLOBALS["LGSettings"]->db_pass, $GLOBALS["LGSettings"]->db_name);

        if ($con->connect_error)
            exit("Error in MySQL connection (" . $con->connect_error . "). ");
        $v = mysqli_multi_query($con, $query);

        do {

            if ($con->more_results()) {
                //        printf("-----------------\n");
            }
        } while ($con->next_result());

        //echo(mysqli_error($con));

        //exit("kuk");
        mydb_query('insert user set privilege=6,mail="' . $GLOBALS["LGSettings"]->mail . '",password="' . $GLOBALS["LGSettings"]->mail . '",title="' . $GLOBALS["LGSettings"]->mail . '";');
    }

    function installModules() {
        $cd = getcwd();
        chdir(dirname(__FILE__));




        foreach (scandir("../../module/") as $module) {
            $modulePath = "../../module/$module";
            if (!is_dir($modulePath) || $module == ".." || $module == ".")
                continue;

            //    echo("Installing module: $module <br>");
            //copy files from lib/php

            foreach (scandir(".") as $libFile) {
                if (is_file("./$libFile")) {
                    copy("./$libFile", "$modulePath/lib/php/$libFile");
                }
            }

            //copy settings
            //       echo("copy from ../settings/main.php to $modulePath/settings/main.php <br>");
            copy("../../settings/main.php", "$modulePath/settings/main.php");
        }
        chdir($cd);
    }

    function sendMailAfterInstall($mail, $admin_url) {
        $message = "Welcome in Mapito, free map server for everyone. Your server is ready on $admin_url . \n\n Important: if mapito is set up correctly, you should disable public setup page on this url: " . $admin_url . "/install/disable.php. You can enable setup page again in Mapito settings $admin_url . Setup page is $admin_url/install/settings.php \n\nYour Mapito login is $mail and password is $mail . You can change your credentionals in settings. ";
        mail($mail, "Mapito", $message);
    }

}

?>