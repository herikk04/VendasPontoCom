<?php
require_once 'htmlabstract.class.php';

class HtmlLegend extends HtmlAbstract {
    private $legenda = null;

    function __construct ($legenda = null, $class = null, $hidden = null,
                          $id = null, $lang = null, $style = null, $title = null) {
        parent::__construct ($class, $hidden, $id, $lang, $style, $title);
        $this->legenda = $legenda;
    }

    public function geraCodigoDaTag () {
        $html = "<legend{$this->geraAtributosGlobais ()}>";
        $html .= $this->legenda;
        $html .= "</legend>";
        return $html;
    }

    function setLegenda ($legenda) {
        $this->legenda = $legenda;
    }

}
?>