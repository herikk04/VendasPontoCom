<?php
/**
 * Este é um Código Iniciado na Fábrica de Software Do IFG - Câmpus Inhumas, e  foi modificado durante a materia de Programação para Web do 3º Ano do tecnico de Informática para Web Integrado ao Ensino Médio.
 * 
 * Professor: Elymar Pereira Cabral
 * ALunos: Herik Kauan de Assis e Thiago Ferreira dos Santos
 * 
 * 
 * Gerencia as conexões com o BD por meio do arquivo de confiração.
 * 
 * Foi implementada para ser usada nas transações que envolvem diversos objetos 
 * ADO. Quando a transação envolver apanas um objeto recomenda-se usar a 
 * transação já existente na AdoPdo.
 * 
 * Esta clase foi baseada no exemplo do livro PHP: Programando com Orientação a 
 * Objetos do Pablo Dall'Oglio (p. 208-209).
 *
 */
final class Transacao {
    private static $conexao = null;

    /**
     * Não devem existir instâncias de TTransaction, por isso o construtor foi
     * marcado como private p/ previnir que algum desavisado tente instanciá-la.
     */
    private function __construct () {
        //vazio
    }

    /**
     * Abre uma conexão quando ainda não existir.
     */
    public static function open () {
        //abre uma conexão e armazena na propriedade estática $conexao
        if (empty (self::$conexao)) {
            try {
                self::$conexao = Conexao::open ();
            } catch (Exception $e) {
                throw new Exception ($e->getMessage ());
            }
            return self::$conexao->beginTransaction ();
        }
        return true;
    }

    /**
     * Recupera a conexão.
     * @return type Conexão;
     */
    public static function getConexao () {
        return self::$conexao;
    }

    static function setConexao ($conexao): void {
        self::$conexao = $conexao;
    }

    /**
     * Descarta todas as operações realizadas na transação.
     */
    public static function rollback () {
        if (self::$conexao) {
            $resultado     = self::$conexao->rollback ();
            self::$conexao = null;
            return $resultado;
        }
        return true;
    }

    /**
     * Aplica todas as operações realizadas na transação e fecha a conexão com o
     * BD.
     */
    public static function commit () {
        if (self::$conexao) {
            $resultado     = self::$conexao->commit ();
            self::$conexao = null;
            return $resultado;
        }
        return true;
    }

    /**
     * Aliás para commit.
     * Aplica todas as operações realizadas na transação e fecha a conexão com o
     * BD.
     */
    public static function close () {
        self::commit ();
    }

}