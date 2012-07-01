<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LGFormManager
 *
 * @author JakubDubrovsky
 */
class LGFormManager {

    /**
     *
     * @var LGUser 
     */
    var $user;

    /**
     *
     * @param LGUser $user 
     */
    public function __construct($user) {
        $this->user = $user;
    }

    /**
     *
     * @param type $planId
     * @return array 
     */
    function getFormsByPlan($planId) {
        $temp = array();
        $v = mydb_query('select formId from form where layerId in (select layerId from layer where planId=' . (int) $planId . ');');
        while ($z = $v->fetch_array()) {
            $temp[] = $z['formId'];
        }
        return $temp;
    }

    /**
     *
     * @param type $planId
     * @return array 
     */
    function getForms() {
        $temp = array();
        $v = mydb_query('select formId from form where layerId in (select layerId from layer where planId in (select planId from plan where planId in (select planId from privilege where userId=' . (int) $this->user->userId . ')));');
         while ($z = $v->fetch_array()) {
            $temp[] = $z['formId'];
        }
        return $temp;
    }

    function addForm($array) {
        mydb_insertValuesInTab($array, 'form');
        //mydb_query('INSERT INTO  `form` SET `title`="' . secure($array['title']) . '" ,`txt`="' . secure($array['txt']) . '" ,`layerId`=' . (int) $array['layerId'] . ' ;');
    }

    function updateForm($array) {
        mydb_updateValuesInTab($array, 'form');
        //mydb_query('UPDATE  `form` SET `title`="' . secure($array['title']) . '" ,`txt`="' . secure($array['txt']) . '" ,`layerId`=' . (int) $array['layerId'] . ' WHERE formId=' . (int) $array['formId'] . ' ;');
    }

    function removeForm($formId) {
        mydb_query('delete from form where formId=' . (int) $formId . ';');
    }

    function getFormEements() {
        $temp = array();
        $v = mydb_query('select formElementId from formElement where supported=1 order by title;');
        while ($z = $v->fetch_array()) {
            $temp[] = $z['formElementId'];
        }
        return $temp;
    }

}

?>