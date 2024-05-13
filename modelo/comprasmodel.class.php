<?php

require_once 'modelabstract.class.php';

class ComprasModel extends ModelAbstract {

    private $compId = null;
    private $compClieCPF = null;
    private $compDt = null;

    function __construct($compId = null, $compClieCPF = null,
            $compDt = null) {
        parent::__construct();
        $this->CompId = $compId;
        $this->compClieCPF = $compClieCPF;
        $this->compDt = $compDt;
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
    
    public function getCompId() {
        return $this->compId;
    }

    public function getCompClieCPF() {
        return $this->compClieCPF;
    }

    public function getCompDt() {
        return $this->compDt;
    }

    public function setCompId($compId): void {
        $this->compId = $compId;
    }

    public function setCompClieCPF($compClieCPF): void {
        $this->compClieCPF = $compClieCPF;
    }

    public function setCompDt($compDt): void {
        $this->compDt = $compDt;
    }
}
?>