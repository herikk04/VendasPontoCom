<?php
require_once 'htmlabstract.class.php'; 

class HtmlTr extends HtmlAbstract {
    
    private $ths = null;
    private $tds = null;

    public function __construct($class = null, $hidden = null, $id = null,
                                $lang = null, $style = null, $title = null) {
        $this->ths = array();
        $this->tds = array();
        parent::__construct($class, $hidden, $id, $lang, $style, $title);
    }

    public function geraCodigoDaTag() {
        $htmltexto = $this->geraCodigoDasSubTags();
        return "<tr>{$this->geraAtributosGlobais()}{$htmltexto}{$this->geraTodasTags()}</tr>";
    }
 
    function addTh(HtmlTh $htmlTh) {
        $this->ths[]= $htmlTh;
    }
    
    function addThs(Array $htmlThs) {
        foreach ($htmlThs as $htmlTh) {
            $this->addTh($htmlTh);
        }
    }
    
    function addTd(HtmlTd $htmlTd) {
        $this->tds[]= $htmlTd;
    }
    
    function addTds(Array $htmlTds) {
        foreach ($htmlTds as $htmlTd) {
            $this->addTd($htmlTd);
        }
    }
    
    function geraThs() {
        return $this->geraCodigoDosObjetos($this->ths);
    }
    
    function geraTds() {
        return $this->geraCodigoDosObjetos($this->tds);
    }
    
    function geraTodasTags() {
        return $this->geraThs() . $this->geraTds();
    }
}
?>