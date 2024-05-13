<?php
require_once 'htmlabstract.class.php';

class HtmlLabel extends HtmlAbstract {
    private $for = null;
    private $form = null;

    public function geraCodigoDaTag() {
        $html = "\n\t<label{$this->geraAtributosGlobais()}{$this->for}{$this->form}>";
        $html .= $this->geraCodigoDasSubTags();
        $html .= "\n\t</label>";
        return $html;
    }

    function setFor($for) {
        $this->for = " for='" . $for . "'";
    }

    function setForm($form) {
        $this->form = " form='" . $form . "'";
    }

}
?>