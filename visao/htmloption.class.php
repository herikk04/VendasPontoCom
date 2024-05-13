<?php
require_once 'htmlabstract.class.php';

class HtmlOption extends HtmlAbstract {
    private $disabled    = null;
    private $label       = null;
    private $selected    = null;
    private $value       = null;
    private $texto       = null;
    
    function __construct ($disabled = null, $label = null, $selected = null,
                          $value = null, $class = null, $hidden = null,
                          $id = null, $lang = null, $style = null, $title = null) {
        $this->setDisabled ($disabled);
        $this->label = $label;
        $this->setSelected ($selected);
        $this->value = $value;

        parent::__construct ($class, $hidden, $id, $lang, $style, $title);
    }

    public function geraCodigoDaTag () {
        $meusAtributos = $this->disabled . $this->label . $this->selected . $this->value;
        $codigoHtml    = $this->geraCodigoDasSubTags ();

        return "<option{$this->geraAtributosGlobais ()}{$meusAtributos}>{$codigoHtml}</option>";
    }

    function setDisabled ($disabled = true) {
        if ($disabled) {
            $this->disabled = " disabled";
        } else {
            $this->disabled = null;
        }
    }

    function setTexto($texto){
        $this->texto = $texto; 
    }
    
    function setLabel ($label) {
        $this->label = " label='{$label}'";
    }

    function setSelected ($selected = true) {
        if ($selected) {
            $this->selected = " selected";
        } else {
            $this->selected = null;
        }
    }

    function setValue ($value) {
        $this->value = " value='{$value}'";
    }

}