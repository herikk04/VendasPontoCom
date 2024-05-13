<?php
require_once "interfaceabstract.class.php";

class ProdutoView extends InterfaceAbstract {
    //É interessante declarar um objeto ProdutoModel para possibilitar montar os 
    //dados na interface após a consulta.
    private $produtoModel = null;

    function __construct ($titulo = "Cadastro de Produtos") {
        parent::__construct ($titulo);
        $this->produtoModel = new ProdutoModel();
    }

    protected function montaDivConsulta () {
        $htmlDivConsulta = new HtmlDiv();
        //Pode-se usar o mesmo id do conteúdo pois a configuração no CSS é igual.
        $htmlDivConsulta->setId ("conteudo");

        //Monta o fieldset com as entradas de dados uma por parágrafo.
        $htmlLegend   = new HtmlLegend ("Consulta");
        $htmlFieldset = new HtmlFieldset ($htmlLegend);
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoParaConsultaDeProduto ());

        //Botão de consulta.
        $bt = new HtmlButton();
        $bt->setName ("bt");
        $bt->setValue ("consultar");
        $bt->setType ("submit");
        $bt->setTexto ("Consultar");

        $htmlFieldset->adicionaObjeto ($bt);

        //Cria o formulário
        $htmlForm = new HtmlForm();
        $htmlForm->setAction ("");
        $htmlForm->setMethod ("post");
        $htmlForm->adicionaObjeto ($htmlFieldset);

        $htmlDivConsulta->adicionaObjeto ($htmlForm);

        return $htmlDivConsulta;
    }

    protected function montaDivConteudo () {
        $htmlDivConteudo = new HtmlDiv();
        $htmlDivConteudo->setId ("conteudo");

        //Monta o fieldset com as entradas de dados uma por parágrafo.
        $htmlLegend   = new HtmlLegend ("Produto");
        $htmlFieldset = new HtmlFieldset ($htmlLegend);
        $htmlFieldset->adicionaObjeto ($this->montaIdEmHidden ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoNome ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoDescricao ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoValor ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoQtdeEmEstoque ());

        //Monta os botões do CRUD
        $htmlFieldset->adicionaObjetos ($this->montaBotoesParaCRUD ());

        //Cria o formulário
        $htmlForm = new HtmlForm();
        $htmlForm->setAction ("");
        $htmlForm->setMethod ("post");
        $htmlForm->adicionaObjeto ($htmlFieldset);

        $htmlDivConteudo->adicionaObjeto ($htmlForm);

        return $htmlDivConteudo;
    }

    private function montaParagrafoParaConsultaDeProduto () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("Produto");

        $combo = new HtmlSelect();
        $combo->setName ("prodId");
        $combo->setTitle ("Escolha um produto");

        //Busca todos os produtos para montar no combo.
        $produtoAdo = new ProdutoADO();
        $buscou     = $produtos   = $produtoAdo->buscaProdutosOrdenadosPorNome ();
        if ($buscou) {
            //continua...
        } else {
            if ($buscou === false) {
                $this->adicionaMensagem ("Ocorreu um erro na busca dos produtos. Informe ao responável pelo sistema.");
            }
            $produtos = array ();
        }
        //Monta Array de options
        $option  = new HtmlOption();
        $option->setValue (-1);
        $option->adicionaObjeto ("Escolha um produto...");
        $options = array ($option);
        foreach ($produtos as $produtoModel) {
            $option     = new HtmlOption();
            $option->setValue ($produtoModel->getProdId ());
            $option->adicionaObjeto ($produtoModel->getProdNome ());
            $options [] = $option;
        }
        //Adiciona o array de options ao combo.
        $combo->adicionaOptions ($options);

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos (array ($label, $combo));

        return $htmlP;
    }

    private function montaIdEmHidden () {
        $htmlInput = new HtmlInput();
        $htmlInput->setName ("prodId");
        $htmlInput->setType ("hidden");
        $htmlInput->setValue ($this->produtoModel->getProdId ());

        return $htmlInput;
    }

    private function montaParagrafoNome () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("Nome");

        $input = new HtmlInput();
        $input->setName ("prodNome");
        $input->setType ("text");
        $input->setValue ($this->produtoModel->getProdNome ());
        $input->setPlaceholder ("Nome do produto");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos (array ($label, $input));

        return $htmlP;
    }

    private function montaParagrafoDescricao () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("Descrição");

        $htmlTextarea = new HtmlTextarea();
        $htmlTextarea->setName ("prodDescricao");
        $htmlTextarea->setTexto ($this->produtoModel->getProdDescricao ());
        $htmlTextarea->setPlaceholder ("Descrição do produto");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos (array ($label, $htmlTextarea));

        return $htmlP;
    }

    private function montaParagrafoValor () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("Valor");

        $input = new HtmlInput();
        $input->setName ("prodValor");
        $input->setType ("text");
        $input->setValue ($this->produtoModel->getProdValor ());
        $input->setPlaceholder ("Valor do produto");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos (array ($label, $input));

        return $htmlP;
    }

    private function montaParagrafoQtdeEmEstoque () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("Estoque");

        $input = new HtmlInput();
        $input->setName ("prodQtdeEmEstoque");
        $input->setType ("number");
        $input->setValue ($this->produtoModel->getProdQtdeEmEstoque ());
        $input->setPlaceholder ("Quantidade do produto em estoque");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos (array ($label, $input));

        return $htmlP;
    }

    private function montaBotoesParaCRUD () {
        //Array de botões
        $botoes = array ();

        if (is_null ($this->produtoModel->getProdId ())) {
            //caso prodId esteja nulo quer dizer que a tela está sendo montada 
            //para inclusão somente, logo não se deve montar os botões de 
            //alteração e exclusão.
            //Botão de insersão.
            $bt        = new HtmlButton();
            $bt->setName ("bt");
            $bt->setValue ("inserir");
            $bt->setType ("submit");
            $bt->setTexto ("Inserir");
            $botoes [] = $bt;
        } else {
            //se proId não estiver nulo significa que foi realizada uma 
            //consulta, os dados do produto deem ser preenchidos e os botões que
            //permitem alteração e exclusão devem ser disponibilizados.
            //Botão de alteração.
            $bt        = new HtmlButton();
            $bt->setName ("bt");
            $bt->setValue ("alterar");
            $bt->setType ("submit");
            $bt->setTexto ("Alterar");
            $botoes [] = $bt;
            //Botão de exclusão.
            $bt        = new HtmlButton();
            $bt->setName ("bt");
            $bt->setValue ("excluir");
            $bt->setType ("submit");
            $bt->setTexto ("Excluir");
            $botoes [] = $bt;
        }

        //Botão limpar.
        $bt        = new HtmlButton();
        $bt->setName ("bt");
        $bt->setValue ("limpar");
        $bt->setType ("submit");
        $bt->setTexto ("Limpar");
        $botoes [] = $bt;

        return $botoes;
    }

    public function recebeChaveDaConsulta () {
        return $this->getValorOuNull ("prodId");
    }

    /**
     * Recebe os dados do formulário, aplica as checagens por códigos 
     * maliciosos, e monta na model do objeto ou numa StdClass.
     */
    public function recebeDadosDaInterface () {
        $produtoModel = new ProdutoModel();

        $produtoModel->setProdId ($this->getValorOuNull ("prodId"));
        $produtoModel->setProdNome ($this->getValorOuNull ("prodNome"));
        $produtoModel->setProdDescricao ($this->getValorOuNull ("prodDescricao"));
        $produtoModel->setProdValor ($this->getValorOuNull ("prodValor"));
        $produtoModel->setProdQtdeEmEstoque ($this->getValorOuNull ("prodQtdeEmEstoque"));

        return $produtoModel;
    }

    function getProdutoModel () {
        return $this->produtoModel;
    }

    function setProdutoModel ($produtoModel): void {
        $this->produtoModel = $produtoModel;
    }

}
?>