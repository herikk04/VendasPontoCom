<?php

abstract class ModelAbstract {
    private $mensagens = null;
    
    public function __construct() {
        $this->mensagens = array();
    }

    protected function adicionaMensagem($mensagem) {
        $this->mensagens [] = $mensagem;
    }

    public function getMensagens() {
        return $this->mensagens;
    }
    
    abstract public function checaAtributos();
}
?>