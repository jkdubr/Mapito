<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8"/>          
        <title>Mapito</title>

        <script src="../lib/js/openlayers/OpenLayers.js"></script>

        <?php
        $modules = split(",", file_get_contents("settings/mapito_viewer_modules"));
        foreach ($modules as $module) {
            ?>
            <script src="../lib/js/ligeo<?php echo($module);?>.js" ></script>
            <?php
        }
        ?>

        <script src="../lib/js/ligeobaselayers.js" ></script>

        <script type="text/javascript" src="../lib/js/jquery.js"></script>
        <script type="text/javascript" src="../lib/js/jtree/_lib/jquery.hotkeys.js"></script>
        <script type="text/javascript" src="../lib/js/jtree/jquery.jstree.js"></script>
        <link type="text/css" rel="stylesheet" href="../lib/js/jtree/_docs/syntax/!style.css"/>
        <link type="text/css" rel="stylesheet" href="../lib/js/jtree/_docs/!style.css"/>
        <script type="text/javascript" src="../lib/js/jtree/_docs/syntax/!script.js"></script>

        <!--script src="../lib/js/prettyPhoto/jquery-1.6.1.min.js" type="text/javascript"></script-->
        <!--script src="../lib/js/prettyPhoto/jquery.lint.js" type="text/javascript" charset="utf-8"></script-->
        <link rel="stylesheet" href="../lib/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
        <script src="../lib/js/prettyPhoto/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>

        <script src="../lib/js/proj4js-compressed.js"></script>

        <script src="../lib/js/ligeo.js"></script>

        <script src="http://maps.googleapis.com/maps/api/js?sensor=false"  type="text/javascript"></script>



        <link rel="stylesheet" href="../lib/css/style.css" type="text/css" />

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


    <body onload="ligeoInit('<?php echo($_GET["plan"]); ?>');">
        <table id="menu">
            <tr>
                <td colspan="2" id="toolbar">
                    <div id="panel"  class="olControlPanel"></div>
                </td>
            </tr>
            <tr>
                <td id="tabMenuContent">
                    
                </td>

                <td  id="menuList" class="menuItem">

                </td>
            </tr>
        </table>

        <div id="map" class="olMap">
        </div>
    </body>
</html>