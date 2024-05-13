<?php
require_once 'htmlabstract.class.php'; //Importa a classe mãe

class HtmlDiv extends HtmlAbstract {

    public function geraCodigoDaTag() {
        return "<div{$this->geraAtributosGlobais()}>{$this->geraCodigoDasSubTags()}</div>";
    }

}