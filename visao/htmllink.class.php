<?php
require_once 'htmlabstract.class.php'; //Importa a classe mãe

class HtmlLink extends HtmlAbstract {
    //Link possue 9 atributos próprios. Declarei os mais importantes aqui para 
    //efeito didático. Link não possue subtags. 
    //Veja em: https://www.w3schools.com/tags/tag_link.asp
    private $crossorigin = null;
    private $href        = null;
    private $hreflang    = null;
    private $media       = null;
    private $rel         = null;
    private $sizes       = null;
    private $type        = null;

    public function geraCodigoDaTag() {
        return "<link{$this->geraAtributosGlobais()}"
                . "{$this->crossorigin}{$this->href}{$this->hreflang}"
                . "{$this->media}{$this->rel}{$this->sizes}{$this->type}>";
    }

    //Declarei somente os sets para efeito didático.
    function setCrossorigin($crossorigin) {
        $this->crossorigin = " crossoorigin='{$crossorigin}'";
    }

    function setHref($href) {
        $this->href = " href='{$href}'";
    }

    function setHreflang($hreflang) {
        $this->hreflang = " hreflang='{$hreflang}'";
    }

    function setMedia($media) {
        $this->media = " media='{$media}'";
    }

    function setRel($rel) {
        $this->rel = " rel='{$rel}'";
    }

    function setSizes($sizes) {
        $this->sizes = " sizes='{$sizes}'";
    }

    function setType($type) {
        $this->type = " type='{$type}'";
    }

}