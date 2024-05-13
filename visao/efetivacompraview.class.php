<?php

require_once "interfaceabstract.class.php";
require_once "../ado/clienteado.class.php";

class EfetivaCompraView extends InterfaceAbstract {

    //É interessante declarar um objeto ProdutoModel para possibilitar montar os 
    //dados na interface após a consulta.
    private $carrinhoDeComprasModel = null;
    private $clienteModel = null;
    private $qtdeProdutosNoCarrinho = null;

    function __construct($titulo = "Confirmação de Compra") {
        parent::__construct($titulo);
        $this->carrinhoDeComprasModel = new CarrinhoDeComprasModel();
        $this->clienteModel = new ClienteModel();
    }

    protected function montaDivConsulta() {
        return null;
    }

    protected function montaDivConteudo() {
        $h1 = new HtmlH1();
        $h1->setTexto("Efetivar compras");

        //Monta Identificação e Produtos.
        //Monta Carrinho de Compras se o Cliente já se identificou
        $htmlDoCarrinho = null;
        $htmlDoCliente = null;

        if (is_null($this->carrinhoDeComprasModel->getCarrClieCPF())) {
            //continua...
        } else {
            $htmlDoCliente = $this->montaDadosDoCliente();
            $htmlDoCarrinho = $this->montaCarrinho();
        }

        //Monta a div do conteúdo.
        $htmlDivConteudo = new HtmlDiv();
        $htmlDivConteudo->setId("conteudo");
        $htmlDivConteudo->adicionaObjetos(array($h1, $htmlDoCliente, $htmlDoCarrinho));

        return $htmlDivConteudo;
    }

    protected function montaDadosDoCliente() {
        $htmlLegend = new HtmlLegend("Dados do Cliente");
        $htmlFieldset = new HtmlFieldset($htmlLegend);
        $htmlFieldset->adicionaObjeto($this->montaParagrafoDoCliente());

        return $htmlFieldset;
    }

    protected function montaCarrinho() {
        //Monta o fieldset com as entradas de dados uma por parágrafo.
        $htmlLegend = new HtmlLegend("Itens da Compra");
        $htmlFieldset = new HtmlFieldset($htmlLegend);
        $htmlFieldset->adicionaObjeto($this->montaTabelaDeProdutos());

        //Monta os botões do CRUD
        $htmlFieldset->adicionaObjeto($this->montaFormularioComOsBotoesParaCRUD());

        return $htmlFieldset;
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
            $htmlTable->addTr($htmlTr);
        }

        return $htmlTable;
    }

    private function montaParagrafoDoCliente() {
        $htmlDivCliente = new HtmlDiv();
        $htmlDivCliente->setId("cliente");

        $htmlLabel = new HtmlLabel();

        $htmlLabel->adicionaObjeto($this->montaParagrafoCPF());
        $htmlLabel->adicionaObjeto($this->montaParagrafoNome());
        $htmlLabel->adicionaObjeto($this->montaParagrafoEndereco());
        $htmlLabel->adicionaObjeto($this->montaParagrafoComplementoDoEndereco());
        $htmlLabel->adicionaObjeto($this->montaParagrafoUF());
        $htmlLabel->adicionaObjeto($this->montaParagrafoCidade());
        $htmlLabel->adicionaObjeto($this->montaParagrafoCEP());
        $htmlLabel->adicionaObjeto($this->montaParagrafoFone());
        $htmlLabel->adicionaObjeto($this->montaParagrafoEmail());

        //Cria o formulário
        $htmlForm = new HtmlForm();
        $htmlForm->setAction("");
        $htmlForm->setMethod("post");
        $htmlForm->adicionaObjeto($htmlLabel);

        $htmlDivCliente->adicionaObjeto($htmlForm);

        return $htmlDivCliente;
    }

    /* private function montaParagrafoDoCliente () {
      $label = new HtmlLabel();
      $label->adicionaObjeto ("Cliente");

      //Busca todos os produtos para montar no combo.
      $clienteAdo = new ClienteADO();
      $buscou     = $clientes   = $clienteAdo->buscaClientesOrdenadosPorNome ();
      if ($buscou) {
      //continua...
      } else {
      if ($buscou === false) {
      $this->adicionaMensagem ("Ocorreu um erro na busca dos clintes. Informe ao responável pelo sistema.");
      }
      $clientes = array ();
      }
      $htmlP = new HtmlP();
      $htmlP->adicionaObjeto ($label);

      return $htmlP;
      } */

    private function montaFormularioComOsBotoesParaCRUD() {
        //Array de com todos os inputs (hidden e button).
        $inputs = array();

        //Mota dados do produto do carrinho do cliente.
        $htmlInputCarrClieCPF = new HtmlInput();
        $htmlInputCarrClieCPF->setName("carrClieCPF");
        $htmlInputCarrClieCPF->setType("hidden");
        $htmlInputCarrClieCPF->setValue($this->carrinhoDeComprasModel->getCarrClieCPF());

        $inputs [] = $htmlInputCarrClieCPF;

        $htmlInputCarrProdId = new HtmlInput();
        $htmlInputCarrProdId->setName("carrProdId");
        $htmlInputCarrProdId->setType("hidden");
        $htmlInputCarrProdId->setValue($this->carrinhoDeComprasModel->getCarrProdId());

        $inputs [] = $htmlInputCarrProdId;

        //Botão comprar.
        $bt = new HtmlButton();
        $bt->setName("bt");
        $bt->setValue("Confirmar");
        $bt->setType("submit");
        $bt->setTexto("Confirmar");

        $inputs [] = $bt;

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos($inputs);

        $htmlForm = new HtmlForm();
        $htmlForm->setAction("");
        $htmlForm->setMethod("post");
        $htmlForm->adicionaObjeto($htmlP);

        return $htmlForm;
    }

    public function recebeChaveDaConsulta() {
        return new CarrinhoDeComprasModel($this->carrinhoDeComprasModel->getCarrClieCPF(), $this->getValorOuNull("carrProdId"));
    }

    /**
     * Recebe os dados do formulário, aplica as checagens por códigos 
     * maliciosos, e monta na model do objeto ou numa StdClass.
     */
    /*public function recebeDadosDaInterface() {
      $carrinhoDeComprasModel = new CarrinhoDeComprasModel();

      $carrinhoDeComprasModel->setCarrClieCPF($this->getValorOuNull("carrClieCPF"));
      $carrinhoDeComprasModel->setCarrProdId($this->getValorOuNull("carrProdId"));
      $carrinhoDeComprasModel->setCarrQtdeProduto($this->getValorOuNull("carQtdeProduto"));

      return $clienteModel;
      } */

    public function recebeDadosDaInterface() {
        $carrinhoDeComprasModel = new CarrinhoDeComprasModel();

        $carrinhoDeComprasModel->setCarrClieCPF($this->getValorOuNull("carrClieCPF"));
        $carrinhoDeComprasModel->setCarrProdId($this->getValorOuNull("carrProdId"));
        $carrinhoDeComprasModel->setCarrQtdeProduto($this->getValorOuNull("carQtdeProduto"));

        $clienteModel = new ClienteModel();

        $clienteModel->setClieCPF($this->getValorOuNull("clieCPF"));
        $clienteModel->setClieNome($this->getValorOuNull("clieNome"));
        $clienteModel->setClieEndereco($this->getValorOuNull("clieEndereco"));
        $clienteModel->setClieComplementoDoEndereco($this->getValorOuNull("clieComplementoDoEndereco"));
        $clienteModel->setClieUF($this->getValorOuNull("clieUF"));
        $clienteModel->setClieCidade($this->getValorOuNull("clieCidade"));
        $clienteModel->setClieCEP($this->getValorOuNull("clieCEP"));
        $clienteModel->setClieFone($this->getValorOuNull("clieFone"));
        $clienteModel->setClieEmail($this->getValorOuNull("clieEmail"));

        return array($carrinhoDeComprasModel, $clienteModel);
    }

    private function montaParagrafoCPF() {
        $label = new HtmlLabel();
        $label->adicionaObjeto("CPF");

        $input = new HtmlInput();
        $input->setName("clieCPF");
        $input->setType("number");
        $input->setValue($this->clienteModel->getClieCPF());
        $input->setPlaceholder("CPF (somente os números)");
        $input->setDisabled("true");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos(array($label, $input));

        return $htmlP;
    }

    private function montaParagrafoNome() {
        $label = new HtmlLabel();
        $label->adicionaObjeto("Nome");

        $input = new HtmlInput();
        $input->setName("clieNome");
        $input->setType("text");
        $input->setValue($this->clienteModel->getClieNome());
        $input->setPlaceholder("Nome completo");
        $input->setDisabled("true");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos(array($label, $input));

        return $htmlP;
    }

    private function montaParagrafoEndereco() {
        $label = new HtmlLabel();
        $label->adicionaObjeto("Endereço");

        $input = new HtmlInput();
        $input->setName("clieEndereco");
        $input->setType("text");
        $input->setValue($this->clienteModel->getClieEndereco());
        $input->setPlaceholder("Endereço completo");
        $input->setDisabled("true");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos(array($label, $input));

        return $htmlP;
    }

    private function montaParagrafoComplementoDoEndereco() {
        $label = new HtmlLabel();
        $label->adicionaObjeto("Complemento");

        $input = new HtmlInput();
        $input->setName("clieComplementoDoEndereco");
        $input->setType("text");
        $input->setValue($this->clienteModel->getClieComplementoDoEndereco());
        $input->setPlaceholder("Complemento do endereço");
        $input->setDisabled("true");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos(array($label, $input));

        return $htmlP;
    }

    private function montaParagrafoUF() {

        $label = new HtmlLabel();
        $label->adicionaObjeto("UF");

        $UF = array("AC" => "Acre", "AL" => "Alagoas", "AP" => "Amapá", "AM" => "Amazonas",
            "BA" => "Bahia", "CE" => "Ceará", "DF" => "Distrito Federal",
            "ES" => "Espírito Santo", "GO" => "Goiás", "MA" => "Maranhão", "MT" => "Mato Grosso", "MS" => "Mato Grosso do Sul",
            "MG" => "Minas Gerais", "PA" => "Pará", "PB" => "Paraíba", "PR" => "Paraná", "PE" => "Pernambuco", "PI" => "Piauí",
            "RJ" => "Rio de Janeiro", "RN" => "Rio Grande do Norte", "RS" => "Rio Grande do Sul", "RO" => "Rondônia",
            "RR" => "Roraima", "SC" => "Santa Catarina", "SP" => "São Paulo", "SE" => "Sergipe", "TO" => "Tocantins");

        $select = new HtmlSelect();
        $select->setName("clieUF");

        foreach ($UF as $unidade => $nomeUF) {
            $option = new HtmlOption();
            $option->setValue($unidade);
            $option->adicionaObjeto($nomeUF);
            $select->adicionaOption($option);
            $select->setDisabled("true");
        }

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos(array($label, $select));

        return $htmlP;
    }

    private function montaParagrafoCidade() {
        $label = new HtmlLabel();
        $label->adicionaObjeto("Cidade");

        $input = new HtmlInput();
        $input->setName("clieCidade");
        $input->setType("text");
        $input->setValue($this->clienteModel->getClieCidade());
        $input->setPlaceholder("Cidade");
        $input->setDisabled("true");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos(array($label, $input));

        return $htmlP;
    }

    private function montaParagrafoCEP() {
        $label = new HtmlLabel();
        $label->adicionaObjeto("CEP");

        $input = new HtmlInput();
        $input->setName("clieCEP");
        $input->setType("text");
        $input->setValue($this->clienteModel->getClieCEP());
        $input->setPlaceholder("CEP");
        $input->setDisabled("true");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos(array($label, $input));

        return $htmlP;
    }

    private function montaParagrafoFone() {
        $label = new HtmlLabel();
        $label->adicionaObjeto("Telefone");

        $input = new HtmlInput();
        $input->setName("clieFone");
        $input->setType("text");
        $input->setValue($this->clienteModel->getClieFone());
        $input->setPlaceholder("Telefone com DDD");
        $input->setDisabled("true");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos(array($label, $input));

        return $htmlP;
    }

    private function montaParagrafoDataDeNascimento() {
        $label = new HtmlLabel();
        $label->adicionaObjeto("Data de nascimento");

        $input = new HtmlInput();
        $input->setName("clieDataDeNascimento");
        $input->setType("date");
        $input->setValue($this->clienteModel->getClieDataDeNascimento());
        $input->setPlaceholder("Data de nascimento");
        $input->setDisabled("true");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos(array($label, $input));

        return $htmlP;
    }

    private function montaParagrafoEmail() {
        $label = new HtmlLabel();
        $label->adicionaObjeto("E-mail");

        $input = new HtmlInput();
        $input->setName("clieEmail");
        $input->setType("email");

        $input->setValue($this->clienteModel->getClieEmail());
        $input->setPlaceholder("E-mail");
        $input->setDisabled("true");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos(array($label, $input));

        return $htmlP;
    }

    function setCarrinhoDeComprasModel(CarrinhoDeComprasModel $carrinhoDeComprasModel): void {
        $this->carrinhoDeComprasModel = $carrinhoDeComprasModel;
    }

    function setClieNome($clieNome) {
        $this->clieNome = $clieNome;
    }

    function getClieNome() {
        return $this->clieNome;
    }

    function getClienteModel() {
        return $this->clienteModel;
    }

    function setClienteModel($clienteModel): void {
        $this->clienteModel = $clienteModel;
    }

}

?>