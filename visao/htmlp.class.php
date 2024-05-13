<?php
require_once 'htmlabstract.class.php';

class HtmlP extends HtmlAbstract {

    public function geraCodigoDaTag() {
        return "<p{$this->geraAtributosGlobais()}>{$this->geraCodigoDasSubTags()}</p>";
    }

}