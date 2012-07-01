<?php

class LGlang {

    var $default;
    var $current;

    function __construct() {
        //zjistim lang
        $this->current = "cs";
        $this->default = $GLOBALS["LGSettings"]->lang_default;
        //podivam se do tab lang_list if exists
        //else pouziju en
        //v tab lang_bundle budou ulozeny name | lang | title , primary (lang,name)
        ;
    }

    function get($name) {
        $v = mydb_query('(select title from lang_bundle where lang="' . $this->current . '" and name="' . $name . '" ) UNION select title from lang_bundle where  lang="' . $this->default . '" and name="' . $name . '" ) ;');
        while ($z = $v->fetch_array()) {
            return $z["title"];
        }
    }

}
$LGLang = new LGlang();
?>
