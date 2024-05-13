<?php

class HtmlEventosGlobais {
    //O array $eventos guardado os Objetos de Eventos. Cada evento deve ser 
    //instanciado pela sua classe que herda desta os métodos de geração do código.
    //O HTML divide em 8 tipos os eventos:
    // - Eventos de Janela
    // - Eventos de Formulários
    // - Eventos de Teclado
    // - Eventos de Mouse
    // - Eventos de Arrastar (Drag)
    // - Eventos de Prancheta (Clipboard)
    // - Eventos de Mídia
    // - Eventos de Miscelânea
    private $eventos = null;

    public function __construct() {
        $this->eventos = array();
    }

    public function geraCodigoDosEventos() {
        $string = null;
        //Este laço percorre todas as posições do array de eventos.
        foreach ($this->eventos as $evento) {
            $string .= $evento->geraCodigoDoEvento();
        }
        return $string;
    }

    public function adicionaEvento($evento) {
        $this->eventos[] = $evento;
    }

    public function adicionaEventos(Array $eventos) {
        foreach ($eventos as $eventos) {
            $this->adicionaEvento($eventos);
        }
    }

}