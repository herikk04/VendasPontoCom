<?php
require_once 'htmlh.class.php'; //Importa a classe mãe

class HtmlH5 extends HtmlH {

    function __construct($class = null, $hidden = null, $id = null,
                         $lang = null, $style = null, $title = null) {
        parent::__construct($class, $hidden, $id, $lang, $style, $title);
        $this->n = 5;
    }

}