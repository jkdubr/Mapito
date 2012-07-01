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

$dbtab = "bio_hosp_hod";
$dbtab1 = "hosp_hodnota_1";
$dbtab2 = "hosp_hodnota_2";

if ($plan->name) {

    $pgsql_conn = pg_connect("host=" . $GLOBALS["LGSettings"]->postgis_host . " dbname=" . $dbname . " user=" . $GLOBALS["LGSettings"]->postgis_user . " password=" . $GLOBALS["LGSettings"]->postgis_pass . "");
    if (!$pgsql_conn) {
        die("Error in connection: " . pg_last_error());
    }
    pg_query($pgsql_conn, "SET NAMES 'utf8'");
}
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <meta charset="utf-8"/>          
        <title>Ligeo </title>
    </head>
    <body>


        <?php if ($user->isUser()) { ?>

            <?php if ($dbname) { ?>

                <h2>Hospodářská hodnota</h2>


                <a href="hosp_info.html"><b>info</b></a>
                <?php
                if ($dbtab) {
                    $layerId = $layerManager->getLayerIdByPlanAndLayerName($planId, $dbtab);
                    $layer = new LGLayer($layerId);

                    $table_info = pg_tableInfo($dbtab, $pgsql_conn);
                    ?>


                    <?php
                    $results = pg_query($pgsql_conn, 'SELECT * FROM ' . $dbtab . ' order by "group", "gid"');
                    ?>
                    <table border=1>
                        <tr>
                            <th></th>
                            <?php
                            for ($gt = 0; $gt < pg_num_fields($results); $gt++) {
                                if (pg_field_type($results, $gt) != "geometry") {
                                    if (pg_field_name($results, $gt) == "gid" || pg_field_name($results, $gt) == "name" || pg_field_name($results, $gt) == "editable_svd" || pg_field_name($results, $gt) == "hyp_max" || pg_field_name($results, $gt) == "editable_title") {
                                        
                                    } else {

                                        echo "<th>";

                                        if (pg_field_name($results, $gt) == "group") {
                                            echo "skupina";
                                        } elseif (pg_field_name($results, $gt) == "title") {
                                            echo "ekonomický faktor";
                                        } elseif (pg_field_name($results, $gt) == "a_body") {
                                            echo "body (dnes)";
                                        } elseif (pg_field_name($results, $gt) == "a_svde") {
                                            echo "SVDe";
                                        } elseif (pg_field_name($results, $gt) == "a_hosp_hodnota") {
                                            echo "aktuální hospodářská hodnota";
                                        } elseif (pg_field_name($results, $gt) == "p_body") {
                                            echo "body (potenciální)";
                                        } elseif (pg_field_name($results, $gt) == "p_svde") {
                                            echo "SVDe";
                                        } elseif (pg_field_name($results, $gt) == "p_hosp_hodnota") {
                                            echo "hospodářský potenciál";
                                        } else {
                                            echo pg_field_name($results, $gt);
                                        }


                                        echo"</th>";
                                    }
                                }
                            }
                            echo "</tr>";
                            for ($lt = 0; $lt < pg_num_rows($results); $lt++) {
                                ?>
                            <tr>
                            <form method="POST" action="../../action/pgItem_update_bio.php" enctype="multipart/form-data"  data-ajax="false">
                                <td>
                                    <input type="hidden" name="planId" value="<?php echo($plan->planId); ?>" />
                                    <input type="hidden" name="dbtab" value="<?php echo($dbtab); ?>" />
                                    <input type="hidden" name="layerId" value="<?php echo($layerId); ?>" />
                                    <input type="hidden" name="gid" value="<?php echo(pg_fetch_result($results, $lt, "gid")); ?>" />
                                    <input type="submit" value="Uložit" />
                                </td>
                                <?php
                                unset($gid);
                                for ($gt = 0; $gt < pg_num_fields($results); $gt++) {
                                    if (pg_field_name($results, $gt) == "group" || pg_field_name($results, $gt) == "title" || pg_field_name($results, $gt) == "a_body" || pg_field_name($results, $gt) == "a_svde" || pg_field_name($results, $gt) == "p_svde" || pg_field_name($results, $gt) == "a_hosp_hodnota" || pg_field_name($results, $gt) == "p_body" || pg_field_name($results, $gt) == "p_hosp_hodnota") {
                                        if (pg_field_name($results, $gt) == "title") {
                                            if (pg_fetch_result($results, $lt, "editable_title") == "0") {
                                                echo('<td>');
                                                echo('<input type="text" value="' . pg_result($results, $lt, $gt) . '" name="' . pg_field_name($results, $gt) . '" readonly="readonly" style="background-color:#DDDDDD;" size="65">');
                                                echo('</td>');
                                            } else {
                                                echo('<td>');
                                                echo('<input type="text" value="' . pg_result($results, $lt, $gt) . '" name="' . pg_field_name($results, $gt) . '" size="65" >');
                                                echo('</td>');
                                            }
                                        } elseif (pg_field_name($results, $gt) == "a_svde" || pg_field_name($results, $gt) == "p_svde" || pg_field_name($results, $gt) == "a_hosp_hodnota" || pg_field_name($results, $gt) == "p_hosp_hodnota") {
                                            if (pg_fetch_result($results, $lt, "editable_svd") == "0") {
                                                echo('<td>');
                                                echo('<input type="text" value="' . pg_result($results, $lt, $gt) . '" name="' . pg_field_name($results, $gt) . '" readonly="readonly" style="background-color:#DDDDDD;">');
                                                echo('</td>');
                                            } else {
                                                if (pg_field_name($results, $gt) == "a_hosp_hodnota" || pg_field_name($results, $gt) == "p_hosp_hodnota") {
                                                    echo('<td>');
                                                    echo('<input type="text" value="' . pg_result($results, $lt, $gt) . '" name="' . pg_field_name($results, $gt) . '" readonly="readonly"  style="background-color:#DDDDDD;">');
                                                } else {
                                                    echo('<td>');
                                                    echo('<input type="text" value="' . pg_result($results, $lt, $gt) . '" name="' . pg_field_name($results, $gt) . '">');
                                                }
                                                echo('</td>');
                                            }
                                        } elseif (pg_field_name($results, $gt) == "a_body" || pg_field_name($results, $gt) == "p_body") {
                                            echo('<td>');
                                            echo('<input type="text" value="' . pg_result($results, $lt, $gt) . '" name="' . pg_field_name($results, $gt) . '">');
                                        } else {  //|| pg_fetch_result($results,  $lt, "group") == "lesni_hospodarstvi" || pg_field_name($results, $gt) == "pracovni_mista" || pg_field_name($results, $gt) == "prumysl" || pg_field_name($results, $gt) == "tezba_ner" || pg_field_name($results, $gt) == "zemedelstvi"
                                            if (pg_fetch_result($results, $lt, "group") == "doprava") {
                                                echo('<td>');
                                                echo('<input type="text" value="' . pg_result($results, $lt, $gt) . '" name="' . pg_field_name($results, $gt) . '" readonly="readonly" style="background-color:blue;">');
                                            } elseif (pg_fetch_result($results, $lt, "group") == "lesni_hospodarstvi") {
                                                echo('<td>');
                                                echo('<input type="text" value="' . pg_result($results, $lt, $gt) . '" name="' . pg_field_name($results, $gt) . '" readonly="readonly" style="background-color:#99AA00;">');
                                            } elseif (pg_fetch_result($results, $lt, "group") == "pracovni_mista") {
                                                echo('<td>');
                                                echo('<input type="text" value="' . pg_result($results, $lt, $gt) . '" name="' . pg_field_name($results, $gt) . '" readonly="readonly" style="background-color:purple;">');
                                            } elseif (pg_fetch_result($results, $lt, "group") == "prumysl") {
                                                echo('<td>');
                                                echo('<input type="text" value="' . pg_result($results, $lt, $gt) . '" name="' . pg_field_name($results, $gt) . '" readonly="readonly" style="background-color:grey;">');
                                            } elseif (pg_fetch_result($results, $lt, "group") == "tezba_ner") {
                                                echo('<td>');
                                                echo('<input type="text" value="' . pg_result($results, $lt, $gt) . '" name="' . pg_field_name($results, $gt) . '" readonly="readonly" style="background-color:brown;">');
                                            } else {
                                                echo('<td>');
                                                echo('<input type="text" value="' . pg_result($results, $lt, $gt) . '" name="' . pg_field_name($results, $gt) . '" readonly="readonly" style="background-color:green;">');
                                            }
                                        }
                                    }
                                }

                                echo "</form>";
                                if (pg_fetch_result($results, $lt, "editable_title") == "1") {
                                    ?>
                                    <td id="pgItem_<?php echo(pg_fetch_result($results, $lt, "gid")); ?>">
                                        <input type="button" value="Smazat" onclick="if(window.confirm('Smazat?')){ document.location='../../action/pgItem_remove.php?gid=<?php echo(pg_fetch_result($results, $lt, "gid")); ?>&dbtab=<?php echo($dbtab); ?>&planId=<?php echo($plan->planId); ?>'}" /> 
                                    </td>
                                    <?php
                                }
                                echo "</tr>";
                            }
                            ?>
                    </table>
                    <h3>Přidat ostatní</h3>
                    <!--INSERT INTO bio_hosp_hod  ("group",name,title,editable_svd,editable_title,hyp_max) VALUES ('doprava','lala2','lala2','1','1',(select hyp_max from bio_hosp_hod WHERE "group"='doprava' GROUP BY bio_hosp_hod.hyp_max))-->
                    <form >
                        <select name="group" id="tabMenuContentGroup">
                            <option value="doprava">Doprava</option>
                            <option value="lesni_hospodarstvi">Lesní hospodářství</option>
                            <option value="pracovni_mista">Pracovní místa</option>
                            <option value="prumysl">Průmysl</option>
                            <option value="tezba_ner">Těžba nerostů</option>
                            <option value="zemedelstvi">Zemědělství</option>
                        </select>
                        <input type="button" value="Vložit" onclick="document.location='../../action/pgItem_insert_bio.php?group=' + document.getElementById('tabMenuContentGroup').value + '&dbtab=<?php echo($dbtab); ?>&planId=<?php echo($plan->planId); ?> '" />
                    </form>
                    <?php
                }
            }
            ?>
            <h2>View 1</h2>
            <?php

            //funkce na zjisteni zda je cislo mezi

            function numberBetween($numToCheck, $low,$high) {
                if ($numToCheck <= $low)
                    return false;
                if ($numToCheck >= $high)
                    return false;
                return true;
            }

            //konec fce
            if ($dbtab1) {
                ?>
                <?php
                $results = pg_query($pgsql_conn, 'SELECT * FROM ' . $dbtab1);
                ?>
                <table border=1>
                    <tr>
                        <?php
                        for ($gt = 0; $gt < pg_num_fields($results); $gt++) {
                            echo "<th>";



                            if (pg_field_name($results, $gt) == "sum_akt") {
                                echo "aktuální ekonomická hodnota";
                            } elseif (pg_field_name($results, $gt) == "sum_pot") {
                                echo "potenciální ekonomická hodnota";
                            } elseif (pg_field_name($results, $gt) == "plneni") {
                                echo "plnění";
                            } elseif (pg_field_name($results, $gt) == "group") {
                                echo "faktor";
                            } else {
                                echo pg_field_name($results, $gt);
                            }


                            echo"</th>";
                        } 
                        echo"<th>plnění slovně</th><th>třída</th>";
                        
                        echo "</tr>";

                        for ($lt = 0; $lt < pg_num_rows($results); $lt++) {
                            echo "<tr>";
                            unset($gid);
                            for ($gt = 0; $gt < pg_num_fields($results); $gt++) {
                                if (pg_field_name($results, $gt) == "group") {
                                    echo "<td>" . pg_result($results, $lt, $gt) . "</td>";
                                } else {

                                    echo "<td>" . round(pg_result($results, $lt, $gt)) . "</td>";
                                }
                            }
                              if (numberBetween(pg_fetch_result($results, $lt, "plneni"), 0, 16)) {
                                    echo "<td>nedostatečné</td><td>7</td>";
                                } elseif (numberBetween(pg_fetch_result($results, $lt, "plneni"), 16, 31)) {
                                    echo "<td>nízké</td><td>6</td>";
                                } elseif (numberBetween(pg_fetch_result($results, $lt, "plneni"), 31, 46)) {
                                    echo "<td>snížené</td><td>5</td>";
                                } elseif (numberBetween(pg_fetch_result($results, $lt, "plneni"), 46, 61)) {
                                    echo "<td>průměrné</td><td>4</td>";
                                } elseif (numberBetween(pg_fetch_result($results, $lt, "plneni"), 61, 76)) {
                                    echo "<td>zvýšené</td><td>3</td>";
                                } elseif (numberBetween(pg_fetch_result($results, $lt, "plneni"), 76, 91)) {
                                    echo "<td>vysoké</td><td>2</td>";
                                } elseif (numberBetween(pg_fetch_result($results, $lt, "plneni"), 91, 100)) {
                                    echo "<td>maximální</td><td>1</td>";
                                } else {
                                    echo "<td>mimo interval</td><td>mimo interval</td>";
                                }
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                    ?>





                <h2>View 2</h2>
                <?php
                if ($dbtab2) {
                    ?>
                    <?php
                    $results = pg_query($pgsql_conn, 'SELECT * FROM ' . $dbtab2);
                    ?>
                    <table border=1>
                        <tr>
                            <?php
                            for ($gt = 0; $gt < pg_num_fields($results); $gt++) {
                                echo "<th>";

                                if (pg_field_name($results, $gt) == "sum") {
                                    echo "ekonomická potenciál";
                                } elseif (pg_field_name($results, $gt) == "hyp_max") {
                                    echo "hypotetický potenciál";
                                } elseif (pg_field_name($results, $gt) == "interval") {
                                    echo "interval";
                                } elseif (pg_field_name($results, $gt) == "group") {
                                    echo "faktor";
                                } else {
                                    echo pg_field_name($results, $gt);
                                }

                                echo"</th>";
                            }
                            
                            echo"<th>slovní vyjádření</th><th>třída</th>";
                            echo "</tr>";
                            for ($lt = 0; $lt < pg_num_rows($results); $lt++) {
                                ?>
                            <tr>
                                <?php
                                unset($gid);
                                for ($gt = 0; $gt < pg_num_fields($results); $gt++) {

                                    echo "<td>" . pg_result($results, $lt, $gt) . "</td>";
                                }

                                if (numberBetween(pg_fetch_result($results, $lt, "interval"), 0, 16)) {
                                    echo "<td>extrémě nízký</td><td>7</td>";
                                } elseif (numberBetween(pg_fetch_result($results, $lt, "interval"), 16, 31)) {
                                    echo "<td>velmi nízký</td><td>6</td>";
                                } elseif (numberBetween(pg_fetch_result($results, $lt, "interval"), 31, 46)) {
                                    echo "<td>nízký</td><td>5</td>";
                                } elseif (numberBetween(pg_fetch_result($results, $lt, "interval"), 46, 61)) {
                                    echo "<td>průměrný</td><td>4</td>";
                                } elseif (numberBetween(pg_fetch_result($results, $lt, "interval"), 61, 76)) {
                                    echo "<td>vysoký</td><td>3</td>";
                                } elseif (numberBetween(pg_fetch_result($results, $lt, "interval"), 76, 91)) {
                                    echo "<td>velmi vysoký</td><td>2</td>";
                                } elseif (numberBetween(pg_fetch_result($results, $lt, "interval"), 91, 100)) {
                                    echo "<td>mimořádný</td><td>1</td>";
                                } else {
                                    echo "<td>mimo interval</td><td>mimo interval</td>";
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        echo "</table>";
                        pg_close($pgsql_conn);
                    }
                }
                ?>
                </body>
                </html>