<?php
//Classe que permite guardar todos os eventos de cada tag.
require_once 'htmleventosglobais.class.php';

abstract class HtmlAbstract {
    //Atributos Globais (não estão todos para efeito didático)
    private $class  = null;
    private $hidden = null;
    private $id     = null;
    private $lang   = null;
    private $style  = null;
    private $title  = null;
    //Este atributo guarda o objeto com os atributos de eventos globais e 
    //definidos de acordo como tipo da tag.
    //Veja mais: (https://www.w3schools.com/tags/ref_eventattributes.asp)
    public $htmlEventosGlobais;
    //Objetos HTML organizados hierarquicamente
    private $tags;

    //O construtor já recebe os valores dos atributos globais desejados pra tag.
    function __construct($class = null, $hidden = null, $id = null,
                         $lang = null, $style = null, $title = null) {
        $this->setClass($class);
        $this->setHidden($hidden);
        $this->setId($id);
        $this->setLang($lang);
        $this->setStyle($style);
        $this->setTitle($title);
        //Objeto que guarda todos os eventos globais;
        $this->htmlEventosGlobais = new HtmlEventosGlobais();
        //Tags é o array contendo objetos de tags que podem estar contidos na 
        //tag maior.
        $this->tags               = array();
    }

    //Este método deve ser implementado pela classe de cada tag cee acordo com o 
    //seu padrão
    abstract public function geraCodigoDaTag();

    //Este método será utilizado para a geração do código HTML das sub-tags.
    //Ele está protegido pq será utlizado apenas pelas classes filhas. 
    protected function geraCodigoDasSubTags() {
        if (is_null($this->tags)) {
            return null;
        }
        return $this->geraCodigoDosObjetos($this->tags);
    }

    //Este método complementa o método acima. Ele implementa uma recursividade e
    //evita duplicidade no código. 
    protected function geraCodigoDosObjetos(Array $objetos) {
        $string = null;
        //Este laço percorre todas as posições do array.
        foreach ($objetos as $objeto) {
            //Se encontrar um objeto, executa-se o método geraCodigo deste.
            if (is_object($objeto)) {
                $string .= $objeto->geraCodigoDaTag();
            } else if (is_array($objeto)) {
                //Neste ponto é implementada uma recursividade, ou seja, este 
                //método chama a si mesmo quando é detectado um array dentro do 
                //array maior.
                $string .= $this->geraCodigoDosObjetos($objeto);
            } else {
                //Quando o que foi recuperado no array não é nem objeto e nem 
                //array assume-se que seja uma string.
                $string .= $objeto;
            }
        }
        return $string;
    }

    //Este método gera a string com atributos globais. Ela precisa ser utilizada 
    //na classe filha quando se for gerar o código da tag.
    protected function geraAtributosGlobais() {
        $atributosGlobais   = $this->class . $this->hidden . $this->id
                . $this->lang . $this->style . $this->title;
        $atributosDeEventos = null;
        if (is_null($this->htmlEventosGlobais)) {
            //continua...
        } else {
            $atributosDeEventos = $this->htmlEventosGlobais->geraCodigoDosEventos();
        }
        return $atributosGlobais . $atributosDeEventos;
    }

    //O escopo é público quando não declarado.
    //Este método adiciona um objeto ao array de objetos.
    function adicionaObjeto($objeto) {
        $this->tags[] = $objeto;
    }

    //Este método adiciona um array de objetos.
    function adicionaObjetos(Array $objetos) {
        foreach ($objetos as $objeto) {
            $this->adicionaObjeto($objeto);
        }
    }

    function setClass($class = null) {
        if (is_null($class) || trim($class) === "") {
            $this->class = NULL;
        } else {
            $this->class = " class='{$class}'";
        }
    }

    function setHidden($hidden = true) {
        if (is_null($hidden) || trim($hidden) === "") {
            $this->hidden = NULL;
        } else {
            if ($hidden) {
                $this->hidden = " hidden";
            } else {
                $this->hidden = NULL;
            }
        }
    }

    function setId($id = null) {
        if (is_null($id) || trim($id) === "") {
            $this->id = NULL;
        } else {
            $this->id = " id='{$id}'";
        }
    }

    function setLang($lang = null) {
        if (is_null($lang) || trim($lang) === "") {
            $this->lang = NULL;
        } else {
            $this->lang = " lang='{$lang}'";
        }
    }

    function setStyle($style = null) {
        if (is_null($style) || trim($style) === "") {
            $this->style = NULL;
        } else {
            $this->style = " style='{$style}'";
        }
    }

    function setTitle($title = null) {
        if (is_null($title) || trim($title) === "") {
            $this->title = NULL;
        } else {
            $this->title = " title='{$title}'";
        }
    }

    function setHtmlEventosGlobais(HtmlEventosGlobais $htmlEventosGlobais) {
        $this->htmlEventosGlobais = $htmlEventosGlobais;
    }

}
?>