<?php
require_once 'controllerabstract.class.php';
require_once '../visao/produtoview.class.php';
require_once '../modelo/produtomodel.class.php';
require_once '../ado/produtoado.class.php';

class ProdutoController extends ControllerAbstract {
    private $acao        = null;
    private $produtoView = null;

    public function __construct () {
        $this->produtoView = new ProdutoView();

        $this->acao = $this->produtoView->getAcao ();

        switch ($this->acao) {
            case "nova" :
                //Se for uma nova tela não precisa fazer nada!
                break;

            case "inserir":
                $this->insereProduto ();

                break;

            case "consultar":
                $this->consultaProduto ();

                break;

            case "alterar":
                $this->alteraProduto();

                break;

            case "excluir":
                $this->excluiProduto ();

                break;

            case "limpar":
                $produtoModel = new ProdutoModel();

                $this->produtoView->setProdutoModel ($produtoModel);

                break;
        }

        $this->produtoView->geraInterface ();
    }

    private function insereProduto () {
        $produtoModel = $this->produtoView->recebeDadosDaInterface ();
        //Independentemente do que for recebido da view, na inserção o id do
        //produto deve ser sempre null.
        $produtoModel->setProdId (null);

        $dadosOk = $produtoModel->checaAtributos ();
        if ($dadosOk) {
            //Se checagem ok, continua para a inserção.
        } else {
            //se retornar com erro repassa as mensagens para a interface.
            $this->produtoView->adicionaMensagens ($produtoModel->getMensagens ());
            $this->produtoView->setProdutoModel ($produtoModel);
            return;
        }

        $produtoADO = new ProdutoADO ($produtoModel);
        $incluiu    = $produtoADO->insereObjeto ();
        if ($incluiu) {
            $produtoModel = new ProdutoModel();
            $this->produtoView->adicionaMensagem ("Produto inserido com sucesso!");
        } else {
            $this->produtoView->adicionaMensagem ("Ocorreu um problema na inserção do produto, informe ao responsável pelo sistema!");
        }

        $this->produtoView->setProdutoModel ($produtoModel);
    }

    private function consultaProduto () {
        $prodId = $this->produtoView->recebeChaveDaConsulta ();

        $produtoADO   = new ProdutoADO();
        $buscou       = $produtoModel = $produtoADO->buscaProduto ($prodId);
        if ($buscou) {
            $this->produtoView->setProdutoModel ($produtoModel);
        } else {
            //se retornar com erro repassa as mensagens para a interface.
            $this->produtoView->adicionaMensagens ("Não foi possível encontrar o produto! Tente novamente ou informe o problema ao responsável pelo sistema.");
        }
    }

    private function alteraProduto () {
        $produtoModel = $this->produtoView->recebeDadosDaInterface ();

        $dadosOk = $produtoModel->checaAtributos ();
        if ($dadosOk) {
            //Se checagem ok, continua para a alteração.
        } else {
            //se retornar com erro repassa as mensagens para a interface.
            $this->produtoView->adicionaMensagens ($produtoModel->getMensagens ());
            $this->produtoView->setProdutoModel ($produtoModel);

            //se ocorrer erro na checagem deve interromper para não incluir.
            return;
        }

        $produtoADO = new ProdutoADO ($produtoModel);
        $alterou    = $produtoADO->alteraObjeto ();
        if ($alterou) {
            $produtoModel = new ProdutoModel();
            $this->produtoView->adicionaMensagem ("Produto alterado com sucesso!");
        } else {
            $this->produtoView->adicionaMensagem ("Ocorreu um problema na alteração do produto, informe ao responsável pelo sistema!");
        }

        $this->produtoView->setProdutoModel ($produtoModel);
    }

    private function excluiProduto () {
        $produtoModel = $this->produtoView->recebeDadosDaInterface ();

        $produtoADO = new ProdutoADO ($produtoModel);
        $excluiu    = $produtoADO->excluiObjeto ();
        if ($excluiu) {
            $produtoModel = new ProdutoModel();
            $this->produtoView->adicionaMensagem ("Produto excluído com sucesso!");
        } else {
            $this->produtoView->adicionaMensagem ("Ocorreu um problema na exclusão do produto, informe ao responsável pelo sistema!");
        }

        $this->produtoView->setProdutoModel ($produtoModel);
    }

}
?>