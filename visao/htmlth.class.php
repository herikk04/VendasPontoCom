<?php
require_once 'htmlabstract.class.php'; 

class HtmlTh extends HtmlAbstract {
    
    private $texto = null;

    public function geraCodigoDaTag () {
        $codMiddleTags = $this->geraCodigoDasSubTags();
        $html = "<th{$this->geraAtributosGlobais()}>{$codMiddleTags}{$this->texto}</th>";
        return $html;
    }

    function setTexto ($texto) {
        $this->texto = $texto;
    }
}
?>