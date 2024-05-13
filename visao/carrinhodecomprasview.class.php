<?php
require_once "interfaceabstract.class.php";
require_once "../ado/clienteado.class.php";

class CarrinhoDeComprasView extends InterfaceAbstract {
    //É interessante declarar um objeto ProdutoModel para possibilitar montar os 
    //dados na interface após a consulta.
    private $carrinhoDeComprasModel = null;
    private $qtdeProdutosNoCarrinho = null;
    
    function __construct ($titulo = "Carrinho de Compras") {
        parent::__construct ($titulo);
        $this->carrinhoDeComprasModel = new CarrinhoDeComprasModel();;
    }
    
    protected function montaDivConsulta () {
        return null;
    }

    protected function montaDivConteudo () {
        $h1 = new HtmlH1();
        $h1->setTexto("Carrinho de Compras de {$this->clieNome}.");
        
        //Monta Identificação e Produtos.
        $htmlIdentificacaoEProduto = $this->montaIdentificacaoEProduto ();

        //Monta Carrinho de Compras se o Cliente já se identificou
        $htmlDoCarrinho = null;
        if (is_null ($this->carrinhoDeComprasModel->getCarrClieCPF ())) {
            //continua...
        } else {
            $htmlDoCarrinho = $this->montaCarrinho ();
        }

        //Monta a div do conteúdo.
        $htmlDivConteudo = new HtmlDiv();
        $htmlDivConteudo->setId ("conteudo");
        $htmlDivConteudo->adicionaObjetos (array ($h1, $htmlIdentificacaoEProduto, $htmlDoCarrinho));

        return $htmlDivConteudo;
    }
    
    protected function montaIdentificacaoEProduto () {
        //Monta o fieldset com as entradas de dados uma por parágrafo.
        $htmlLegend   = new HtmlLegend ("Identificação e Produtos");
        $htmlFieldset = new HtmlFieldset ($htmlLegend);

        //Monta input do CPF.
        //$htmlFieldset->adicionaObjeto ($this->montaParagrafoParaIdentificacao ());
        //Monta Combo Box dos produtos.
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoParaConsultaDeProduto ());

        //Botão de consulta.
        $bt    = new HtmlButton();
        $bt->setName ("bt");
        $bt->setValue ("acrescentar");
        $bt->setType ("submit");
        $bt->setTexto ("Acrescentar");
        $htmlP = new HtmlP();
        $htmlP->adicionaObjeto ($bt);
        $htmlFieldset->adicionaObjeto ($htmlP);

        //Cria o formulário
        $htmlForm = new HtmlForm();
        $htmlForm->setAction ("");
        $htmlForm->setMethod ("post");
        $htmlForm->adicionaObjeto ($htmlFieldset);

        return $htmlForm;
    }

    protected function montaCarrinho () {
        //Monta o fieldset com as entradas de dados uma por parágrafo.
        $htmlLegend   = new HtmlLegend ("Carrinho de Compras");
        $htmlFieldset = new HtmlFieldset ($htmlLegend);
        $htmlFieldset->adicionaObjeto ($this->montaTabelaDeProdutos ());

        //Monta os botões do CRUD
        $htmlFieldset->adicionaObjeto ($this->montaFormularioComOsBotoesParaCRUD ());

        return $htmlFieldset;
    }

    private function montaParagrafoParaConsultaDeProduto () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("Produto");

        $combo = new HtmlSelect();
        $combo->setName ("carrProdId");
        $combo->setTitle ("Escolha um produto");

        //Busca todos os produtos para montar no combo.
        $produtoAdo = new ProdutoADO();
        /**
         * @todo TEM QUE IMPLEMENTAR OUTRO MÉTODO QUE SELECIONE APENAS OS 
         * PRODUTOS COM ESTOQUE MAIOR DO QUE ZERO...
         */
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

    /*
     * Este método monta uma tabela com os produtos já selecionados para o 
     * carrinho de compras.
     * 
     * @todo Este método ainda está montando os produtos em linhas. Deve-se 
     * implementar as classes HtmlTable, HtmlThead, HtmlTbody, HtmlTfoot, 
     * HtmlTh e HtmlTd para usar aqui e montar o carrinho na tabela.
     * 
     * @return \HtmlP|\HtmlDiv
     */
     

    private function montaTabelaDeProdutos() {
        //$htmlDiv = new HtmlDiv();
        //Monta o cabeçalho da tabela.
        /**
         * @ DEVE-SE MONTAR ESTA LINHA NO CABAÇALHO DA TABELA.
         */
        $htmlTable = new HtmlTable();
        $htmlTr = new HtmlTr();

        $htmlTh = new HtmlTh();
        $htmlTh->setTexto("Produto");

        $htmlTr->addTh($htmlTh);

        $htmlTh = new HtmlTh();
        $htmlTh->setTexto("Valor");

        $htmlTr->addTh($htmlTh);

        $htmlTh = new HtmlTh();
        $htmlTh->setTexto("Qtde.");

        $htmlTr->addTh($htmlTh);

        $htmlTh = new HtmlTh();
        $htmlTh->setTexto("Total");

        $htmlTr->addTh($htmlTh);

        $htmlTd = new HtmlTd();
        $htmlTd->setTexto("");
        
        $htmlTr->addTd($htmlTd);
        $htmlTable->addTr($htmlTr);

        //Monta Corpo da tabela
        $carrinhoDeCompras = new CarrinhoDeComprasADO();
        $buscou = $produtos = $carrinhoDeCompras->buscaOCarrinhoDoCliente($this->carrinhoDeComprasModel->getCarrClieCPF());
        if ($buscou) {
            //continua...
        } else {
            if ($buscou === 0) {
                //Se carrinho vazio apenas informa...
                $htmlP = new HtmlP();
                $htmlP->adicionaObjeto("O seu carrinho está vazio!");
                return $htmlP;
            } else {
                //Se ocorrer erro na busca informar...
                $this->adicionaMensagem("Ocorreu um erro ao buscar o seu carrinho! Informe ao responsável pelo sistema.");
                return null;
            }
        }

        /**
         * @todo NESTE LAÇO CADA PRODUTO É MONTADO EM UMA DIV. COM A TABELA,
         * CADA PRODUTO DEVE ESTAR NUMA LINHA DO CORPO DA TABELA E O BOTÃO DE 
         * RETIRAR DEVE FICAR NA MESMA LINHA E NA ÚLTIMA COLUNA.
         */
        foreach ($produtos as $produto) {
            //    $divProduto = new HtmlDiv();
            //$htmlTable = new HtmlTable();

            $htmlTr = new HtmlTr();

            $htmlTd = new HtmlTd();
            $htmlTd->setTexto($produto->prodNome);
            $htmlTr->addTd($htmlTd);

            $htmlTd = new HtmlTd();
            $htmlTd->setTexto($produto->prodValor);
            $htmlTr->addTd($htmlTd);

            $htmlTd = new HtmlTd();
            $htmlTd->setTexto($produto->carrQtdeProduto);
            $htmlTr->addTd($htmlTd);

            $htmlTd = new HtmlTd();
            $htmlTd->setTexto($produto->prodValor * $produto->carrQtdeProduto);
            $htmlTr->addTd($htmlTd);

            $htmlTd = new HtmlTd();
            $htmlTd->adicionaObjeto(
                    $this->montaFormRetiraProduto(
                            $this->carrinhoDeComprasModel->getCarrClieCPF(),
                            $produto->carrProdId,
                            $produto->carrQtdeProduto
                    )
            );
            $this->qtdeProdutosNoCarrinho++;
            
            $htmlTr->addTd($htmlTd);

            $htmlTable->addTr($htmlTr);
        }

        return $htmlTable;
    }

    private function montaFormRetiraProduto($carrClieCPF, $carrProdId, $carrQtdeProduto) {
        //Mota dados do produto do carrinho do cliente a ser excluído.
        $htmlInputCarrClieCPF = new HtmlInput();
        $htmlInputCarrClieCPF->setName("carrClieCPF");
        $htmlInputCarrClieCPF->setType("hidden");
        $htmlInputCarrClieCPF->setValue($carrClieCPF);
        $htmlInputCarrProdId = new HtmlInput();
        $htmlInputCarrProdId->setName("carrProdId");
        $htmlInputCarrProdId->setType("hidden");
        $htmlInputCarrProdId->setValue($carrProdId);
        $htmlInputCarrQtdeProduto = new HtmlInput();
        $htmlInputCarrQtdeProduto->setName("carrQtdeProduto");
        $htmlInputCarrQtdeProduto->setType("hidden");
        $htmlInputCarrQtdeProduto->setValue($carrQtdeProduto);
        //Monta botão de retirada.
        $htmlButton = new HtmlButton();
        $htmlButton->setName("bt");
        $htmlButton->setType("submit");
        $htmlButton->setValue("retirar");
        $htmlButton->setTexto("Retirar");
        //Cria o formulário
        $htmlForm = new HtmlForm();
        $htmlForm->setAction("");
        $htmlForm->setMethod("post");
        $htmlForm->adicionaObjetos(
                array(
                    $htmlInputCarrClieCPF,
                    $htmlInputCarrProdId,
                    $htmlInputCarrQtdeProduto,
                    $htmlButton
                )
        );

        return $htmlForm;
    }
    
    /*private function montaFormEsvaziaOCarrinho($carrProdId) {
        $produtosASeremLidos = null;
        while($produtosASeremLidos < $this->qtdeProdutosNoCarrinho){
        
        //monta os dados do carrinho do cliente
        $htmlInputCarrProdId = new HtmlInput();
        $htmlInputCarrProdId->setName("carrProdId[]");
        $htmlInputCarrProdId->setType("hidden");
        $htmlInputCarrProdId->setValue($carrProdId);
        
        //Monta botão de esvaziar.
        $htmlButton = new HtmlButton();
        $htmlButton->setName("bt");
        $htmlButton->setType("submit");
        $htmlButton->setValue("esvaziar");
        $htmlButton->setTexto("Esvaziar");
        
        //Cria o formulário
        $htmlForm = new HtmlForm();
        $htmlForm->setAction("");
        $htmlForm->setMethod("post");
        $htmlForm->adicionaObjetos(
                array(
                    $htmlInputCarrProdId,
                    $htmlButton
                )
            );
        }
        
        //Monta botão de esvaziar.
        $htmlButton = new HtmlButton();
        $htmlButton->setName("bt");
        $htmlButton->setType("submit");
        $htmlButton->setValue("esvaziar");
        $htmlButton->setTexto("Esvaziar");
        
        //Cria o formulário
        $htmlForm = new HtmlForm();
        $htmlForm->setAction("");
        $htmlForm->setMethod("post");
        $htmlForm->adicionaObjetos(
                array(
                    $htmlInputCarrProdId,
                    $htmlButton
                )
            );
    }*/

    private function montaFormularioComOsBotoesParaCRUD () {
        //Array de com todos os inputs (hidden e button).
        $inputs = array ();

        //Mota dados do produto do carrinho do cliente.
        $htmlInputCarrClieCPF = new HtmlInput();
        $htmlInputCarrClieCPF->setName ("carrClieCPF");
        $htmlInputCarrClieCPF->setType ("hidden");
        $htmlInputCarrClieCPF->setValue ($this->carrinhoDeComprasModel->getCarrClieCPF ());

        $inputs [] = $htmlInputCarrClieCPF;

        $htmlInputCarrProdId = new HtmlInput();
        $htmlInputCarrProdId->setName ("carrProdId");
        $htmlInputCarrProdId->setType ("hidden");
        $htmlInputCarrProdId->setValue ($this->carrinhoDeComprasModel->getCarrProdId ());

        $inputs [] = $htmlInputCarrProdId;

        //Botão comprar.
        $bt = new HtmlButton();
        $bt->setName ("bt");
        $bt->setValue ("continuar");
        $bt->setType ("submit");
        $bt->setTexto ("Continuar");

        $inputs [] = $bt;

        //Botão limpar.
        $bt = new HtmlButton();
        $bt->setName ("bt");
        $bt->setValue ("limpar");
        $bt->setType ("submit");
        $bt->setTexto ("Limpar");
        
        $inputs [] = $bt;
        
        //$inputs [] = $bt =$this->montaFormEsvaziaOCarrinho($this->carrinhoDeComprasModel->getCarrProdId(), $this->carrinhoDeComprasModel->getCarrQtdeProduto());
                
        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos ($inputs);
        
        
        //Monta formulário para essas ações. Obs.: o formulário não pode 
        //envolver o fieldset completo porque há necessidade de se montar 
        //formulários para a retirada de cada produto separadamente e um 
        //formulário não pode conter outro formulário.
        $htmlForm = new HtmlForm();
        $htmlForm->setAction ("");
        $htmlForm->setMethod ("post");
        $htmlForm->adicionaObjeto ($htmlP);

        return $htmlForm;
    }

    public function recebeChaveDaConsulta () {
        return new CarrinhoDeComprasModel ($this->carrinhoDeComprasModel->getCarrClieCPF(), $this->getValorOuNull ("carrProdId"));
    }

    /**
     * Recebe os dados do formulário, aplica as checagens por códigos 
     * maliciosos, e monta na model do objeto ou numa StdClass.
     */
    public function recebeDadosDaInterface () {
        $carrinhoDeComprasModel = new CarrinhoDeComprasModel();

        $carrinhoDeComprasModel->setCarrClieCPF ($this->getValorOuNull ("carrClieCPF"));
        $carrinhoDeComprasModel->setCarrProdId ($this->getValorOuNull ("carrProdId"));
        $carrinhoDeComprasModel->setCarrQtdeProduto ($this->getValorOuNull ("carQtdeProduto"));

        return $carrinhoDeComprasModel;
    }

    function setCarrinhoDeComprasModel (CarrinhoDeComprasModel $carrinhoDeComprasModel): void {
        $this->carrinhoDeComprasModel = $carrinhoDeComprasModel;
    }
        
    function setClieNome ($clieNome){
        $this->clieNome = $clieNome;
    }
    
    function getClieNome(){
        return $this->clieNome;
    }
}
?>