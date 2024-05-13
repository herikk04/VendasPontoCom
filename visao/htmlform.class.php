<?php
require_once 'htmlabstract.class.php';

class HtmlForm extends HtmlAbstract {
    //Tem que declarar todos os atributos.
    //Declarei apenas o essencial para testar.
    private $action = null;
    private $method = null;

    public function geraCodigoDaTag() {
        $html = "\n\t<form{$this->geraAtributosGlobais()}{$this->action}{$this->method}>";
        $html .= $this->geraCodigoDasSubTags();
        $html .= "\n\t</form>";
        return $html;
    }

    function setAction($action) {
        $this->action = " action='{$action}'";
    }

    function setMethod($method) {
        $this->method = " method='{$method}'";
    }

}
?>