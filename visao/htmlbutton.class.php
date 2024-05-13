<?php
require_once 'htmlabstract.class.php'; //Importa a classe mãe

class HtmlButton extends HtmlAbstract {
    //Declarei apenas os atributos mais utilizados para efeito didático.
    protected $name  = null;
    protected $type  = null;
    protected $value = null;
    //Texto a ser apresentado no botão.
    protected $texto = null;

    function __construct($name = null, $type = null, $value = null,
                         $texto = null, $class = null, $hidden = null,
                         $id = null, $lang = null, $style = null, $title = null) {
        $this->setName($name);
        $this->setType($type);
        $this->setValue($value);
        $this->texto = $texto;
        parent::__construct($class, $hidden, $id, $lang, $style, $title);
    }

    public function geraCodigoDaTag() {
        $html = "<button {$this->name}{$this->type}{$this->value}{$this->geraAtributosGlobais()}>"
                . $this->texto . "</button>";
        return $html;
    }

    function setName($name) {
        if (is_null($name)) {
            $this->name = null;
        } else {
            $this->name = " name='{$name}'";
        }
    }

    function setType($type) {
        if (is_null($type)) {
            $this->type = null;
        } else {
            $this->type = " type='{$type}'";
        }
    }

    function setValue($value) {
        if (is_null($value)) {
            $this->value = null;
        } else {
            $this->value = " value='{$value}'";
        }
    }

    function setTexto($texto) {
        $this->texto = $texto;
    }

}