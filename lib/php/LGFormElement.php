<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LGFormElement
 *
 * @author JakubDubrovsky
 */
class LGFormElement {
 var $title;
 var $name;
 var $txt;
 var $elementSettings;
 var $formElementId;
 
    public function __construct($formElementId) {
        $v=mydb_query('select * from formElement where formElementId='.(int)$formElementId.';');
        while($z=$v->fetch_array()){
            $this->formElementId=$formElementId;
            $this->title=$z['title'];
            $this->name=$z['name'];
            $this->txt=$z['txt'];
            $this->elementSettings=$z['elementSettings'];
        }
    }
 }

?>