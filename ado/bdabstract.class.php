<?php
/**
 * Este é um Código Iniciado na Fábrica de Software Do IFG - Câmpus Inhumas, e  foi modificado durante a materia de Programação para Web do 3º Ano do tecnico de Informática para Web Integrado ao Ensino Médio.
 * 
 * Professor: Elymar Pereira Cabral
 * ALunos: Herik Kauan de Assis e Thiago Ferreira dos Santos
 * 
 * 
 * Descrição de BancoDeDadosPdo:
 * Esta classe cuida da camada de persistência do banco de dados e será  
 * extendida diretamente pela classe AdoAbstract. 
 * 
 * Todos os métodos a serem execudados diretamente pela classe PDO devem ser 
 * implementados nesta.
 * 
 */
require_once 'conexao.class.php';
require_once 'transacao.class.php';

abstract class BDAbstract {
    private $conexao               = null;
    private $pdoStatment           = null;
    private $conexaoParaTransacoes = null;

    /**
     * Este é o método construtor da classe BancoDeDadosPdo. Nele é feita a conexão com o 
     * banco de dados usando os dados da classe AtributosBd que deve ser recebida via parâmetro.
     * @param type $atributosBd Classe com os dados para conexão e seleção do banco de dados.
     * @return type
     */
    function __construct () {

        try {
            $this->conexao = Conexao::open ();
        } catch (Exception $e) {
            $this->geraLogDeErro ("Conexão com o Banco de dados.", "Mensagem: " . $e->getMessage ());
            die ("Não foi possível conectar ao SGBD. Contate o analista responsável.");
        }
    }

    /**
     * Este é o método que vai destruir o construtor, vai encerrar a conexão.
     */
    function __destruct () {
        $this->conexao               = $this->conexaoParaTransacoes = NULL;
    }

    /**
     * Este método retornará erros do SGBD.
     * 
     * @param inteiro $tipo Identifica se o erro foi de uma execução num objeto 
     *                      do tipo statment (0) ou diretamente no BD (1).
     * @return String Mensagem do erro
     */
    function getBdError ($tipo = 0) {
        $erro = null;
        if ($tipo === 0) {
            $erro = $this->pdoStatment->errorInfo ();
        } else {
            $erro = $this->conexao->errorInfo ();
        }

        return $erro[2];
    }

    /**
     * Método para execução da query via PDO Prepared Statement
     * passando os valores por parametros em array, separados da query
     * @param String $query Instrução SQL parametrizada com ?.
     * @param array $arrayDeValores Valores a serem substituídos nos ? da instrução.
     * @return boolean true ou false dependendo do resultado de execute()
     */
    function executaQuery ($query, $arrayDeValores) {
        try {
            $preparou = $this->conexao->prepare ($query);
            if ($preparou) {
                $this->pdoStatment = $preparou;
            } else {
                $this->geraLogDeErro ($query, "PREPARE : " . $this->conexao->errorInfo ());
                return false;
            }
        } catch (Exception $e) {
            $this->geraLogDeErro ($query, $e->getMessage ());
            return false;
        }

        try {
            $executou = $this->pdoStatment->execute (array_values ($arrayDeValores));
            if ($executou) {
                $this->geraLogDeExecucao ($query, 'executaPs');
                return true;
            } else {
                $this->geraLogDeErro ($query, $this->getBdError ());
                return false;
            }
        } catch (Exception $e) {
            $this->geraLogDeErro ($query, "EXECUTE : " . $e->getMessage ());
            return false;
        }
    }

    /**
     * Este método será retornado o número de linhas afetadas em uma consulta sql.
     * OBS: Segundo o php.net o comportamento do rowCount de retornar o número de
     *      linhas, não será garantido para todos bancos de dados.
     * @param type $resultado
     * @return rowCount
     */
    function qtdeLinhas () {
        return $this->pdoStatment->rowCount ();
    }

    /**
     * Este método irá retorna a quantidade de linhas afetadas por Updates, Deletes...
     * @return rowCount
     */
    function linhasAfetadas () {
        return $this->pdoStatment->rowCount ();
    }

    /**
     * Este método lê o resultado de um select. Retorna uma tupla no formato de
     * array indexado pelo nome da coluna ou um objeto stdClas, de acoro com o 
     * parâmetro de entrada (2 ou 5 respectivamente).
     * @param type $estilo 2 == FETCH_ASSOC, 5 == FETCH_OBJ;
     * @return type
     */
    function leTabelaBD ($estilo = 5) {
        return $this->pdoStatment->fetch ($estilo);
    }

    /**
     * Este método é responsável por gerar arquivo de log quando houver erro 
     * de SQL ao executar uma query no banco de dados
     * @date 07/07/2016
     * @author Charles Batista <charlesbatista@hotmail.com>
     */
    function geraLogDeErro ($query, $mensagemDeErro) {
        $conteudo_file = '===================================================================================' . PHP_EOL;
        $conteudo_file .= 'Hora: ' . date ("H:i:s") . ' | Script: ' . $_SERVER['SCRIPT_NAME'] . PHP_EOL;
        $conteudo_file .= "Query executada: " . $query . ": " . $mensagemDeErro . PHP_EOL . PHP_EOL;

        $diretoriosDeLogs = $_SERVER['DOCUMENT_ROOT'] . "/Logs";

        // Se o diretório não existir, cria-o
        if (!is_dir ($diretoriosDeLogs)) {
            mkdir ($diretoriosDeLogs);
        }

        $fopen = fopen ($diretoriosDeLogs . "/erros_" . date ("Ymd") . ".log",
                                                              "a");
        fwrite ($fopen, $conteudo_file);
        fclose ($fopen);
    }

    /**
     * Este método é responsável por gerar arquivo de log quando houver insert, delete ou update 
     * de SQL ao executar uma query no banco de dados
     * @date 11/03/2017
     * @author Enan Iansen Lara <enanilara@outlook.com>
     */
    function geraLogDeExecucao ($query) {
        $conteudo_file = '===================================================================================' . PHP_EOL;
        $conteudo_file .= 'Hora: ' . date ("H:i:s") . ' | Script: ' . $_SERVER['SCRIPT_NAME'] . PHP_EOL;
        $conteudo_file .= "Query executada: " . $query . PHP_EOL;

        $diretoriosDeLogs = $_SERVER['DOCUMENT_ROOT'] . "/Logs";

        // Se o diretório não existir, cria-o
        if (!is_dir ($diretoriosDeLogs)) {
            mkdir ($diretoriosDeLogs);
        }

        $fopen = fopen (
                $diretoriosDeLogs . "/execucao_" . date ("Ymd") . ".log", "a"
        );
        fwrite ($fopen, $conteudo_file);
        fclose ($fopen);
    }

    /**
     * Recupera o último id inserido numa tabela. Cuidado! Não utilize este 
     * mátodo em transações. Utilize o método 
     * recuperaIdEmTransacoesMultiobjetos($tabela = null) no lugar.
     * 
     * @return boolean/int retorna o id da última tupla inserida ou false se 
     * ocorrer erro.
     */
    function recuperaId ($tabela = null) {
        if (is_null ($tabela)) {
            return $this->conexao->lastInsertId ();
        } else {
            return $this->conexao->lastInsertId ($tabela);
        }
    }

    /**
     * Este métedo é para iniciar a transação com o banco de dados. Entra no 
     * lugar do iniciaTransacaoComApenasUmObjeto().
     * Ele usa a classe TTransaction que permite abrir transações envolvendo 
     * mais de um objeto.
     */
    public function iniciaTransacao () {
        try {
            if (Transacao::open ()) {
                $this->conexaoParaTransacoes = Transacao::getConexao ();
            } else {
                return false;
            }

            return true;
        } catch (Exception $e) {
            $this->setMensagem ("Erro ao tentar iniciar a transa&ccedil;&atilde;o. Contate o analista respons&aacute;vel.");
            $this->setMensagem ($e->getMessage ());
            return false;
        }
    }

    /**
     * Aplica todas as operações realizadas na transação e fecha a conexão com o
     * BD.
     * @return boolean
     */
    public function validaTransacao () {
        return Transacao::commit ();
    }

    /**
     * Descarta todas as operações realizadas na transação.
     * @return boolean
     */
    public function descartaTransacao () {
        return Transacao::rollback ();
    }

    function executaPsComTransacao ($query, array $arrayDeValores) {
        try {
            $preparou = $this->conexaoParaTransacoes->prepare ($query);
            if ($preparou) {
                $this->pdoStatment = $preparou;
            } else {
                $this->setMensagem ($this->conexao->errorInfo ());
                $this->geraLogDeErro ($query, "PREPARE : " . $this->conexao->errorInfo ());
                return false;
            }
        } catch (Exception $e) {
            $this->setMensagem ($e->getMessage ());
            $this->geraLogDeErro ($query, $e->getMessage ());
            return false;
        }
        try {
            $executou = $this->pdoStatment->execute (array_values ($arrayDeValores));
            if ($executou) {
                $this->geraLogDeExecucao ($query,
                                          'executaPsEmTransaoesMultiobjetos');
                return true;
            } else {
                $this->setMensagem ($this->getBdError ());
                $this->geraLogDeErro ($query, $this->getBdError ());
                return false;
            }
        } catch (Exception $e) {
            $this->setMensagem ($e->getMessage ());
            $this->geraLogDeErro ($query, "EXECUTE : " . $e->getMessage ());
            return false;
        }
    }

    function recuperaIdEmTransacoesMultiobjetos ($tabela = null) {
        if (is_null ($tabela)) {
            return $this->conexaoParaTransacoes->lastInsertId ();
        } else {
            return $this->conexaoParaTransacoes->lastInsertId ($tabela);
        }
    }

    /**
     * Similar ao método executaArrayDeQuerysComTransacao porém executa o método
     * executaPs desta classe que precisa receber as querys parametrizadas (?)
     * e os parâmetros. Além disso trabalha com multiplus objetos e, portanto,
     * trabalha com os métodos para transações multilobjetos.
     * 
     * @param type $arrayPsEParametros Vetor onde cada posição deve conter um 
     * array com uma query na primeira posição e um array com os parâmetros na 
     * segunda posição.
     * Exemplo: Array(0 => Array (String $query, Array $arrayDeParametros),
     *                1 => Array (String $query, Array $arrayDeParametros))
     * @return boolean True se executou todos as querys e False se ocorreu algum 
     *                 erro ou não executou alguma tupla.
     */
    function executaInstrucoesNumaTransacao (Array $arrayPsEParametros) {
        $this->iniciaTransacao ();

        foreach ($arrayPsEParametros as $psEParametros) {
            $executouQuery = $this->executaPsComTransacao ($psEParametros[0],
                                                           $psEParametros[1]);
            if ($executouQuery) {
                //continua
            } else {
                $this->descartaTransacao ();
                return false;
            }
        }

        $this->validaTransacao ();

        return true;
    }

    function getConexao () {
        return $this->conexao;
    }

    function setConexao ($conexao): void {
        $this->conexao = $conexao;
    }

    function getPdoStatment () {
        return $this->pdoStatment;
    }

    function setPdoStatment ($pdoStatment) {
        $this->pdoStatment = $pdoStatment;
    }

    function getConexaoParaTransacoesMultiobjetos () {
        return $this->conexaoParaTransacoes;
    }

    function setConexaoParaTransacoesMultiobjetos ($conexaoParaTransacoesMultiobjetos) {
        return $this->conexaoParaTransacoes = $conexaoParaTransacoesMultiobjetos;
    }

}
?>
