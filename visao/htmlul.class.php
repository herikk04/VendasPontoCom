<?php
require_once 'htmlabstract.class.php'; //Importa a classe mãe

class HtmlUl extends HtmlAbstract {
    //Array de options
    private $lis;

    function __construct($class = null, $hidden = null, $id = null,
                         $lang = null, $style = null, $title = null) {
        parent::__construct($class, $hidden, $id, $lang, $style, $title);
        $this->lis = array();
    }

    public function geraCodigoDaTag() {
        return "<ul{$this->geraAtributosGlobais()}>{$this->geraLis()}</ul>";
    }

    function adicionaLi(HtmlLi $htmlLi) {
        $this->lis [] = $htmlLi;
    }

    function adicionaLis(Array $htmlLis) {
        foreach ($htmlLis as $htmlLi) {
            $this->lis [] = $htmlLi;
        }
    }

    private function geraLis() {
        return $this->geraCodigoDosObjetos($this->lis);
    }

}