<?php

require_once 'controllerabstract.class.php';
require_once '../visao/efetivacompraview.class.php';
require_once '../visao/efetivacompraviewmensagem.class.php';
require_once '../modelo/carrinhodecomprasmodel.class.php';
require_once '../ado/carrinhodecomprasado.class.php';
require_once '../modelo/produtomodel.class.php';
require_once '../ado/produtoado.class.php';
require_once '../modelo/clientemodel.class.php';
require_once '../ado/clienteado.class.php';
require_once '../modelo/itensdascomprasmodel.class.php';
require_once '../ado/itensdascomprasado.class.php';
require_once '../modelo/comprasmodel.class.php';
require_once '../ado/comprasado.class.php';

class EfetivaCompraController extends ControllerAbstract {

    private $acao = null;
    private $efetivaCompraView = null;
    private $clieCPF;

    public function __construct() {
        $this->efetivaCompraView = new EfetivaCompraView();

        session_start();
        //session_destroy();
        //$_SESSION['clieCPF'] = null;

        if (isset($_SESSION['clieCPF'])) {
            $this->verificaAcao();
            $this->consultaCliente();
        } else {
            $this->efetivaCompraView = new EfetivaCompraView();
            $this->efetivaCompraView->adicionaMensagem("Não há usuario logado. Faça login.");
        }

        $this->efetivaCompraView->geraInterface();
    }

    private function verificaAcao() {
        $this->clieCPF = $_SESSION['clieCPF'];
        $this->acao = $this->efetivaCompraView->getAcao();
        $clienteADO = new ClienteADO();
        $buscou = $scCliente = $clienteADO->buscaCliente($this->clieCPF);
        if ($buscou) {
            $carrinhoDeComprasModel = new CarrinhoDeComprasModel($scCliente->getClieCPF());
            $this->efetivaCompraView->setCarrinhoDeComprasModel($carrinhoDeComprasModel);
            $this->efetivaCompraView->setClieNome($scCliente->getClieNome());
        } else {
            //se retornar com erro repassa as mensagens para a interface.
            $this->efetivaCompraView->adicionaMensagem("Não foi possível encontrar o cliente! Tente novamente ou informe o problema ao responsável pelo sistema.");
        }

        switch ($this->acao) {
            case "Confirmar" :
                $this->confirmar();

                break;
        }
    }

    private function consultaCliente() {
        $clieCPF = $_SESSION['clieCPF'];

        $clienteADO = new ClienteADO();
        $buscou = $clienteModel = $clienteADO->buscaCliente($clieCPF);
        if ($buscou) {
            $this->efetivaCompraView->setClienteModel($clienteModel);
        } else {
            //se retornar com erro repassa as mensagens para a interface.
            $this->efetivaCompraView->adicionaMensagens("Não foi possível encontrar o cliente! Tente novamente ou informe o problema ao responsável pelo sistema.");
        }
    }

    private function confirmar() {
        $carrinhoDeComprasADO = new CarrinhoDeComprasADO();
        $itensDasComprasADO = new ItensDasComprasADO();
        $comprasADO = new ComprasADO();
        $produtoADO = new ProdutoADO();
        $carrinhoDeComprasModel = new CarrinhoDeComprasModel();
        $itensDasComprasModel = new ItensDasComprasModel();

        $comprasADO->iniciaTransacao();

        $comprasModel = new ComprasModel();
        $comprasModel->setCompClieCPF($_SESSION['clieCPF']);
        $compDt = Data::getDataEHoraNoFormatoBD();
        $comprasModel->setCompDt($compDt);

        $comprasADO->setComprasModel($comprasModel);

        $instrucao = $comprasADO->montaInstrucaoDeInsersaoEArrayDeColunasEValores();
        $executou = $comprasADO->executaPsComTransacao($instrucao[0], $instrucao[1]);
        if ($executou) {
            //continua
        } else {
            $this->efetivaCompraView->adicionaMensagem("Não foi possivel confirmar sua compra devido a um erro ao gerar a compra.");
            $comprasADO->descartaTransacao();
            return false;
        }

        $itemCompId = $comprasADO->recuperaIdEmTransacoesMultiobjetos();
        $itensDasComprasModel->setItemCompId($itemCompId);

        $instrucao = $carrinhoDeComprasADO->montaBuscaOCarrinhoDoCliente($_SESSION['clieCPF']);

        $executou = $comprasADO->executaPsComTransacao($instrucao[0], $instrucao[1]);
        if ($executou) {
            //continua
        } else {
            $this->efetivaCompraView->adicionaMensagem("Não foi possivel buscar os produtos do carrinho.");
            $comprasADO->descartaTransacao();
            return false;
        }

        while (($produtoDoCarrinho = $comprasADO->leTabelaBD(5)) !== FALSE) {
            $produtosDoCarrinho [] = $produtoDoCarrinho;
        }

        foreach ($produtosDoCarrinho as $produtoDoCarrinho) {
            //$produtoDoCarrinho->carrQtdeProduto;
            $dadosParaInsert = array("itemCompId" => $itensDasComprasModel->getItemCompId(),
                                     "itemProdId" => $produtoDoCarrinho->carrProdId,
                                     "itemQtdeProduto" => $produtoDoCarrinho->carrQtdeProduto
                                    );
            $instrucao = $itensDasComprasADO->montaInsercaoDeDadosInformados($dadosParaInsert);
            $executou = $comprasADO->executaPsComTransacao($instrucao[0], $instrucao[1]);
            if ($executou) {
                //continua
            } else {
                $this->efetivaCompraView->adicionaMensagem("Não foi possivel confirmar sua compra devido a um erro em salva-la.");
                $comprasADO->descartaTransacao();
            }
        }

        $instrucao = $carrinhoDeComprasADO->montaInstrucaoDeleteCarrinho($_SESSION['clieCPF']);
        $executou = $comprasADO->executaPsComTransacao($instrucao[0], $instrucao[1]);
        if ($executou) {
        $this->efetivaCompraView = new EfetivaCompraViewMensagem();
        $this->efetivaCompraView->adicionaMensagem("A compra foi confirmada com sucesso.");
        $comprasADO->validaTransacao();
        return true;
        } else {
            $this->efetivaCompraView->adicionaMensagem("Não foi possivel confirmar sua compra devido ao apagar o antigo carrinho de compras.");
            $comprasADO->descartaTransacao();
            return false;
        }
    }

}

?>