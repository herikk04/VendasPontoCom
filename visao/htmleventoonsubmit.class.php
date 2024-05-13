<?php
class HtmlEventoOnsubmit {
    private $onsubmit = null;
    function __construct($onsubmit = null) {
        $this->setOnsubmit($onsubmit);
    }
    function setOnsubmit($onsubmit) {
        $this->onsubmit = " onsubmit='{$onsubmit}'";
    }
    function geraCodigoDoEvento() {
        return $this->onsubmit;
    }
}