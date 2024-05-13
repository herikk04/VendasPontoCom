<?php
require_once 'htmlabstract.class.php'; 

class HtmlTable extends HtmlAbstract {
    
    private $trs = null;

    public function __construct($class = null,
                                $hidden = null, $id = null, $lang = null,
                                $style = null, $title = null) {
        $this->trs = array();
        parent::__construct($class, $hidden, $id, $lang, $style, $title);
    }

    public function geraCodigoDaTag() {
        return "<table>{$this->geraAtributosGlobais()}{$this->geraTrs()}</table>";
    }
    
    function addTr($htmlTr) {
        $this->trs[]= $htmlTr;
    }
    
    function addTrs(Array $htmlTrs) {
        foreach ($htmlTrs as $htmlTr) {
            $this->trs[]= $htmlTr;
        }
    }
    
    private function geraTrs() {
        return $this->geraCodigoDosObjetos($this->trs);
    }
}
?>