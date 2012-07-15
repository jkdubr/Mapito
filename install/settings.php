<?php
require_once '../lib/php/LGInstall.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$LGInstall = new LGInstall();
if (!$LGInstall->isEnabled()) {
    exit("Editing is disabled. You can enabled it in settings.");
}
if ($LGInstall->isInstalled()) {
    require_once '../settings/main.php';

    $mail = $LGSettings->mail;

    $db_host = $LGSettings->db_host;
    $db_user = $LGSettings->db_user;
    $db_pass = $LGSettings->db_pass;
    $db_name = $LGSettings->db_name;

    $postgis_host = $LGSettings->postgis_host;
    $postgis_user = $LGSettings->postgis_user;
    $postgis_pass = $LGSettings->postgis_pass;

    $geoserver_url = $LGSettings->geoserver_url;
    $geoserver_user = $LGSettings->geoserver_user;
    $geoserver_pass = $LGSettings->geoserver_pass;

    $lang_default = $LGSettings->lang_default;

    $module = $LGSettings->module;

    $admin_url = $LGSettings->admin_url;
    $map_url = $LGSettings->map_url;
} else {
    $settings_domain = str_replace('install/settings.php', '', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

//
    $map_url = $settings_domain . 'module/viewer/';
    $admin_url = $settings_domain;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>
        </title>
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
        <link rel="stylesheet" href="my.css" />
        <style>
            /* App custom styles */
        </style>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js">
        </script>
        <script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js">
        </script>
    </head>
    <body>

        <!-- Home -->
        <div data-role="page" id="page1">
            <div data-theme="a" data-role="header">
                <h3>
                    Mapito - first step
                </h3>
            </div>
            <div data-role="content" style="padding: 15px">
                <form action="settings_update.php" method="POST" data-ajax="false">
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="">
                                Email
                            </label>
                            <input required name="mail" id="" placeholder="your@mail.com" value="<?php echo($mail); ?>" type="email" />
                        </fieldset>
                    </div>
                    <hr>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="">
                                MySQL db host
                            </label>
                            <input required name="db_host" id="" placeholder="localhost" value="<?php echo($db_host); ?>" type="text" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="">
                                MySQL DB name
                            </label>
                            <input required name="db_name" id="" placeholder="mapito" value="<?php echo($db_name); ?>" type="text" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="">
                                MySQL user
                            </label>
                            <input required name="db_user" id="" placeholder="root" value="<?php echo($db_user); ?>" type="text" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="">
                                MySQL password
                            </label>
                            <input required name="db_pass" id="" placeholder="" value="<?php echo($db_pass); ?>" type="password" />
                        </fieldset>
                    </div>
                    <hr>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="">
                                PostGIS DB host
                            </label>
                            <input required name="postgis_host" id="" placeholder="localhost" value="<?php echo($postgis_host); ?>" type="text" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="">
                                PostGIS DB user
                            </label>
                            <input required name="postgis_user" id="" placeholder="root" value="<?php echo($postgis_user); ?>" type="text" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="">
                                PostGIS DB password
                            </label>
                            <input required name="postgis_pass" id="" placeholder="" value="<?php echo($postgis_pass); ?>" type="password" />
                        </fieldset>
                    </div>
                    <hr>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="">
                                Geoserver URL
                            </label>
                            <input required name="geoserver_url" id="" placeholder="http://my_geoserver.com/" value="<?php echo($geoserver_url); ?>" type="url" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="">
                                Geoserver user
                            </label>
                            <input required name="geoserver_user" id="" placeholder="root" value="<?php echo($geoserver_user); ?>" type="text" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="">
                                Geoserver password
                            </label>
                            <input required name="geoserver_pass" id="" placeholder="" value="<?php echo($geoserver_pass); ?>" type="password" />
                        </fieldset>
                    </div>         
                    <hr>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="">
                                Mapito admin page
                            </label>
                            <input required name="admin_url" id="" placeholder="http://admin.your_domain.com" onchange="if(document.getElementById('f_map_url').value==''){document.getElementById('f_map_url').value=this.value}" value="<?php echo($admin_url); ?>"  type="url" />
                        </fieldset>
                    </div>
                    <div data-role="fieldcontain">
                        <fieldset data-role="controlgroup">
                            <label for="">
                                Mapito viewer url (default is http://admin.your_domain.com/module/viewer)
                            </label>
                            <input required name="map_url" id="f_map_url" placeholder="http://your_domain.com/" value="<?php echo($map_url); ?>"  type="url" />
                        </fieldset>
                    </div>

                    <input type="submit" value="Submit">


                    <!--
                                        <fieldset data-role="controlgroup" data-type="vertical">
                                            <legend>
                                                Choose:
                                            </legend>
                                            <input required name="checkbox1" id="checkbox1" type="checkbox" />
                                            <label for="checkbox1">
                                                Checkbox
                                            </label>
                                        </fieldset>
                                        <input required type="submit" value="Submit" />
                    -->
                </form>

            </div>
        </div>
    </body>
</html>