<?php

class LGInstall {

    private $enabledPage = "../../install/enabled";

    function __construct() {
        
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
        $cd = getcwd();
        chdir(dirname(__FILE__));

        $filename = "../../settings/main.php";

        if (!file_put_contents($filename, $data)) {
            if (!chmod("../../settings/", 0777)) {
                exit("You have to change CHMOD to value 0777 for folder /settings , /install and /module/viewer ");
            }
            chmod("../../install/", 0777);
            chmod("../../module/viewer", 0777);
        }

        chdir($cd);
    }

    function installMySQL() {
        require_once 'main.lib.php';


        $cd = getcwd();
        chdir(dirname(__FILE__));
        $query = file_get_contents('dump.sql');
        chdir($cd);



        $v = mysqli_multi_query($GLOBALS["db_mysqli"], $query);

        do {

            if ($GLOBALS["db_mysqli"]->more_results()) {
                //        printf("-----------------\n");
            }
        } while ($GLOBALS["db_mysqli"]->next_result());

        echo(mysqli_error($GLOBALS["db_mysqli"]));

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