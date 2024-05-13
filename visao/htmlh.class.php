<?php
require_once 'htmlabstract.class.php'; //Importa a classe mãe

abstract class HtmlH extends HtmlAbstract {
    protected $n = 1;
    private $texto = null;
    
    public function setTexto($texto){
        $this->texto = $texto; 
    }
    
    public function geraCodigoDaTag() {
        $codigoEntreTag = $this->geraCodigoDasSubTags();
        $codigoHtml = "<h{$this->n}{$this->geraAtributosGlobais()}>{$codigoEntreTag}{$this->texto}</h{$this->n}>";

        return $codigoHtml;
    }

}