<?php
require_once "htmlabstract.class.php";

class HtmlDoctypeHtml5 extends HtmlAbstract {

    public function geraCodigoDaTag() {
        return "<!DOCTYPE html>";
    }

}