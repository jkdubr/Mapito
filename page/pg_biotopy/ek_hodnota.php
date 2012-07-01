<?php
require_once '../../lib/php/main.lib.php';

//returns an array with infos of every field in the table (name, type, length, size)


$user = new LGUser();
$planManager = new LGPlanManager($user);
$layerManager = new LGLayerManager($user);
$planId = (int) $_GET["planId"];
$plan = new LGPlan($planId);
if ($user->getPrivilegeForPlan($plan->planId) > 1)
    $dbname = 'ligeo_' . $plan->name;
$dbtab = $_GET["dbtab"];

if ($plan->name) {

    $pgsql_conn = pg_connect("host=" . $GLOBALS["LGSettings"]->postgis_host . " dbname=" . $dbname . " user=" . $GLOBALS["LGSettings"]->postgis_user . " password=" . $GLOBALS["LGSettings"]->postgis_pass . "");
    if (!$pgsql_conn) {
        die("Error in connection: " . pg_last_error());
    }
}
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <meta charset="utf-8"/>         
        <title>Ligeo </title>
    </head>
    <body>


        <?php
        if ($user->isUser()) {

            if ($dbname) {
                if ($dbtab) {
                    ?>


                    <h3><?php echo($dbtab); ?></h3>

                    <?php
                    $results = pg_query($pgsql_conn, 'SELECT * FROM ' . $dbtab . ' order by gid');
                    ?>
                    <table border=1>
                        <tr>
                            <th></th>
                            <?php
                            for ($gt = 0; $gt < pg_num_fields($results); $gt++) {
                                if (pg_field_type($results, $gt) != "geometry") {
                                    echo "<th>";
                                    if (pg_field_name($results, $gt) == "puvodnost") {
                                        echo "původnost";
                                    } elseif (pg_field_name($results, $gt) == "chranene_druhy") {
                                        echo "chráněné druhy";
                                    } elseif (pg_field_name($results, $gt) == "stupen_degradace") {
                                        echo "stupeň degradace";
                                    } elseif (pg_field_name($results, $gt) == "jakostni_trida") {
                                        echo "jakostní třída";
                                    } elseif (pg_field_name($results, $gt) == "ohrozeni_spolecenstva") {
                                        echo "ohrožení společenstva";
                                    } elseif (pg_field_name($results, $gt) == "vyskyt_bioindikatoru") {
                                        echo "výskyt bioindikátorů";
                                    } elseif (pg_field_name($results, $gt) == "chranene_druhy2") {
                                        echo "chráněné druhy";
                                    } elseif (pg_field_name($results, $gt) == "synantropie2") {
                                        echo "synantropie";
                                    } elseif (pg_field_name($results, $gt) == "ek_hodnota") {
                                        echo "ekologická hodnota";
                                    } elseif (pg_field_name($results, $gt) == "kodbio") {
                                        echo "kód biotopu";
                                    } elseif (pg_field_name($results, $gt) == "area") {
                                        echo "rozloha [m2]";
                                    } else {
                                        echo pg_field_name($results, $gt);
                                    }

                                    echo"</th>";
                                }
                            }
                            echo "<th>procenta z celkové rozlohy</th>";
                            echo "<th>ekologická hodnota</th>";
                            echo "<th>potenciál biotopu</th>";
                            echo "</tr>";


                            for ($lt = 0; $lt < pg_num_rows($results); $lt++) {
                                ?>

                            <tr>
                            <form method="POST" action="../../action/pgItem_update_bio.php" enctype="multipart/form-data"  data-ajax="false">

                                <td>

                                    <input type="hidden" name="planId" value="<?php echo($plan->planId); ?>" />
                                    <input type="hidden" name="dbtab" value="<?php echo($dbtab); ?>" />
                                    <input type="hidden" name="layerId" value="<?php echo($layerId); ?>" />

                                    <input type="submit" value="Uložit" />

                                </td>
                <?php
                unset($gid);
                for ($gt = 0; $gt < pg_num_fields($results); $gt++) {
                    if (pg_field_type($results, $gt) != "geometry") {
                        echo('<td>');
                        if (pg_field_name($results, $gt) == "gid") {
                            $gid = pg_result($results, $lt, $gt);
                            echo ('<input type="hidden" name="gid" value="' . $gid . '">');
                            echo (pg_result($results, $lt, $gt));
                        } elseif ($table_info[pg_field_name($results, $gt)]["type"] == "varchar" && $table_info[pg_field_name($results, $gt)]["len"] == 212) {

                            echo('<input type="file"  name="' . pg_field_name($results, $gt) . '">');
                            if (pg_result($results, $lt, $gt))
                                echo('<a  target="_blank" href="' . pg_result($results, $lt, $gt) . '">odkaz</a>');

                            echo('<br><input type="text" value="' . pg_result($results, $lt, $gt) . '" name="' . pg_field_name($results, $gt) . '">');
                        } else {
                            echo('<input type="text" value="' . pg_result($results, $lt, $gt) . '" name="' . pg_field_name($results, $gt) . '">');
                        }
                        ?>
                                        </td>
                                        <?php
                                    }
                                }
                                ?>
                                <td>
                                <?php
                                $results_2 = pg_query($pgsql_conn, 'select area*100/allrow.sumarea as areaperc from ' . $dbtab . ',(select sum(area) as sumarea from ' . $dbtab . ') as allrow where gid =' . $gid . '');

                                $row = pg_fetch_all($results_2);
                                $aa = $row[0];
                                echo '<input type="text" value="' . $aa["areaperc"] . '" ';
                                ?>

                                </td>
                                <td>
                                    <?php
                                    $results_3 = pg_query($pgsql_conn, 'select ((puvodnost + antropogenost + stupen_degradace + jakostni_trida + trofie + saprobita + zralost + rozmanitost + synantropie + chranene_druhy + ohrozeni_spolecenstva + vyskyt_bioindikatoru + chranene_druhy2 + synantropie2)*' . $aa["areaperc"] . ') as ekhodn from ' . $dbtab . ' where gid =' . $gid . '');
                                    $row_3 = pg_fetch_all($results_3);
                                    $aa_3 = $row_3[0];
                                    echo '<input type="text" value="' . $aa_3["ekhodn"] . '" ';
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $results_4 = pg_query($pgsql_conn, 'select (ke) as potbio from ' . $dbtab . ' where gid =' . $gid . '');
                                    $row_4 = pg_fetch_all($results_4);
                                    $aa_4 = $row_4[0];
                                    $bb_4 = $aa_4["potbio"] * $aa_3["ekhodn"];
                                    echo '<input type="text" value="' . $bb_4 . '" ';
                                    ?>
                                </td>                                   
                                <td id="pgItem_<?php echo($gid); ?>" <?php if ($_GET["pgItem"] == $gid) { ?> style="background-color: yellow" <?php } ?> >
                                    <input type="button" value="Smazat" onclick="if(window.confirm('Smazat?')){ document.location='../../action/pgItem_remove.php?gid=<?php echo($gid); ?>&dbtab=<?php echo($dbtab); ?>&planId=<?php echo($plan->planId); ?>'}" />
                                </td>
                            </form>
                        </tr>

                <?php
            }
        }
    }
}
?>
</body>
</html>
