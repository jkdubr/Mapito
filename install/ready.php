<?php
require_once '../lib/php/main.lib.php';
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
        <?php
        $LGInstall = new LGInstall();
        if ($LGInstall->checkAll(false)) {
            ?>
            <div data-role="page" id="page1">
                <div data-theme="a" data-role="header">
                    <h3>
                        Error log
                    </h3>
                </div>
                <div data-role="content" style="padding: 15px">
                    <?php
                    $LGInstall->checkAll(true);
                    ?>

                </div>
            </div> 
            <?php
        }
        ?>
        <!-- Home -->
        <div data-role="page" id="page1">
            <div data-theme="a" data-role="header">
                <h3>
                    Congratulation
                </h3>
            </div>
            <div data-role="content" style="padding: 15px">


                <p>Mapito server is ready to use.</p>

                <!--<p>Registration information has been sent to your mail.</p>-->
                <p>Your new login is your email, your password is your email.</p>

                <p>Enjoy <a href="../module/viewer/example/" data-ajax="false">map example</a></p>

                <p>Go to <a href="../" data-ajax="false">admin page</a></p>
            </div>
        </div>
        <script>
            //App custom javascript
        </script>
    </body>
</html>