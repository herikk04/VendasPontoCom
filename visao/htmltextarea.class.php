<?php
require_once 'htmlabstract.class.php';

class HtmlTextarea extends HtmlAbstract {
    //Tem que declarar todos os atributos.
    //Declarei apenas o essencial para testar.
    private $autofocus   = null;
    private $cols        = null;
    private $dirname     = null;
    private $disabled    = null;
    private $form        = null;
    private $maxlength   = null;
    private $name        = null;
    private $placeholder = null;
    private $required    = null;
    private $rows        = null;
    private $wrap        = null;
    //guarda texto que vai no textarea.
    private $texto       = null;

    public function geraCodigoDaTag () {
        $html = "<textarea{$this->geraAtributosGlobais ()}";
        $html .= "{$this->autofocus}{$this->cols}{$this->dirname}";
        $html .= "{$this->disabled}{$this->form}{$this->maxlength}";
        $html .= "{$this->name}{$this->placeholder}{$this->required}";
        $html .= "{$this->rows}{$this->wrap}>{$this->texto}</textarea>";
        
        return $html;
    }

    function setAutofocus ($autofocus = true) {
        if ($autofocus) {
            $this->autofocus = " autofocus";
        } else {
            $this->autofocus = null;
        }
    }

    function setCols ($cols) {
        $this->cols = " cols='{$cols}'";
    }

    function setDirname ($dirname) {
        $this->dirname = " dirname='{$dirname}'";
    }

    function setDisabled ($disabled = true) {
        if ($disabled) {
            $this->disabled = " disabled";
        } else {
            $this->disabled = null;
        }
    }

    function setForm ($form) {
        $this->form = " form='{$form}'";
    }

    function setMaxlength ($maxlength) {
        $this->maxlength = " maxlength='{$maxlength}'";
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

    function setRequired ($required = true) {
        if ($required) {
            $this->required = " readonly";
        } else {
            $this->required = null;
        }
    }

    function setRows ($rows) {
        $this->rows = " rows='{$rows}'";
    }

    function setWrap ($wrap) {
        $this->wrap = " wrap='{$wrap}'";
    }

    function setTexto ($texto) {
        $this->texto = $texto;
    }

}
?>