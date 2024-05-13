<?php
require_once 'htmlabstract.class.php';

class HtmlFieldset extends HtmlAbstract {
    private $disable = null;
    private $form    = null;
    private $name    = null;
    private $legend  = null;

    function __construct ($legend = null, $class = null, $hidden = null, $id = null,
                          $lang = null, $style = null, $title = null) {
        parent::__construct ($class, $hidden, $id, $lang, $style, $title);
        
        $this->legend = $legend;
    }

    public function geraCodigoDaTag () {
        $html = "<fieldset{$this->geraAtributosGlobais ()}{$this->disable}{$this->form}{$this->name}>";
        if (is_null ($this->legend)) {
            //se não tem legenda não precisa fazer nada.
        } else {
            $html .= $this->legend->geraCodigoDaTag ();
        }
        $html .= $this->geraCodigoDasSubTags ();
        $html .= "</fieldset>";
        return $html;
    }

    function setDisable ($disable = true) {
        if ($disable) {
            $this->disable = " disable";
        } else {
            $this->disable = null;
        }
    }

    function setForm ($form) {
        $this->form = " form='{$form}'";
    }

    function setName ($name) {
        $this->name = " name='{$name}'";
    }

    function setLegend (HtmlLegend $legend) {
        $this->legend = $legend;
    }

}
?>