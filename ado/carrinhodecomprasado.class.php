<?php
/**
 * Implementa os métodos de persistência para a tabela Produtos.
 *
 */
require_once 'adoabstract.class.php';
require_once '../modelo/modelabstract.class.php';
require_once '../modelo/carrinhodecomprasmodel.class.php';

class CarrinhoDeComprasADO extends ADOAbstract {
    private $carrinhoDeComprasModel = null;

    function __construct ($carrinhoDeComprasModel = NULL) {
        parent::__construct ("CarrinhoDeCompras");

        if (is_null ($carrinhoDeComprasModel)) {
            $this->carrinhoDeComprasModel = new CarrinhoDeComprasModel();
        } else {
            $this->carrinhoDeComprasModel = $carrinhoDeComprasModel;
        }
    }

    public function montaArrayDeColunasEValores () {
        return $colunasValores = array (
            "carrClieCPF"     => $this->carrinhoDeComprasModel->getCarrClieCPF (),
            "carrProdId"      => $this->carrinhoDeComprasModel->getCarrProdId (),
            "carrQtdeProduto" => $this->carrinhoDeComprasModel->getCarrQtdeProduto (),
            "carrData"        => $this->carrinhoDeComprasModel->getCarrData ()
        );
    }

    public function montaInstrucaoDeInsersaoEArrayDeColunasEValores () {
        $colunasValores = $this->montaArrayDeColunasEValores ();
        return Array ($this->montaStringDoInsert ($colunasValores), $colunasValores);
    }

    public function insereObjeto () {
        $colunasValores = $this->montaArrayDeColunasEValores ();
        return $this->executaQuery ($this->montaStringDoInsert ($colunasValores), $colunasValores);
    }

    public function montaChaveParaAlteracao () {
        return array (
            "carrClieCPF" => $this->carrinhoDeComprasModel->getCarrClieCPF (),
            "carrProdId"  => $this->carrinhoDeComprasModel->getCarrProdId ()
        );
    }

    public function montaInstrucaoDeAlteracaoEArrayDeColunasEValores ($colunasParaAlteracao) {
        $colunasChave       = array (
            "carrClieCPF" => $this->carrinhoDeComprasModel->getCarrClieCPF (),
            "carrProdId"  => $this->carrinhoDeComprasModel->getCarrProdId ()
        );
        $instrucao          = $this->montaStringDoUpdate ($colunasParaAlteracao, $colunasChave);
        $dadosParaAlteracao = array_merge ($colunasParaAlteracao, $colunasChave);
        return array ($instrucao, $dadosParaAlteracao);
    }

    public function alteraObjeto () {
        //monta o array dos dados para alteração.
        $colunasParaAlteracao = array (
            "carrQtdeProduto" => $this->carrinhoDeComprasModel->getCarrQtdeProduto (),
            "carrData"        => $this->carrinhoDeComprasModel->getCarrData ()
        );
        //monta a chave para a alteração.
        $colunasChave         = $this->montaChaveParaAlteracao ();

        $instrucao = $this->montaStringDoUpdate ($colunasParaAlteracao, $colunasChave);

        return $this->executaQuery ($instrucao, array_merge ($colunasParaAlteracao, $colunasChave));
    }

    
    
    public function montaInstrucaoDeExclusaoEArrayDeColunasEValores () {
        $colunasChave       = array (
            "carrClieCPF" => $this->carrinhoDeComprasModel->getCarrClieCPF (),
            "carrProdId"  => $this->carrinhoDeComprasModel->getCarrProdId ()
        );
        $instrucao          = $this->montaStringDoDeleteParametrizada ($colunasChave);
        return array ($instrucao, $colunasChave);
    }

    public function excluiObjeto () {
        //monta a chave para a alteração.
        $colunasChave = array (
            "carrClieCPF" => $this->carrinhoDeComprasModel->getCarClieCPF (),
            "carrProdId"  => $this->carrinhoDeComprasModel->getCarrProdId ()
        );

        $instrucao = $this->montaStringDoDeleteParametrizada ($colunasChave);

        return $this->executaQuery ($instrucao, $colunasChave);
    }

    /**
     * Monta o objeto ProdutoModel a partir do dados lidos.
     * Este método sobrescreve o método da AdoAbstract para completar a 
     * funcionalidade.
     * 
     * @param type $carrinhoDeComprasModel Objeto lido no padão FETCH_OBJ
     * @return \ProdutoModel Objeto model
     */
    public function montaObjeto ($scCarrinhoDeCompras) {
        return new CarrinhoDeComprasModel ($scCarrinhoDeCompras->carrClieCPF, $scCarrinhoDeCompras->carrProdId, $scCarrinhoDeCompras->carrQtdeProduto, $scCarrinhoDeCompras->carrData);
    }
    
    public function montaBuscaOCarrinhoDoCliente($carrClieCPF) {
        $instrucao = ""
                . " SELECT * "
                . "   FROM {$this->getNomeDaTabela ()} "
                . "  WHERE carrClieCPF = ? ";
        return array($instrucao, array($carrClieCPF));
    }
    
    public function montaInstrucaoDeleteCarrinho($carrClieCPF) {
        $instrucao = ""
                . " DELETE "
                . "   FROM {$this->getNomeDaTabela ()} "
                . "  WHERE carrClieCPF = ? ";
        return array($instrucao, array($carrClieCPF));
    }
    
    public function montaInstrucaodeBuscaDoProduto($prodId) {
        $instrucao = " "
                . "select * from " . $this->getNomeDaTabela() . ""
                . " where prodId = ? ";
        return array($instrucao, array($prodId));
    }
    
    public function buscaOCarrinhoDoCliente ($carrClieCPF) {
        $instrucao = ""
                . " SELECT * "
                . "   FROM {$this->getNomeDaTabela ()} "
                . "  INNER JOIN Produtos ON prodId = carrProdId "
                . "  WHERE carrClieCPF = ? ";

            $executou = $this->executaQuery ($instrucao, array ($carrClieCPF));
        if ($executou) {
            if (parent::qtdeLinhas () === 0) {
                return 0;
            }
        } else {
            return false;
        }

        $produtos = array ();
        while (($produto  = $this->leTabelaBD (5)) !== FALSE) {
            $produtos [] = $produto;
        }

        return $produtos;
    }
    
    function montaSelectProdutoDeUmCarrinho() {
        $instrucao = " "
                . " SELECT *"
                . "   FROM " . $this->getNomeDaTabela() . " "
                . "  WHERE carrClieCPF = ? AND carrProdId = ? ";
        return $instrucao;
    }

    public function buscaUmProdutoDeUmCarrinho ($carrClieCPF, $carrProdId) {
        return $this->buscaObjeto (array ($carrClieCPF, $carrProdId), "carrClieCPF = ? AND carrProdId = ?");
    }

    function getCarrinhoDeComprasModel () {
        return $this->carrinhoDeComprasModel;
    }

    function setCarrinhoDeComprasModel ($carrinhoDeComprasModel): void {
        $this->carrinhoDeComprasModel = $carrinhoDeComprasModel;
    }
    
}