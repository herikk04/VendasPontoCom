<?php
require_once 'htmlabstract.class.php'; //Importa a classe mãe

class HtmlSelect extends HtmlAbstract {
    //Atributos do select
    private $autofocus = null;
    private $disabled  = null;
    private $form      = null;
    private $multiple  = null;
    private $name      = null;
    private $require   = null;
    private $size      = null;
    //Array de options
    private $options;

    function __construct($class = null, $hidden = null, $id = null,
                         $lang = null, $style = null, $title = null) {
        $this->options = array();
        parent::__construct($class, $hidden, $id, $lang, $style, $title);
    }

    public function geraCodigoDaTag() {
        $meusAtributos = $this->autofocus
                . $this->disabled
                . $this->form
                . $this->multiple
                . $this->name
                . $this->require
                . $this->size;

        return "<select{$this->geraAtributosGlobais()}{$meusAtributos}>{$this->geraOptions()}</select>";
    }

    function adicionaOption(HtmlOption $htmlOption) {
        $this->options [] = $htmlOption;
    }

    function adicionaOptions(Array $htmlOptions) {
        foreach ($htmlOptions as $htmlOption) {
            $this->adicionaOption($htmlOption);
        }
    }

    private function geraOptions() {
        return $this->geraCodigoDosObjetos($this->options);
    }

    function setAutofocus($autofocus = true) {
        if ($autofocus) {
            $this->autofocus = " autofocus";
        } else {
            $this->autofocus = null;
        }
    }

    function setDisabled($disabled = true) {
        if ($disabled) {
            $this->disabled = " disabled";
        } else {
            $this->disabled = null;
        }
    }

    function setForm($form) {
        $this->form = " form='{$form}'";
    }

    function setMultiple($multiple = ture) {
        if ($multiple) {
            $this->multiple = " multiple";
        } else {
            $this->multiple = null;
        }
    }

    function setName($name) {
        $this->name = " name='{$name}'";
    }

    function setRequire($require) {
        if ($require) {
            $this->require = " require";
        } else {
            $this->require = null;
        }
        $this->require = $require;
    }

    function setSize(int $size) {
        $this->size = " size='{$size}'";
    }

}