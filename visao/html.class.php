<?php
require_once 'htmlabstract.class.php'; //Importa a classe mãe

class Html extends HtmlAbstract {//Herança
    //A tag HTML só tem duas tags associadas a ela e são obrigatórias, por isso a
    //associação neste caso é explícita.
    private $htmlHead;
    private $htmlBody;
    //Único atributo desta tag.
    private $xmlns = null;

    function __construct (HtmlHead $htmlHead, HtmlBody $htmlBody, $xmlns = null,
                          $class = null, $hidden = null, $id = null,
                          $lang = null, $style = null, $title = null) {
        $this->htmlHead = $htmlHead;
        $this->htmlBody = $htmlBody;

        $this->setXmlns ($xmlns);

        parent::__construct ($class, $hidden, $id, $lang, $style, $title);
    }

    public function geraCodigoDaTag () {
        $codigoHtml = $this->htmlHead->geraCodigoDaTag () . $this->htmlBody->geraCodigoDaTag ();

        return "<html{$this->geraAtributosGlobais ()}{$this->xmlns}>{$codigoHtml}</html>";
    }

    function setHead (HtmlHead $head) {
        $this->htmlHead = $head;
    }

    function setBody (HtmlBody $body) {
        $this->htmlBody = $body;
    }

    function setXmlns ($xlns = null) {
        if (is_null ($xlns)) {
            $this->xmlns = null;
        } else {
            $this->xmlns = " xlns='{$xlns}'";
        }
    }

}