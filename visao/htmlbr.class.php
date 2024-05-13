<?php
require_once 'htmlabstract.class.php'; //Importa a classe mãe

class HtmlBr extends HtmlAbstract {

    public function geraCodigoDaTag() {
        return "<br{$this->geraAtributosGlobais()}>";
    }

}