<?php
require_once 'htmlabstract.class.php'; //Importa a classe mãe

class HtmlScript extends HtmlAbstract {
    private $async   = null;
    private $charset = null;
    private $defer   = null;
    private $src     = null;
    private $type    = null;

    public function geraCodigoDaTag() {
        return "<script{$this->geraAtributosGlobais()}"
                . "{$this->async}{$this->charset}{$this->defer}{$this->src}"
                . "{$this->type}></script>";
    }

    function setAsync($async) {
        $this->async = " async='{$async}'";
    }

    function setCharset($charset) {
        $this->charset = " charset='{$charset}'";
    }

    function setDefer($defer) {
        $this->defer = " defer='{$defer}'";
    }

    function setSrc($src) {
        $this->src = " src='{$src}'";
    }

    function setType($type) {
        $this->type = " type='{$type}'";
    }

}