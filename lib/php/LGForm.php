<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LGForm
 *
 * @author JakubDubrovsky
 */
class LGForm {

    var $formId;
    var $layerId;
    var $title;
    var $txt;
    var $autoupdate;

    public function __construct($formId) {
        $v = mydb_query('select * from form  where formId=' . (int) $formId . ';');
        while ($z = $v->fetch_array()) {
            $this->formId = $z['formId'];
            $this->layerId = $z['layerId'];
            $this->title = $z['title'];
            $this->txt = $z['txt'];
            $this->autoupdate = $z['autoupdate'];
        }
    }

    function getFormItems() {
        $temp = array();
        $v = mydb_query('select formItemId from formItem where formId=' . (int) $this->formId . ' ;');

        while ($z = $v->fetch_array()) {
            $temp[] = $z['formItemId'];
        }

        return $temp;
    }

    function addFormItem($array) {
        mydb_query('INSERT INTO `formItem` set `title`="' . $array['title'] . '", `name`="' . $array['name'] . '", `placeholder`="' . $array['placeholder'] . '", `type`="' . $array['type'] . '", `default`="' . $array['default'] . '", `required`="' . (int) $array['required'] . '", `options`="' . $array['options'] . '", `formId`="' . (int) $array['formId'] . '", `formItemId`="' . (int) $array['formItemId'] . '";');
    }

    function updateFormItem($array) {
        mydb_query('update `formItem` set `title`="' . $array['title'] . '", `name`="' . $array['name'] . '", `placeholder`="' . $array['placeholder'] . '", `type`="' . $array['type'] . '", `default`="' . $array['default'] . '", `required`="' . (int) $array['required'] . '", `options`="' . $array['options'] . '", `formId`="' . (int) $array['formId'] . '" where `formItemId`="' . (int) $array['formItemId'] . '";');
    }

    function removeFormItem($formItemId) {
        mydb_query('delete from `formItem`  where `formItemId`="' . (int) $formItemId . '";');
    }

    function toDictionaryWithFormElements() {
        $temp = array();
        $v = mydb_query('select formItemId from formItem where formId=' . (int) $this->formId . ' ;');

        while ($z = $v->fetch_array()) {
            $formItem = new LGFormItem($z['formItemId']);
            $temp[] = $formItem->toDictionary();
        }

        return $temp;
    }

    /*
      function collectData($jsonData, $userId) {
      mydb_query('insert into formData set userId=' . (int) $userId . ', formId=' . $this->formId . ',data="' . secure($jsonData) . '";');
      return mydb_insert_id();
      }
     */

    function toDictionary() {

        //   $v = mydb_query('select formItemId from formItem where formId=' . (int) $this->formId . ' ;');

        $temp->formId = $this->formId;
        $temp->layerId = $this->layerId;
        $temp->title = $this->title;
        $temp->txt = $this->txt;
        $temp->autoupdate = $this->autoupdate;

        return $temp;
    }

}

?>