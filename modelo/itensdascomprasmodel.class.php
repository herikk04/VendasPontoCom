<?php

require_once 'modelabstract.class.php';

class ItensDasComprasModel extends ModelAbstract {

    private $itemCompId = null;
    private $itemProdId = null;
    private $itemQtdeProduto = null;

    function __construct($itemCompId = null, $itemProdId = null,
            $itemQtdeProduto = null) {
        parent::__construct();
        $this->itemCompId = $itemCompId;
        $this->itemProdId = $itemProdId;
        $this->itemQtdeProduto = $itemQtdeProduto;
    }

    /**
     * @todo ESTE METODO DEVE SER IMPLEMENTADO DE ACORDO COM AS CHECAGENS 
     * ABAIXO.
     * 
     * @return boolean
     */
    public function checaAtributos() {
        $atributosOk = true;

        //carrClieCPF - Origatório e numérico.
        //carrProdId - Origatório e numérico.
        //carrQtdeProduto - Origatório, numérico e maior do que zero.
        //carrData - Origatório e formato de data válida (aaaa-mm-dd).

        return $atributosOk;
    }
    
    public function getItemCompId() {
        return $this->itemCompId;
    }

    public function getItemProdId() {
        return $this->itemProdId;
    }

    public function getItemQtdeProduto() {
        return $this->itemQtdeProduto;
    }

    public function setItemCompId($itemCompId): void {
        $this->itemCompId = $itemCompId;
    }

    public function setItemProdId($itemProdId): void {
        $this->itemProdId = $itemProdId;
    }

    public function setItemQtdeProduto($itemQtdeProduto): void {
        $this->itemQtdeProduto = $itemQtdeProduto;
    }
}

?>