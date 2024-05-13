<?php

class ControllerAbstract {
    protected $mensagem = null;

    function getMensagem () {
        return $this->mensagem;
    }

    function setMensagem ($mensagem): void {
        $this->mensagem = $mensagem;
    }

}