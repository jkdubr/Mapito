<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LGFormItem
 *
 * @author JakubDubrovsky
 */
class LGFormItem {

    var $title;
    var $name;
    var $placeholder;
    var $type;
    var $default;
    var $required;
    var $options;
    var $formId;
    var $formItemId;

    public function __construct($formItemId) {
        $v = mydb_query('select * from formItem where formItemId=' . (int) $formItemId . ';');
        while ($z = $v->fetch_array()) {
            $this->title = $z['title'];
            $this->name = $z['name'];
            $this->placeholder = $z['placeholder'];
            $this->type = $z['type'];
            $this->default = $z['default'];
            $this->required = $z['required'];
            $this->options = $z['options'];
            $this->formId = $z['formId'];
            $this->formItemId = $formItemId;
        }
    }

    function toDictionary() {
        $temp->title = $this->title;
        $temp->name = $this->name;
        $temp->type = $this->type;
        $temp->required = $this->required;
        $temp->placeholder = $this->placeholder;
        $temp->default = $this->default;
        $temp->options = json_decode($this->options);

        return $temp;
    }

}

?>