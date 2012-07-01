<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class LGUser {

    var $userId;

    /**
     *
     * @var LGSystemUser 
     */
    var $systemUser;
    var $userHash;

    function __construct() {
        if($_GET["userHash"]){
            $this->hash($_GET["userHash"]);
        }
        if (!$_SESSION["userId"]) {
            return false;
        }
        $this->userId = $_SESSION["userId"];
        $this->systemUser = new LGSystemUser($this->userId);
    }

    function login($mail, $password) {
        $v = mydb_query("select userId from user where mail='" . secure($mail) . "' and password='" . secure($password) . "';");
        if ($z = $v->fetch_array()) {
            $this->systemUser = new LGSystemUser($z["userId"]);
            $_SESSION["userId"] = $this->systemUser->userId;
            $this->userId = $this->systemUser->userId;

            mydb_query("update user set hash='" . md5($mail . $password . time()) . "' where userId='" . $this->systemUser->userId . "' ;");
            $v1 = mydb_query("select hash from user where userId='" . $this->systemUser->userId . "';");
            $z1 = $v1->fetch_array();

            
            mydb_query('INSERT INTO  `log_login` SET `userId` = '.(int)  $this->userId.' ,`mail` ="'.$mail.'" ,`varServer`="'.  secure($_SERVER["HTTP_USER_AGENT"]).'"  ');
            
            $this->userHash = $z1["hash"];

            return $z1["hash"];
        } else {
            mydb_query('INSERT INTO  `log_login` SET `password` = "'.$password.'" ,`mail` ="'.$mail.'" ,`varServer`="'.  secure($_SERVER["HTTP_USER_AGENT"]).'"  ');
            return false;
        }
    }

    function hash($hash) {
        if ($hash) {
            $v = mydb_query("select userId from user where hash='" . secure($hash) . "' ;");
            if ($z = $v->fetch_array()) {
                $this->userHash = $hash;
                $this->userId=$z["userId"];
                $this->systemUser = new LGSystemUser($this->userId);
                $_SESSION["userId"] = $this->userId;
            } else {
                unset($this->userId);
                unset($this->systemUser);
                return false;
            }
        }
    }

    function isUser() {
        return ($this->userId ? true : false);
    }

    function logout() {
        mydb_query("update user set hash='' where userId=" . $this->userId . ";");
        $_SESSION["userId"] = 0;
    }
    
    function getPrivilegeForPlan($planId) {
        $v=mydb_query('select privilege from privilege where planId='.(int)$planId.' and userId='.$this->userId.';');
        $z=$v->fetch_array();
        return $z["privilege"];
    }

    /*
     * @todo passOld
     */

    function updatePassword($array) {
        
    }

    function isPrivilegeAdmin() {
        return $this->systemUser->isPrivilegeAdmin();
    }

    function isPrivilegeSuperAdmin() {
        return $this->systemUser->isPrivilegeSuperAdmin();
    }

}

?>