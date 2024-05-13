<?php
require_once 'htmlabstract.class.php'; //Importa a classe mãe

class HtmlTitle extends HtmlAbstract {
    //Este atributo guarda o texto do dítulo e não identifiquei-o como $title 
    //porque a classe mãe já possue um atributo com esse identificador.
    private $textoDoTitulo = null;

    public function __construct($textoDoTitulo = null, $class = null,
                                $hidden = null, $id = null, $lang = null,
                                $style = null, $title = null) {
        $this->textoDoTitulo = $textoDoTitulo;
        parent::__construct($class, $hidden, $id, $lang, $style, $title);
    }

    public function geraCodigoDaTag() {
        return "<title{$this->geraAtributosGlobais()}>{$this->textoDoTitulo}</title>";
    }

    function setTextoDoTitulo($textoDoTitulo) {
        $this->textoDoTitulo = $textoDoTitulo;
    }

}