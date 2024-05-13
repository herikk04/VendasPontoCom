<?php
require_once 'htmlabstract.class.php';

class HtmlLi extends HtmlAbstract {
    //Atributo da tag li
    private $value = null;


    function __construct($texto = null, $value = null, $class = null,
                         $hidden = null, $id = null, $lang = null,
                         $style = null, $title = null) {
        parent::__construct($class, $hidden, $id, $lang, $style, $title);
        $this->setValue($value);
    }

    public function geraCodigoDaTag() {
        return "<li{$this->geraAtributosGlobais()}>{$this->geraCodigoDasSubTags()}</li>";
    }

    function setValue($value = null) {
        if (is_null($value)) {
            $this->value = null;
        } else {
            $this->value = " value='" . $value . "'";
        }
    }

}