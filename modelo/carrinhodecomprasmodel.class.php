<?php

require_once 'modelabstract.class.php';

class CarrinhoDeComprasModel extends ModelAbstract {

    private $carrClieCPF = null;
    private $carrProdId = null;
    private $carrQtdeProduto = null;
    private $carrData = null;

    function __construct($carrClieCPF = null, $carrProdId = null,
            $carrQtdeProduto = null, $carrData = null) {
        parent::__construct();
        $this->carrClieCPF = $carrClieCPF;
        $this->carrProdId = $carrProdId;
        $this->carrQtdeProduto = $carrQtdeProduto;
        $this->carrData = $carrData;
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

    function getCarrClieCPF() {
        return $this->carrClieCPF;
    }

    function getCarrProdId() {
        return $this->carrProdId;
    }

    function getCarrQtdeProduto() {
        return $this->carrQtdeProduto;
    }

    function getCarrData() {
        return $this->carrData;
    }

    function setCarrClieCPF($carrClieCPF): void {
        $this->carrClieCPF = $carrClieCPF;
    }

    function setCarrProdId($carrProdId): void {
        $this->carrProdId = $carrProdId;
    }

    function setCarrQtdeProduto($carrQtdeProduto): void {
        $this->carrQtdeProduto = $carrQtdeProduto;
    }

    function setCarrData($carrData): void {
        $this->carrData = $carrData;
    }

}

?>