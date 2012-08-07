<?php

$GLOBALS["db_mysqli"] = @new mysqli($GLOBALS["LGSettings"]->db_host, $GLOBALS["LGSettings"]->db_user, $GLOBALS["LGSettings"]->db_pass, $GLOBALS["LGSettings"]->db_name);

if ($GLOBALS["db_mysqli"]->connect_error) {
    exit('MySQL connect error (' . $mysqli->connect_error . '), try to repair MySQL connectio for user: '.$GLOBALS["LGSettings"]->db_user.' and database:'.$GLOBALS["LGSettings"]->db_name.' ');
}
$GLOBALS["db_mysqli"]->query('SET NAMES "utf8" COLLATE "utf8_czech_ci"');

/**
 *
 * @param String $query
 * @return mysqli_result
 */
function mydb_query($query) {
    $er = 0;
    $v = mysqli_query($GLOBALS['db_mysqli'], $query);
    if (($er && mysqli_error($GLOBALS['db_mysqli']))) {
        echo(mysqli_error($GLOBALS['db_mysqli']) . '<br>' . $query);
    }
    return $v;
}

function mydb_insert_id() {
    return mysqli_insert_id($GLOBALS['db_mysqli']);
}

function mydb_insertValuesInTab($array, $tabName) {
    mydb_query('insert into ' . $tabName . ' set ' . mydb_sqlValuesInTab($array, $tabName) . ';');
    return mydb_insert_id();
}

function mydb_updateValuesInTab($array, $tabName) {
    mydb_query('update ' . $tabName . ' set ' . mydb_sqlValuesInTab($array, $tabName) . ' where `' . $tabName . 'Id`=' . (int) $array[$tabName . 'Id'] . ';');
    return mydb_insert_id();
}

function mydb_sqlValuesInTab($array, $tabName) {
    $sqlArray = array();
    $v = mydb_query('SELECT data_type,column_name,COLUMN_KEY FROM  information_schema.`COLUMNS` WHERE TABLE_SCHEMA="' . $GLOBALS['LGSettings']->db_name . '" and table_name="' . $tabName . '" and column_name in("' . implode('","', array_keys($array)) . '");');
    while ($z = $v->fetch_array()) {
        $sqlArray[] = '`' . $z['column_name'] . '`="' . secure($array[$z['column_name']], $z['data_type']) . '"';
    }
    return (count($sqlArray) ? implode(',', $sqlArray) : '');
}

function pg_tableInfo($TABLE, $DBCON) {
    $s = "SELECT a.attname AS name, t.typname AS type, a.attlen AS size, a.atttypmod AS len, a.attstorage AS i 
    FROM pg_attribute a , pg_class c, pg_type t 
    WHERE c.relname = '$TABLE'  
    AND a.attrelid = c.oid AND a.atttypid = t.oid";

    if ($r = pg_query($DBCON, $s)) {
        $i = 0;
        while ($q = pg_fetch_assoc($r)) {
            if ($q["type"] != "tid" && $q["type"] != "xid" && $q["type"] != "oid" && $q["type"] != "cid") {
                $name = $q["name"];
                $a[$name]["type"] = $q["type"];
                $a[$name]["name"] = $name;
                if ($q["len"] < 0 && $q["i"] != "x") {
                    // in case of digits if needed ... (+1 for negative values)
                    $a[$name]["len"] = (strlen(pow(2, ($q["size"] * 8))) + 1);
                } else {
                    $a[$name]["len"] = $q["len"];
                }
                $a[$name]["size"] = $q["size"];
                $i++;
            }
        }
        return $a;
    }
    return null;
}

?>