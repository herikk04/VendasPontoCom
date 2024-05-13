<?php
/**
 * Este é um Código Iniciado na Fábrica de Software Do IFG - Câmpus Inhumas, e  foi modificado durante a materia de Programação para Web do 3º Ano do tecnico de Informática para Web Integrado ao Ensino Médio.
 * 
 * Professor: Elymar Pereira Cabral
 * Alunos: Herik Kauan de Assis e Thiago Ferreira dos Santos
 * 
 * 
 * Gerencia as conexões com o BD por meio do arquivo de configuração.
 * 
 * Esta clase foi baseada no exemplo do livro PHP: Programando com Orientação a 
 * Objetos do Pablo Dall'Oglio (p. 202-203).
 *
 */
require_once 'atributosbd.class.php';

final class Conexao {

    /**
     * Não devem existir instâncias de TConnection, por isso o construtor foi
     * marcado como private p/ previnir que algum desavisado tente instanciá-la.
     */
    private function __construct () {
        //vazio
    }

    public static function open () {
        $atributosBD = new AtributosBd();

        //inicia variáveis locais da conexão
        $options     = array (PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        $confUTF8    = "charset=utf8";

        //recupera valores do BD
        $host    = $atributosBD->getHost ();
        $bdNome  = $atributosBD->getBdNome ();
        $usuario = $atributosBD->getUsuario ();
        $senha   = $atributosBD->getSenha ();

        try {
            $conexao = new PDO ("mysql:host={$host};dbname={$bdNome};{$confUTF8}", $usuario, $senha, $options);
        } catch (PDOException $e) {
            throw $e;
        }

        //determina lançamento de exceções na ocorrência de erros
        $conexao->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //configurações especiais para o tipo de caracteres.
        $conexao->exec ("SET NAMES utf8");
        $conexao->exec ("SET collation_connection='utf8_general_ci'");
        $conexao->exec ("SET character_set_connection=utf8");
        $conexao->exec ("SET character_set_client=utf8");
        $conexao->exec ("SET character_set_results=utf8");

        return $conexao;
    }

}