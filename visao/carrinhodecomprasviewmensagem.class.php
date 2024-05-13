<?php
require_once "interfaceabstract.class.php";

class CarrinhoDeComprasViewMensagem extends CarrinhoDeComprasView {
    //É interessante declarar um objeto ProdutoModel para possibilitar montar os 
    //dados na interface após a consulta.
    private $carrinhoDeComprasModel = null;
    
    protected function montaDivConsulta () {
        return null;
    }

    protected function montaDivConteudo () {        
        return null;
    }
}
?>