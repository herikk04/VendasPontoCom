<?php
require_once 'htmlabstract.class.php';

class HtmlA extends HtmlAbstract {
    //Atributos do select
    private $download       = null;
    private $href           = null;
    private $hreflang       = null;
    private $media          = null;
    private $ping           = null;
    private $referrerpolicy = null;
    private $rel            = null;
    private $target         = null;
    private $type           = null;

    public function geraCodigoDaTag () {
        $meusAtributos = $this->download
                . $this->href
                . $this->hreflang
                . $this->media
                . $this->ping
                . $this->referrerpolicy
                . $this->rel
                . $this->target
                . $this->type;

        return "<a{$this->geraAtributosGlobais ()}{$meusAtributos}>{$this->geraCodigoDasSubTags ()}</a>";
    }

    function setAutofocus ($autofocus = true) {
        if ($autofocus) {
            $this->autofocus = " autofocus";
        } else {
            $this->autofocus = null;
        }
    }

    function setDownload ($download) {
        if ($download) {
            $this->download = " download";
        } else {
            $this->download = null;
        }
    }

    function setHref ($href) {
        $this->href = " href={$href}";
    }

    function setHreflang ($hreflang) {
        $this->hreflang = " hreflang='{$hreflang}'";
    }

    function setMedia ($media) {
        $this->media = " media='{$media}'";
    }

    function setPing ($ping) {
        $this->ping = " ping='" . $ping . "'";
    }

    function setReferrerpolicy ($referrerpolicy) {
        $this->referrerpolicy = " referrerpolicy='" . $referrerpolicy . "'";
    }

    function setRel ($rel) {
        $this->rel = " rel='" . $rel . "'";
    }

    function setTarget ($target) {
        $this->target = " target='" . $target . "'";
    }

    function setType ($type) {
        $this->type = " type='" . $type . "'";
    }

}