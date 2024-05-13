<?php
require_once 'htmlabstract.class.php';

class HtmlInput extends HtmlAbstract {
    //Tem que declarar todos os atributos.
    //Declarei apenas o essencial para testar.
    private $disabled    = null;
    private $name        = null;
    private $placeholder = null;
    private $readonly    = null;
    private $type        = null;
    private $value       = null;

    public function geraCodigoDaTag () {
        $html = "\n\t<input{$this->geraAtributosGlobais ()}{$this->disabled}
        {$this->name}{$this->placeholder}{$this->readonly}{$this->type}{$this->value}>";
        $html .= $this->geraCodigoDasSubTags ();
        $html .= "\n\t</input>";
        return $html;
    }

    function setDisabled ($disabled = true) {
        if ($disabled) {
            $this->disabled = " disabled";
        } else {
            $this->disabled = null;
        }
    }

    function setName ($name) {
        $this->name = " name=" . $name;
    }

    function setPlaceholder ($placeholder) {
        $this->placeholder = " placeholder='{$placeholder}'";
    }

    function setReadonly ($readonly = true) {
        if ($readonly) {
            $this->readonly = " readonly";
        } else {
            $this->readonly = null;
        }
    }

    function setType ($type) {
        $this->type = " type='{$type}'";
    }

    function setValue ($value) {
        $this->value = " value='{$value}'";
    }

}
?>