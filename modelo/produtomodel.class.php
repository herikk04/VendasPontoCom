<?php
require_once 'modelabstract.class.php';

class ProdutoModel extends ModelAbstract {
    private $prodId            = null;
    private $prodNome          = null;
    private $prodDescricao     = null;
    private $prodValor         = null;
    private $prodQtdeEmEstoque = null;

    function __construct ($prodId = null, $prodNome = null,
                          $prodDescricao = null, $prodValor = null,
                          $prodQtdeEmEstoque = null) {
        parent::__construct ();

        $this->prodId            = $prodId;
        $this->prodNome          = $prodNome;
        $this->prodDescricao     = $prodDescricao;
        $this->prodValor         = $prodValor;
        $this->prodQtdeEmEstoque = $prodQtdeEmEstoque;
    }

public function checaAtributos() {
        $atributosOk = true;

        if (is_null($this->prodNome) || trim($this->prodNome) == '') {
            $atributosOk = false;
            $this->adicionaMensagem("O nome deve ser informado!");
        } else {
            if (strlen($this->prodNome) > 45) {
                $atributosOk = false;
                $this->adicionaMensagem("O nome não pode ser maior que 45 caracteres.");
            }
        }

        if (is_null($this->prodDescricao) || trim($this->prodDescricao) == '') {
            $atributosOk = false;
            $this->adicionaMensagem("A descrição deve ser informada!");
        } else {
            if (strlen($this->prodDescricao) > 500) {
                $atributosOk = false;
                $this->adicionaMensagem("A descrição não pode ser maior que 500 caracteres.");
            }
        }

        if (is_null($this->prodValor) || trim($this->prodValor) == '') {
            $atributosOk = false;
            $this->adicionaMensagem("O valor deve ser informado!");
            
        } elseif($this->prodValor < 0){
                $atributosOk = false;
                $this->adicionaMensagem("O valor deve ser positivo.");
        } else {
            if (is_numeric($this->prodValor)) {
                //número ok, continua...
            } else {
                // erro no número.
                $atributosOk = false;
                $this->adicionaMensagem("O valor deve ser numérico.");
            }
        } 

        if (is_null($this->prodQtdeEmEstoque) || trim($this->prodQtdeEmEstoque) == '') {
            $atributosOk = false;
            $this->adicionaMensagem("O estoque deve ser informado!");
        } else {
            $estoqueOk = preg_match('/^[0-9]{1,}$/', $this->prodQtdeEmEstoque);
            if ($estoqueOk) {
                //continua...
            } else {
                $atributosOk = false;
                $this->adicionaMensagem("O estoque deve ser inteiro.");
            }
        }
        
        return $atributosOk;
    }

    function getProdId () {
        return $this->prodId;
    }

    function getProdNome () {
        return $this->prodNome;
    }

    function getProdDescricao () {
        return $this->prodDescricao;
    }

    function getProdValor () {
        return $this->prodValor;
    }

    function getProdQtdeEmEstoque () {
        return $this->prodQtdeEmEstoque;
    }

    function setProdId ($prodId): void {
        $this->prodId = $prodId;
    }

    function setProdNome ($prodNome): void {
        $this->prodNome = $prodNome;
    }

    function setProdDescricao ($prodDescricao): void {
        $this->prodDescricao = $prodDescricao;
    }

    function setProdValor ($prodValor): void {
        $this->prodValor = $prodValor;
    }

    function setProdQtdeEmEstoque ($prodQtdeEmEstoque): void {
        $this->prodQtdeEmEstoque = $prodQtdeEmEstoque;
    }

}
?>