<?php

/**
 * Implementa os métodos de persistência para a tabela Produtos.
 *
 */
require_once 'adoabstract.class.php';
require_once '../modelo/modelabstract.class.php';
require_once '../modelo/comprasmodel.class.php';

class ComprasADO extends ADOAbstract {

    private $comprasModel = null;

    function __construct($comprasModel = NULL) {
        parent::__construct("Compras");
        
        $this->comprasModel = new ComprasModel();
    }

    public function montaArrayDeColunasEValores() {
        return $colunasValores = array(
            "compId" => $this->comprasModel->getCompId(),
            "compClieCPF" => $this->comprasModel->getCompClieCPF(),
            "compDt" => $this->comprasModel->getCompDt()
        );
    }

    public function montaInstrucaoDeInsersaoEArrayDeColunasEValores() {
        $colunasValores = $this->montaArrayDeColunasEValores();
        return Array($this->montaStringDoInsert($colunasValores), $colunasValores);
    }
    
    public function insereObjeto() {
        $colunasValores = $this->montaArrayDeColunasEValores();
        return $this->executaQuery($this->montaStringDoInsert($colunasValores), $colunasValores);
    }

    public function montaChaveParaAlteracao() {
        return array(
            "compId" => $this->comprasModel->getCompId(),
            "compClieCPF" => $this->comprasModel->getCompClieCPF()
        );
    }

    public function montaInstrucaoDeAlteracaoEArrayDeColunasEValores($colunasParaAlteracao) {
        $colunasChave = array(
            "compId" => $this->comprasModel->getCompId(),
            "compClieCPF" => $this->comprasModel->getCompClieCPF()
        );
        $instrucao = $this->montaStringDoUpdate($colunasParaAlteracao, $colunasChave);
        $dadosParaAlteracao = array_merge($colunasParaAlteracao, $colunasChave);
        return array($instrucao, $dadosParaAlteracao);
    }

    public function alteraObjeto() {
        //monta o array dos dados para alteração.
        $colunasParaAlteracao = array(
            "compId" => $this->comprasModel->getCompId(),
            "compDt" => $this->comprasModel->getCompDt()
        );
        //monta a chave para a alteração.
        $colunasChave = $this->montaChaveParaAlteracao();

        $instrucao = $this->montaStringDoUpdate($colunasParaAlteracao, $colunasChave);

        return $this->executaQuery($instrucao, array_merge($colunasParaAlteracao, $colunasChave));
    }

    public function montaInstrucaoDeExclusaoEArrayDeColunasEValores() {
        $colunasChave = array(
            "compId" => $this->comprasModel->getCompId(),
            "compClieCPF" => $this->comprasModel->getCompClieCPF()
        );
        $instrucao = $this->montaStringDoDeleteParametrizada($colunasChave);
        return array($instrucao, $colunasChave);
    }

    public function excluiObjeto() {
        //monta a chave para a alteração.
        $colunasChave = array(
            "compId" => $this->comprasModel->getCompId(),
            "compClieCPF" => $this->comprasModel->getCompClieCPF()
        );

        $instrucao = $this->montaStringDoDeleteParametrizada($colunasChave);

        return $this->executaQuery($instrucao, $colunasChave);
    }

    /**
     * Monta o objeto ProdutoModel a partir do dados lidos.
     * Este método sobrescreve o método da AdoAbstract para completar a 
     * funcionalidade.
     * 
     * @param type $carrinhoDeComprasModel Objeto lido no padão FETCH_OBJ
     * @return \ProdutoModel Objeto model
     */
    public function montaObjeto($scCompras) {
        return new ComprasModel($scCompras->compId, $scCompras->compClieCPF, $scCompras->compDt);
    }

    public function montaBuscaDaCompraDoCliente($compClieCPF) {
        $instrucao = ""
                . " SELECT * "
                . "   FROM {$this->getNomeDaTabela()} "
                . "  WHERE compClieCPF = ? ";
        return array($instrucao, array($compClieCPF));
    }

    public function montaInstrucaoDeleteCompra($compClieCPF) {
        $instrucao = ""
                . " DELETE "
                . "   FROM {$this->getNomeDaTabela()} "
                . "  WHERE compClieCPF = ? ";
        return array($instrucao, array($compClieCPF));
    }

    /* public function buscaOCarrinhoDoCliente ($compClieCPF) {
      $instrucao = ""
      . " SELECT * "
      . "   FROM {$this->getNomeDaTabela ()} "
      . "  INNER JOIN Produtos ON prodId = carrProdId "
      . "  WHERE compClieCPF = ? ";

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
      } */

    function getComprasModel() {
        return $this->comprasModel;
    }

    function setComprasModel($comprasModel): void {
        $this->comprasModel = $comprasModel;
    }

}
