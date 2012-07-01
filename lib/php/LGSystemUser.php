<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class LGSystemUser {

    var $userId;
    var $superUserId;
    var $title;
    var $mail;
    var $tel;
    var $txt;
    var $applicationPrivilege;

    function __construct($userId) {

        $v = mydb_query("select * from user where userId = '" . (int) $userId . "';");

        if ($z = $v->fetch_array()) {

            $this->userId = $z["userId"];
            $this->title = $z["title"];
            $this->mail = $z["mail"];
            $this->tel = $z["tel"];
            $this->txt = $z["txt"];
            $this->applicationPrivilege = $z["privilege"];
            $this->superUserId = $z["superUserId"];
        } else {
            return false;
        }
    }

    /*
     * @todo passOld
     */

    function updatePassword($array) {
        
    }

    /*
     * send reset URL to user's mail (valid for 2hour)
     * @todo generate new password and send to user mail
     */

    function resetUserPassword($mail, $key, $pass1, $pass2) {
        $mail = secure($mail);
        $pass1 = secure($pass1);

        $v = mydb_query("select 1 from user where mail = '" . $mail . "' and key='" . secure($key) . "';");
        if (!mysqli_num_rows($v) || $pass1 != $pass2)
            return false;

        mydb_query("update user set password='" . $pass1 . "' where mail='" . $mail . "';");
        return true;
    }

    function sendUserResetMail($mail) {
        $mail = secure($mail);
        $key = md5($mail . "ligeo" . time());
        mydb_query("insert userReset set mail='" . $mail . "',key='" . $key . "';");

        $msg = "Muzete resetovat mail <a href='key=" . $key . "&mail=" . $mail . "'>na teto adrese</a>";
    }

    function privilegeForPlan($planId) {
        
        $privilege = new LGPlanPrivilege($this->userId, $planId);
        return $privilege->privilege;
    }

    function isPrivilegeAdmin() {
        return $this->applicationPrivilege >= 5;
    }

    function isPrivilegeSuperAdmin() {
        return $this->applicationPrivilege >= 6;
    }

}

?>