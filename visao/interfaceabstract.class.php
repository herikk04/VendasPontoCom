<?php
require_once "htmldoctypehtml5.class.php";
require_once "html.class.php";
require_once "htmlhead.class.php";
require_once "htmltitle.class.php";
require_once "htmllink.class.php";
require_once "htmlmeta.class.php";
require_once "htmlscript.class.php";
require_once "htmlbody.class.php";
require_once "htmldiv.class.php";
require_once "htmllegend.class.php";
require_once "htmlfieldset.class.php";
require_once "htmlp.class.php";
require_once "htmlh1.class.php";
require_once "htmlh2.class.php";
require_once "htmlh3.class.php";
require_once "htmlh4.class.php";
require_once "htmlh5.class.php";
require_once "htmlh6.class.php";
// require_once "htmlimg.class.php";
require_once "htmlol.class.php";
require_once "htmlul.class.php";
require_once "htmlli.class.php";
require_once "htmlform.class.php";
require_once "htmllabel.class.php";
require_once "htmlinput.class.php";
require_once "htmltextarea.class.php";
require_once "htmlselect.class.php";
require_once "htmloption.class.php";
require_once "htmla.class.php";
require_once "htmlbutton.class.php";
require_once "htmlbr.class.php";
require_once "htmltable.class.php";
require_once "htmltd.class.php";
require_once "htmltr.class.php";
require_once "htmlth.class.php";

abstract class InterfaceAbstract {
    //Atributos da interface
    private $mensagens = null;
    private $titulo    = null;

    function __construct ($titulo = null) {
        $this->mensagens = array ();
        $this->titulo    = $titulo;
    }

    public function geraInterface () {
        $doctype = $this->montaDoctype ();
        $html    = $this->montaHtml ();

        $htmlCompleto = $doctype->geraCodigoDaTag () . $html->geraCodigoDaTag ();

        echo $htmlCompleto;
    }

    private function montaDoctype () {
        $htmlDoctype = new HtmlDoctypeHtml5();

        return $htmlDoctype;
    }

    private function montaHtml () {
        //Monta sub-tags
        //Title
        $htmlTitle = new HtmlTitle ($this->titulo);
        //Meta
        $htmlMeta  = $this->montaMeta ();
        //Link
        $htmlLink  = $this->montaLink ();

        //DivCabecalho
        $htmlDivCabecalho      = $this->montaDivCabecalho ();
        //DivMenu
        $htmlDivMenu           = $this->montaDivMenu ();
        //DivMensagens
        $htmlDivTituloDoModulo = $this->montaDivTituloDoModulo ();
        //DivMensagens
        $htmlDivMensagens      = $this->montaDivMensagens ();
        //DivConsulta
        $htmlDivConsulta       = $this->montaDivConsulta ();
        //DivConteudo
        $htmlDivConteudo       = $this->montaDivConteudo ();
        //DivRodape
        $htmlDivRodape         = $this->montaDivRodape ();

        //Head
        $htmlHead = $this->montaHead ($htmlTitle, $htmlMeta, $htmlLink);

        //Body
        $htmlBody = $this->montaBody (
                array (
                    $htmlDivCabecalho, $htmlDivMenu, $htmlDivTituloDoModulo,
                    $htmlDivMensagens, $htmlDivConsulta, $htmlDivConteudo,
                    $htmlDivRodape
                )
        );

        //Monta HTML
        $htmlHtml = new Html ($htmlHead, $htmlBody);

        return $htmlHtml;
    }

    private function montaHead ($htmlTitle, $htmlMeta, $htmlLink) {
        $htmlHead = new HtmlHead ($htmlTitle);
        $htmlHead->adicionaMeta ($htmlMeta);
        $htmlHead->adicionaLink ($htmlLink);

        return $htmlHead;
    }

    private function montaMeta () {
        $htmlMeta = new HtmlMeta();
        $htmlMeta->setCharset ("UTF-8");

        return $htmlMeta;
    }

    private function montaLink () {
        $htmlLink = new HtmlLink();
        $htmlLink->setRel ("stylesheet");
        $htmlLink->setType ("text/css");
        //Para o CSS é necessário montar o caminho absoluto pq o módulo que está
        //sendo executado defino o ponto de partida e ele pode estar na raiz do
        //site ou na pasta modulos.
        $htmlLink->setHref ("../css/estilo.css");

        return $htmlLink;
    }

    private function montaBody (Array $tagDoBody) {
        $htmlBody = new HtmlBody();
        $htmlBody->adicionaObjetos ($tagDoBody);

        return $htmlBody;
    }

    private function montaDivCabecalho () {
        $h1 = $this->montaH1 ("Vendas PontoCom", "topo");

        $htmlDivCabecalho = new HtmlDiv();
        $htmlDivCabecalho->setId ("cabecalho");
        $htmlDivCabecalho->adicionaObjeto ($h1);

        return $htmlDivCabecalho;
    }

    private function montaDivMenu () {
        //H1
        $h1  = $this->montaH1 ("MENU");
        //Array de li
        $lis = array ();
        
        //Li 4
        $htmlA  = $this->montaA ("logincliente.php", "Login");
        $htmlLi = new HtmlLi();
        $htmlLi->adicionaObjeto ($htmlA);

        $lis [] = $htmlLi;
        
        //Li 1
        $htmlA  = $this->montaA ("cadastrodecliente.php", "Cadastro de Clientes");
        $htmlLi = new HtmlLi();
        $htmlLi->adicionaObjeto ($htmlA);

        $lis [] = $htmlLi;

        //Li 2
        $htmlA  = $this->montaA ("cadastrodeproduto.php", "Cadastro de Produtos");
        $htmlLi = new HtmlLi();
        $htmlLi->adicionaObjeto ($htmlA);

        $lis [] = $htmlLi;

        //Li 3
        $htmlA  = $this->montaA ("carrinhodecompras.php", "Carrinho de Compras");
        $htmlLi = new HtmlLi();
        $htmlLi->adicionaObjeto ($htmlA);

        $lis [] = $htmlLi;

        //Ul
        $htmlUl = new HtmlUl ();
        $htmlUl->adicionaLis ($lis);

        $htmlDivMenu = new HtmlDiv();
        $htmlDivMenu->setId ("menu");
        $htmlDivMenu->adicionaObjetos (array ($h1, $htmlUl));

        return $htmlDivMenu;
    }

    private function montaH1 ($objeto, $id = null) {
        $h1 = new HtmlH1();
        $h1->setId ($id);
        $h1->adicionaObjeto ($objeto);

        return $h1;
    }

    private function montaA ($href, $objeto) {
        $htmlA = new HtmlA();
        $htmlA->setHref ($href);
        $htmlA->adicionaObjeto ($objeto);

        return $htmlA;
    }

    /**
     * Monta uma div para o título do módulo.
     * 
     * @return \HtmlDiv Html com a div com as mensagens
     */
    final protected function montaDivTituloDoModulo () {
        $divTituloDoModulo = new HtmlDiv();
        $divTituloDoModulo->setId ("tituloDoModulo");

        $htmlH1 = new HtmlH1 ();
        $htmlH1->adicionaObjeto ($this->titulo);

        $divTituloDoModulo->adicionaObjeto ($htmlH1);

        return $divTituloDoModulo;
    }

    /**
     * Monta uma div com as possíveis mensagens para o usuário.
     * 
     * @return \HtmlDiv Html com a div com as mensagens
     */
    final protected function montaDivMensagens () {
        $mensagens = array ();

        foreach ($this->mensagens as $mensagem) {
            $pMensagem = new HtmlP();
            $pMensagem->adicionaObjeto ($mensagem);

            $mensagens [] = $pMensagem;
        }

        $divMensagens = new HtmlDiv();
        $divMensagens->setId ("mensagens");

        if (count ($mensagens) > 0) {
            $htmlLegend   = new HtmlLegend ("Mensagens");
            $htmlFieldset = new HtmlFieldset ($htmlLegend);
            $htmlFieldset->adicionaObjetos ($mensagens);
            $divMensagens->adicionaObjeto ($htmlFieldset);
        }

        return $divMensagens;
    }

    /**
     * Monta uma div para as consultas inicialmente nula e caso o módulo precise
     * implementar consultas basta sobreescrever este módulo.
     * 
     * @return type Div com as tags para a consulta.
     */
    protected function montaDivConsulta () {
        return null;
    }

    abstract protected function montaDivConteudo ();

    private function montaDivRodape () {
        $htmlP = new HtmlP();
        $htmlP->adicionaObjeto ("Insira no rodap&eacute; informa&ccedil;&atilde;es sobre a empresa como contatos, endere&ccedil;os, etc.");

        $htmlDivRodape = new HtmlDiv();
        $htmlDivRodape->setId ("rodape");
        $htmlDivRodape->adicionaObjeto ($htmlP);

        return $htmlDivRodape;
    }

    private function setTitulo ($titulo) {
        $this->titulo = $titulo;
    }

    /**
     * Monta e retorna os dados da interface no ato da consulta. 
     */
    abstract public function recebeChaveDaConsulta ();

    /**
     * Monta e retorna os dados da interface no objeto model ou em uma StdClass.
     */
    abstract public function recebeDadosDaInterface ();

    /**
     * Recupera o valor do botão clicado ou, caso não tenha sido clicado nenhum
     * botão, retorna "nova".
     * 
     * @return string Valor do botão clicado ou "nova"
     */
    public function getAcao () {
        if (isset ($_POST['bt'])) {
            return $this->getValorOuNull ('bt');
        } else {
            return "nova";
        }
    }

    protected function getValorOuNull ($post) {
        //Checa se o dado existe.
        if (isset ($_REQUEST[$post])) {
            return $this->limpaDadoDeSqlInjections ($_REQUEST[$post]);
        } else {
            return null;
        }
    }

    protected function limpaDadoDeSqlInjections ($dado) {
        $pattern = '/[&]|[!]|[#]|[$]|[%]|[*]|[£]|[¢]|[¬]|[\{]|[\}]|[\>]|[\=]/ui';

        $dado = stripslashes ($dado);

        if (preg_match ($pattern, $dado)) {
            $dado     = htmlentities ($dado, ENT_QUOTES | ENT_HTML5,
                                      $encoding = 'UTF-8');
        }

        $dado = strip_tags ($dado);

        if (preg_match ($pattern, $dado)) {
            $this->adicionaMensagem ("Algum valor foi informado com caracteres especiais, isso pode representar um risco de segurança, por isso ele foi apagado.");
            return null;
        } else {
            return $dado;
        }
    }

    final public function adicionaMensagem ($mensagem) {
        $this->mensagens [] = $mensagem;
    }

    final public function adicionaMensagens (Array $mensagens) {
        foreach ($mensagens as $mensagem) {
            $this->mensagens [] = $mensagem;
        }
    }

}
?>