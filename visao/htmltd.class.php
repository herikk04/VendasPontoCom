<?php
require_once 'htmlabstract.class.php';

class HtmlTd extends HtmlAbstract {
    
    private $texto = null;

    public function geraCodigoDaTag () {
        $codMiddleTags = $this->geraCodigoDasSubTags();
        $html = "<td{$this->geraAtributosGlobais()}>{$codMiddleTags}{$this->texto}</td>";
        return $html;
    }

    function setTexto ($texto) {
        $this->texto = $texto;
    }
}
?>