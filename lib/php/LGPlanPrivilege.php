<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LGPrivilege
 *
 * @author JakubDubrovsky
 */
class LGPlanPrivilege {

    var $privilege;
    var $dateFrom;
    var $dateTo;

    public function __construct($userId, $planId) {
        $v = mydb_query("select * from privilege where userId=" . (int) $userId . " and planId=" . (int)$planId . ";");
        if ($z = $v->fetch_array()) {
            $this->privilege = $z["privilege"];
            $this->dateFrom = $z["dateFrom"];
            $this->dateTo = $z["dateTo"];
        }
    }
    
    

}

?>