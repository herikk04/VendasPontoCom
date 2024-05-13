<?php
require_once 'htmlabstract.class.php'; //Importa a classe mãe

class HtmlHead extends HtmlAbstract {
    //Principais elementos da tag head. Veja: https://www.w3schools.com/tags/tag_head.asp
    //Como a tag head possue apenas 7 elementos o ideal é representá-los 
    //explícitamente aqui.
    //Implementei 5 para efeitos didáticos.
    private $htmlTitle = null;
    private $links     = null;
    private $metas     = null;
    private $scripts   = null;

    function __construct(HtmlTitle $htmlTitle, $class = null, $hidden = null,
                         $id = null, $lang = null, $style = null, $title = null) {
        parent::__construct($class, $id, $lang, $style, $title, $hidden);
        //Title é usada na maioria das vezes e é simplesmente uma string envolta 
        //pela tag, então achei interessante permitir recebê-la via parâmetro do 
        //construtor. Ainda assim deve ser um objeto.
        $this->htmlTitle = $htmlTitle;

        //Links, Metas e Scripts estão no plural porquê são arrays. Essas tags
        //podem ser declaradas múltiplas vezes dentro da Head e comumente o são.
        $this->links   = array();
        $this->metas   = array();
        $this->scripts = array();
    }

    //Implementação do método abstrato.
    public function geraCodigoDaTag() {
        //Como todo objeto HTML que herda da HtmlAbstract o HtmlTitle tem o método
        //geraCodigoHtml()
        $codigoHtml = $this->htmlTitle->geraCodigoDaTag();
        if (empty($this->links)) {
            //Se o array estiver vazio não precisa fazer nada.
        } else {
            //Para os arrays Links, Metas e Scripts é necessário percorrer todas 
            //as posições do array recuperando os objetos e executando o método 
            //geraCodigoDaTag() deles. Para essas tarefa já existe o método 
            //geraCodigoDosObjetos() da classe mãe. 
            //Isso é reaproveitamento de código.
            $codigoHtml .= $this->geraCodigoDosObjetos($this->links);
        }
        if (empty($this->metas)) {
            //continua...
        } else {
            $codigoHtml .= $this->geraCodigoDosObjetos($this->metas);
        }
        if (empty($this->scripts)) {
            //continua...
        } else {
            $codigoHtml .= $this->geraCodigoDosObjetos($this->scripts);
        }
        return "<head{$this->geraAtributosGlobais()}>{$codigoHtml}</head>";
    }

    function adicionaLink(HtmlLink $link) {
        $this->links [] = $link;
    }

    function adicionaLinks(Array $links) {
        foreach ($links as $link) {
            $this->adicionaLink($link);
        }
    }

    function adicionaMeta(HtmlMeta $meta) {
        $this->metas [] = $meta;
    }

    function adicionaMetas(Array $metas) {
        foreach ($metas as $meta) {
            $this->adicionaMeta($meta);
        }
    }

    function adicionaScript(HtmlScript $script) {
        $this->scripts [] = $script;
    }

    function adicionaScripts(Array $scripts) {
        foreach ($scripts as $script) {
            $this->adicionaScript($script);
        }
    }

}
?>