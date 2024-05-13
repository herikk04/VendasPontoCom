<?php
/**
 * Este é um Código Iniciado na Fábrica de Software Do IFG - Câmpus Inhumas, e  foi modificado durante a materia de Programação para Web do 3º Ano do tecnico de Informática para Web Integrado ao Ensino Médio.
 * 
 * Professor: Elymar Pereira Cabral
 * ALunos: Herik Kauan de Assis e Thiago Ferreira dos Santos
 * 
 * 
 * 
 * Descrição de AtributosBD:
 * Atributos a serem usados para se conectar ao banco de dados.
 *
 */
class AtributosBd {
    private $host    = "localhost";
    private $bdNome  = "VendasPontoCom";
    private $usuario = "vpc";
    private $senha   = "vpc";


    function getHost () {
        return $this->host;
    }

    function getBdNome () {
        return $this->bdNome;
    }

    function getUsuario () {
        return $this->usuario;
    }

    function getSenha () {
        return $this->senha;
    }

    function getTipo () {
        return $this->tipo;
    }

    function setHost ($host): void {
        $this->host = $host;
    }

    function setBdNome ($bdNome): void {
        $this->bdNome = $bdNome;
    }

    function setUsuario ($usuario): void {
        $this->usuario = $usuario;
    }

    function setSenha ($senha): void {
        $this->senha = $senha;
    }

    function setTipo ($tipo): void {
        $this->tipo = $tipo;
    }

}
?>
