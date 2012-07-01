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
        <title>Mapito </title>
    </head>
    <body>


        <?php if ($user->isUser()) { ?>
            <fieldset>

                <form method="GET" action="">
                    <select name="planId">

                        <?php
                        foreach ($planManager->getPlans() as $planIdtemp) {
                            if ($user->getPrivilegeForPlan($planIdtemp) > 1) {
                                $planT = new LGPlan($planIdtemp);
                                ?>
                                <option value="<?php echo($planIdtemp); ?>"><?php echo($planT->title); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <input type="submit" value="Vyber">
                </form>
            </fieldset>


            <?php if ($dbname) { ?>

                <h2><?php echo($plan->title); ?></h2>


                <fieldset>

                    <form method="GET" action="">
                        <input type="hidden" name="planId" value="<?php echo($plan->planId); ?>">
                            <select name="dbtab">
                                <?php
                                $sql = "SELECT f_table_name,type  FROM geometry_columns  ";
                                $result = pg_query($pgsql_conn, $sql);
                                if (!$result) {
                                    die("Error in SQL query: " . pg_last_error());
                                }

                                while ($row = pg_fetch_array($result)) {
                                    echo('<option value="' . $row["f_table_name"] . '">' . $row["f_table_name"] . '</option>');
                                }
                                ?>
                            </select>
                            <input type="submit" value="Vyber" />
                    </form>
                </fieldset>


                <?php
                if ($dbtab) {
                    $layerId = $layerManager->getLayerIdByPlanAndLayerName($planId, $dbtab);
                    $layer = new LGLayer($layerId);
                    if ($layer->isLocked) {
                        ?>
                        Vrstva uzamčena
                        <?php
                    } else {
                        $table_info = pg_tableInfo($dbtab, $pgsql_conn);
                        ?>
                        <fieldset>
                            <form action="../../action/pgCol_add.php" method="POST">
                                <input type="hidden" name="planId" value="<?php echo($plan->planId); ?>" />
                                <input type="hidden" name="layerId" value="<?php echo($layerId); ?>" />


                                <select name="colType">
                                    <option value="TEXT">TEXT</option>
                                    <option value="varchar(208)">LINK</option>

                                </select>
                                <input type="text" name="colName" />
                                <input type="submit" value="Vyber" />
                            </form>
                        </fieldset>

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

                                        echo "<th>" . pg_field_name($results, $gt) . " (" . pg_field_type($results, $gt) . " [" . $table_info[pg_field_name($results, $gt)]["len"] . "])";
                                        if (pg_field_name($results, $gt) != "gid") {
                                            ?>
                                            <form name="form_<?php echo(pg_field_name($results, $gt)); ?>_remove" action="../../action/pgCol_remove.php" method="POST">
                                                <input type="hidden" name="planId" value="<?php echo($plan->planId); ?>">

                                                    <input type="hidden" name="layerId" value="<?php echo($layerId); ?>" />
                                                    <input type="hidden" name="colName" value="<?php echo(pg_field_name($results, $gt)); ?>" />
                                                    <a onClick="if(window.confirm('Smazat?')){document.form_<?php echo(pg_field_name($results, $gt)); ?>_remove.submit();}">del</a>                    
                                            </form>        
                                            <?php
                                        }
                                        echo"</th>";
                                    }
                                }
                                echo "</tr>";


                                for ($lt = 0; $lt < pg_num_rows($results); $lt++) {
                                    ?>

                                    <tr>
                                        <form method="POST" action="../../action/pgItem_update.php" enctype="multipart/form-data"  data-ajax="false">

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
                                            <td id="pgItem_<?php echo($gid); ?>" <?php if ($_GET["pgItem"] == $gid) { ?> style="background-color: yellow" <?php } ?> >
                                                <input type="button" value="Smazat" onclick="if(window.confirm('Smazat?')){ document.location='action/pgItem_remove.php?gid=<?php echo($gid); ?>&dbtab=<?php echo($dbtab); ?>&planId=<?php echo($plan->planId); ?>'}" /> 
                                            </td>
                                        </form>
                                    </tr>

                                    <?php
                                }
                                echo "</table>";


                                pg_close($pgsql_conn);
                            }
                        }
                    }
                }
                ?>
                </body>
                </html>