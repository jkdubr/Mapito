<?php
require_once 'lib/php/main.lib.php';

$user = new LGUser();
$planManager = new LGPlanManager($user);
?>
<!DOCTYPE html> 
<html> 
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Mapito | Admin</title> 

        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />

        <link rel="apple-touch-icon" href="favicon.png" />
        <link rel="apple-touch-icon-precomposed" ref="favicon.png" />

        <meta name="viewport" content="width=device-width, initial-scale=1"> 

        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
        <script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>


        <link rel="stylesheet" href="style/main.css"></link>


        <script type="text/javascript">
            $(document).ready(function() {

<?php
if (!$user->isUser()) {
    ?>
                $.mobile.changePage('login.php', {transition: 'pop', role: 'dialog'});

<?php } ?>
    
    });
    
    
        </script>


        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-24999566-1']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>
    </head> 
    <body> 











        <?php
        if ($user->isUser()) {
            ?>


            <div data-role="page" class="type-interior">

                <div data-role="header" data-theme="f">
                    <h1>Mapito admin</h1>
                </div><!-- /header -->

                <div data-role="content">
                    <div class="content-primary">		
                        <?php
                        $LGInstall = new LGInstall();
                        if ($_GET['page'])
                            require_once 'page/' . $_GET['page'] . '/index.php';
                        else if ($LGInstall->isEnabled() && $user->isPrivilegeSuperAdmin()) {
                            echo("Setup page is enable. Due to security, you should disable public setup page <a href='install/disable.php' target='_blank'>in settings</a>");
                        }
                        ?>

                    </div><!--/content-primary -->		

                    <div class="content-secondary">

                        <div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="d">

                            <ul data-role="listview" data-theme="c" data-dividertheme="d">
                                <li data-role="list-divider"><?php echo($user->systemUser->title); ?></li>
                                <?php
                                /*
                                  $lang["user_account"]="Uživatelský účet";
                                  $lang["plan_edit"]="Plány";
                                  $lang["layers_edit"]="Vrstvy";
                                  $lang["layerStyles_edit"]="Styly";
                                  $lang["users_account"]="Správa uživatelů";

                                  //vypis slozku page/

                                  foreach (scandir("page") as $value) {
                                  if ( $value == "." || $value == ".." || !is_dir("page/$value"))
                                  continue;
                                  ?>
                                  <li><a href="index.php?page=<?php echo($value);?>"><?php echo($value); ?></a></li>
                                  <?php
                                  }
                                 */
                                ?>


                                <li><a href="index.php?page=user_account">My account</a></li>
                                <li><a href="index.php?page=plan_edit">Plans</a></li>
                                <li><a href="index.php?page=layers_edit">Layers</a></li>
                                <li><a href="index.php?page=layerStyles_edit">SLD Styles</a></li>
                                <!--<li><a href="page/pg_edit" data-ajax="false" target="_blank">Editace vrstev</a></li>-->


                                <?php if ($user->isPrivilegeSuperAdmin()) { ?>
                                    <li><a href="index.php?page=layers_public">Public layers</a></li>
                                    <li><a href="index.php?page=users_edit">Users</a></li>

                                    <?php
                                    if ($LGInstall->isEnabled()) {
                                        ?>  
                                        <li><a href="install/settings.php" target="_blank">Mapito Setup</a></li>
                                    <?php } ?>

                                    <li><a href="install/<?php echo(($LGInstall->isEnabled() ? "disable" : "enable")); ?>.php" target="_blank" data-ajax="false"><?php echo(($LGInstall->isEnabled() ? "Disable setup page" : "Enable setup page")) ?></a></li>
                                    <!--<li><a href="contact/">Vkládání kontaktů</a></li>-->
                                <?php } ?>
                                <li><a href="action/logout.php"  data-ajax="false">Logout</a></li>


                            </ul>
                        </div>
                    </div>		

                </div><!-- /content -->

                <div data-role="footer" class="footer-docs" data-theme="c">
                    <p>&copy; 2011</p>
                </div>
            </div><!-- /page -->
        <?php } ?>
    </body>
</html>