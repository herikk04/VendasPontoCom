<?php

/**
 * Implementa os métodos de persistência para a tabela Produtos.
 *
 */
require_once 'adoabstract.class.php';
require_once '../modelo/modelabstract.class.php';
require_once '../modelo/produtomodel.class.php';

class ProdutoADO extends ADOAbstract {

    private $produtoModel = null;

    function __construct($produtoModel = NULL) {
        parent::__construct("Produtos");

        if (is_null($produtoModel)) {
            $this->produtoModel = new ProdutoModel();
        } else {
            $this->produtoModel = $produtoModel;
        }
    }

    public function insereObjeto() {
        $colunasValores = array(
            "prodId" => $this->produtoModel->getProdId(),
            "prodNome" => $this->produtoModel->getProdNome(),
            "prodDescricao" => $this->produtoModel->getProdDescricao(),
            "prodValor" => $this->produtoModel->getProdValor(),
            "prodQtdeEmEstoque" => $this->produtoModel->getProdQtdeEmEstoque()
        );

        $insert = $this->montaStringDoInsert($colunasValores);

        return $this->executaQuery($insert, $colunasValores);
    }

    public function alteraObjeto() {
        //monta o array dos dados para alteração.
        $colunasParaAlteracao = array(
            "prodNome" => $this->produtoModel->getProdNome(),
            "prodDescricao" => $this->produtoModel->getProdDescricao(),
            "prodValor" => $this->produtoModel->getProdValor(),
            "prodQtdeEmEstoque" => $this->produtoModel->getProdQtdeEmEstoque()
        );
        //monta a chave para a alteração.
        $colunasChave = array(
            "prodId" => $this->produtoModel->getProdId()
        );
        $instrucao = $this->montaStringDoUpdate($colunasParaAlteracao, $colunasChave);

        return $this->executaQuery($instrucao, array_merge($colunasParaAlteracao, $colunasChave));
    }

    public function montaInstrucaoDeAlteracaoEArrayDeColunasEValores($colunasParaAlteracao) {
        $colunasChave = array("prodId" => $this->produtoModel->getProdId());
        $instrucao = $this->montaStringDoUpdate($colunasParaAlteracao, $colunasChave);
        $dadosParaAlteracao = array_merge($colunasParaAlteracao, $colunasChave);
        return array($instrucao, $dadosParaAlteracao);
    }

    public function excluiObjeto() {
        //monta a chave para a alteração.
        $colunasChave = array(
            "prodId" => $this->produtoModel->getProdId()
        );
        $instrucao = $this->montaStringDoDeleteParametrizada($colunasChave);

        return $this->executaQuery($instrucao, $colunasChave);
    }

    /**
     * Monta o objeto ProdutoModel a partir do dados lidos.
     * Este método sobrescreve o método da AdoAbstract para completar a 
     * funcionalidade.
     * 
     * @param type $produtoModel Objeto lido no padão FETCH_OBJ
     * @return \ProdutoModel Objeto model
     */
    public function montaObjeto($produtoModel) {
        return new ProdutoModel($produtoModel->prodId, $produtoModel->prodNome, $produtoModel->prodDescricao, $produtoModel->prodValor, $produtoModel->prodQtdeEmEstoque);
    }

    public function buscaProdutosOrdenadosPorNome() {
        return $this->buscaArrayObjeto(null, 1, "ORDER BY prodNome");
    }

    public function buscaProduto($prodId) {
        return $this->buscaObjeto(array($prodId), "prodId = ?");
    }

    public function montaInstrucaodeBuscaDoProduto($prodId) {
        $instrucao = " "
                . "select * from " . $this->getNomeDaTabela() . ""
                . " where prodId = ? ";
        return array($instrucao, array($prodId));
    }
    
    function getProdutoModel() {
        return $this->produtoModel;
    }
    
    function montaInstrucaoSelect($prodId) {
        $instrucao = "select * from {$this->getNomeDaTabela()} where prodId = ?";
        
        return array($instrucao, array($prodId));
    }
    
    function setProdutoModel($produtoModel): void {
        $this->produtoModel = $produtoModel;
    }

}
