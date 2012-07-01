<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LSUserManager
 *
 * @author JakubDubrovsky
 */
class LGUserManager {

    /**
     *
     * @var LGUser
     */
    var $user;

    public function __construct($user) {
        $this->user = $user;
    }

    function addUser($array) {
        mydb_query("insert user set superUserId='" . $this->user->userId . "' ,mail='" . secure($array["mail"]) . "',title='" . secure($array["title"]) . "',tel='" . secure($array["tel"]) . "',txt='" . secure($array["txt"]) . "',password='" . secure($array["password"]) . "';");

        return mydb_insert_id();
    }

    function removeUser($userId) {
        mydb_query("delete from user where userId='" . (int) $userId . "';");
    }

    function updateUser($array) {
        mydb_query("update user set title='" . secure($array["title"]) . "',mail='" . secure($array["mail"]) . "',tel='" . secure($array["tel"]) . "',txt='" . secure($array["txt"]) . "' where userId=" . (int) $array["userId"] . ";");
        if (count($array["planPrivilege_planId"])) {
            mydb_query("delete from privilege where userId=" . (int) $array["userId"] . ";");
            for ($i = 0; $i < count($array["planPrivilege_planId"]); $i++) {
                if ($array["planPrivilege_privilege"][$i] > 0) {
                    mydb_query("insert privilege set privilege=" . (int) $array["planPrivilege_privilege"][$i] . ", planId=" . (int) $array["planPrivilege_planId"][$i] . ", userId=" . (int) $array["userId"] . ", dateFrom='" . $array["planPrivilege_dateFrom"][$i] . "', dateTo='" . $array["planPrivilege_dateTo"][$i] . "';");
                }
            }
        }
    }

    function updateUserPrivilege($userId, $planId, $privilege, $dateFrom="", $dateTo="") {
        mydb_query("replace privilege set privilege=" . (int) $privilege . ", planId=" . (int) $planId . ", userId=" . (int) $userId . ", dateFrom='" . $planPrivilege_dateFrom . "', dateTo='" . $planPrivilege_dateTo . "';");
    }

    function getUsers() {
        $temp = array();

        $v = mydb_query("select userId from user " . ($this->user->isPrivilegeAdmin() ? "" : " where superUserId=" . $this->user->userId . " ") . " ");
        while ($z = $v->fetch_array())
            $temp[] = $z["userId"];

        return $temp;
    }

}

?>