<?php
require_once 'htmlabstract.class.php'; //Importa a classe mãe

class HtmlMeta extends HtmlAbstract {
    private $charset   = null;
    private $content   = null;
    private $httpEquiv = null;
    private $name      = null;

    public function geraCodigoDaTag() {
        return "<meta{$this->geraAtributosGlobais()}{$this->charset}"
                . "{$this->content}{$this->httpEquiv}{$this->name}>";
    }

    function setCharset($charset) {
        $this->charset = " charset='{$charset}'";
    }

    function setContent($content) {
        $this->content = " content='{$content}'";
    }

    function setHttpEquiv($httpEquiv) {
        $this->httpEquiv = " http-equiv='{$httpEquiv}'";
    }

    function setName($name) {
        $this->scheme = " name='{$name}'";
    }

}