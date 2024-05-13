<?php
/**
 * Este é um Código Iniciado na Fábrica de Software Do IFG - Câmpus Inhumas, e  foi modificado durante a materia de Programação para Web do 3º Ano do tecnico de Informática para Web Integrado ao Ensino Médio.
 * 
 * Professor: Elymar Pereira Cabral
 * ALunos: Herik Kauan de Assis e Thiago Ferreira dos Santos
 * 
 * 
 * Descrição de ADO:
 * Esta classe cuida dos métodos para persistência no banco de dados e será 
 * extendida diretamente pelas classes da camada ADO. 
 * Métodos genéricos são implementados nesta e os que são mais específicos de 
 * cada objeto são implementados nas classes ado filhas.
 * 
 * Esta classe extende a classe BDAbstract.
 * 
 */
require_once 'bdabstract.class.php';

abstract class ADOAbstract extends BDAbstract {
    private $nomeDaTabela = null;
    private $mensagem     = null;

    function __construct ($nomeDaTabela) {
        parent::__construct ();
        $this->nomeDaTabela = $nomeDaTabela;
    }

    abstract function insereObjeto ();

    abstract function alteraObjeto ();

    abstract function excluiObjeto ();
    
    /**
     * Cada classe ADO deve implementar o seu método montaObjeto() de acordo com
     * a model correspondente.
     * 
     * @param type $objetoBd Objeto Lido. Pode vir no ormato FETCH_ASSOC ou
     * FETCH_OBJ, dependendo da leitura. O default é FETCH_OBJ.
     * @return type Objeto model correspondente.
     */
    public function montaObjeto($objetoBd) {
        return $objetoBd;
    }

    /**
     * Este métedo lê uma linha de um  determinado objeto.
     * @param type $resultado
     * @return boolean
     */
    public function leObjeto () {
        $objetoBD = $this->leTabelaBD ();
        if ($objetoBD) {
            return $this->montaObjeto ($objetoBD);
        } else {
            return FALSE;
        }
    }

    /**
     * Implementa a consulta a tabelas usando a parametrização dos valores para 
     * maior segurança, por isso exige um array com os valores para substituição 
     * no Prepare. Necessita do nome da tabela no atributo de classe 
     * $nomeDaTabela (use o método setNomeDaTabela).
     * 
     * @param type $arrayDeValoresParaPs Array com os valores a serem 
     *             substituídos pelo Prepare (PS). Têm que estar na ordem 
     *             identificada pelo ? na clásula where.
     * @param type $where String com a expressão lógica para ser montada após a
     *             cláusula where do select com ? no lugar dos valores. Não obrigatória.
     * @param type $orderBy Instrução order by completa. Não obrigatória.
     * @return int|boolean Retorna true para execução ok, false para 
     *         erro/exceção e 0 para consulta vazia.
     * @throws Exception Lança essa exceção quando o nome da tabela não 
     *         está identificado no atributo da classe.
     */
    public function consultaObjeto ($arrayDeValoresParaPs, $where = 1,
                                    $orderBy = NULL) {
        if (is_null ($this->nomeDaTabela)) {
            throw new Exception ("Voc&ecirc; deve identificar o nome da tabela para usar esta classe. Utilize o setNomeDaTabela.");
        }

        $query = "select * from {$this->nomeDaTabela} where {$where} {$orderBy} ";

        $executou = $this->executaQuery ($query, $arrayDeValoresParaPs);
        if ($executou) {
            if (parent::qtdeLinhas () === 0) {
                return 0;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Implementa a consulta a tabelas como o método consultaObjetoPs() usando a 
     * parametrização dos valores para maior segurança, por isso exige um array 
     * com os valores para substituição no Prepare. Necessita do nome da tabela 
     * no atributo de classe $nomeDaTabela (use o método setNomeDaTabela).
     * 
     * Pode lançar uma exceção  porque chama o método consultaComPs() que lança
     * essa exceção quando o nome da tabela não está identificado no atributo da 
     * classe.
     * 
     * Retorna o a tupla recuperada em forma de objeto do tipo Model.
     * 
     * @param type $arrayDeValoresParaPs Array com os valores a serem 
     *             substituídos pelo Prepare (PS). Têm que estar na ordem 
     *             identificada pelo ? na clásula where.
     * @param type $where String com a expressão lógica para ser montada após a
     *             cláusula where do select com ? no lugar dos valores. Não 
     *             obrigatória.
     * @param type $orderBy Instrução order by completa. Não obrigatória.
     * @return int|boolean|Objeto Retorna true para execução ok, false para 
     *         erro/exceção, 0 para consulta vazia ou objeto do tipo model da 
     *         tupla encontrada.
     */
    public function buscaObjeto ($arrayDeValoresParaPs, $where, $orderBy = NULL) {
        try {
            $consultou = $this->consultaObjeto ($arrayDeValoresParaPs,
                                                $where, $orderBy);
        } catch (Exception $e) {
            throw $e;
        }

        if ($consultou) {
            return $this->leObjeto ();
        } else {
            return $consultou;
        }
    }

    /**
     * Implementa a consulta a tabelas como o método consultaObjetoPs() usando a 
     * parametrização dos valores para maior segurança.
     * 
     * Pode lançar a exceção Exception porque chama o método 
     * consultaComPs() que lança essa exceção quando o nome da tabela não 
     * está identificado no atributo da classe.
     * 
     * Retorna um array com os cada tupla recuperada em forma de objeto do tipo
     * Model.
     * 
     * @param type $arrayDeValoresParaPs Array com os valores a serem 
     *             substituídos pelo Prepare (PS). Têm que estar na ordem 
     *             identificada pelo ? na clásula where.
     * @param type $where String com a expressão lógica para ser montada após a
     *             cláusula where do select com ? no lugar dos valores. Não 
     *             obrigatória.
     * @param type $orderBy Instrução order by completa. Não obrigatória.
     * @return int|boolean|Objeto Retorna true para execução ok, false para 
     *         erro/exceção, 0 para consulta vazia ou array com cada tupola 
     *         recuperada em forma de objetos do tipo Model.
     * @throws Exception Lança essa exceção quando a cláusula where foi
     *         definida e o array de valores está nulo.
     */
    function buscaArrayObjeto ($arrayDeValoresParaPs = null, $where = '1',
                               $orderBy = NULL) {
        //Quando se monta o where deve-se montar obrigatoriamente o array de valores também.
        if (is_null ($arrayDeValoresParaPs)) {
            //qando o $arrayDeValoresParaPs vier nulo, apenas troca p/ um array 
            //vazio para parametrizar a consultaComPs() corretamente que exige um array.
            $arrayDeValoresParaPs = array ();
            if ($where == '1') {
                //se o array veio nulo e o where tá == 1, ok.
            } else {
                //tá errado se o array veio nulo e o where não tá == 1.
                throw new Exception ("Para sele&ccedil;&otilde;es de linhas com cl&aacute;usula where informe o array com os valores para substitui&ccedil;&atilde;o.");
            }
        }
        $arrayObjeto = array (); //variável array a ser alimentada;

        $resultado = $this->consultaObjeto ($arrayDeValoresParaPs, $where,
                                            $orderBy);

        if ($resultado) {
            //continua
        } else if ($resultado === 0) {
            $this->setMensagem ("Nada foi encontrado com a chave de consulta.");
            return 0;
        } else {
            return FALSE;
        }

        while (($objeto = $this->leObjeto ()) !== FALSE) {
            /*
             * É necessário clonar o objeto, pois o objeto é uma referência a este mesmo.
             * Ao se clonar, cria-se uma cópia. Sem clonar o array conteria em cada uma
             * das suas posições a mesma referência, o que geraria múltiplas ocorrência
             * de um único objeto. No entanto o que se pretende com este método é que 
             * se tenha em cada ocorrência um objeto que represente cada uma das tuplas
             * selecinadas na tabela representada.
             */

            $arrayObjeto [] = clone ($objeto);
        }
        return $arrayObjeto;
    }

    /**
     * Método para montar Insert para a execução com Prepared Statement
     * onde os valores serão referenciados por ?
     * @param String $tabela
     * @param array $colunasValores
     * @return String $query
     */
    function montaStringDoInsert (array $colunasValores) {
        if (is_null ($this->nomeDaTabela)) {
            throw new Exception ("Voc&ecirc; deve identificar o nome da tabela para usar esta classe. Utilize o setNomeDaTabela.");
        }

        $primeiraColuna = true;
        $colunas        = " (";
        $valores        = " values (";
        $param          = "?";

        foreach ($colunasValores as $nomeDaColuna => $valorDaColuna) {
            if ($primeiraColuna) {
                $primeiraColuna = false;
            } else {
                $colunas .= ", ";
                $valores .= ", ";
            }

            $colunas .= "`{$nomeDaColuna}`";
            $valores .= "({$param})";
        }
        $colunas .= ") ";
        $valores .= ") ";

        $query = "insert into {$this->getNomeDaTabela ()} " . $colunas . $valores;

        return $query;
    }

    /**
     * Monta a string da query Update com o nome da tabela, as colunas e os 
     * parametros em ? para serem substituidos dentro do executePS
     * 
     * @param type $tabela Nome da Tabela
     * @param array $colunasParaAlteracao 
     *                     Array no formato ("nome_da_coluna" => "valor_da_coluna").
     *                     Se alguma coluna for nula use NULL sem aspas
     *                     para o seu valor.
     * @param type $where  Critério para fazer a atualização.
     * @return string      Query de update.
     */
    function montaStringDoUpdate (array $colunasParaAlteracao, $colunasChave) {
        if (is_null ($this->nomeDaTabela)) {
            throw new Exception ("Voc&ecirc; deve identificar o nome da tabela para usar esta classe. Utilize o setNomeDaTabela.");
        }

        //monta clunas a serem alteradas.
        $colunasEValores   = NULL;
        $contadorIteracoes = 0;
        $numeroDeColunas   = count ($colunasParaAlteracao);
        $param             = '?';

        foreach ($colunasParaAlteracao as $nomeDaColuna => $valorDaColuna) {
            $colunasEValores .= "`{$nomeDaColuna}` = ({$param})";

            $contadorIteracoes++;

            if ($contadorIteracoes < $numeroDeColunas) {
                $colunasEValores .= ", ";
            }
        }

        //monta cláusula where
        $where             = NULL;
        $contadorIteracoes = 0;
        $numeroDeColunas   = count ($colunasChave);
        $param             = '?';

        foreach ($colunasChave as $nomeDaColuna => $valorDaColuna) {
            $where .= "`{$nomeDaColuna}` = {$param}";

            $contadorIteracoes++;

            if ($contadorIteracoes < $numeroDeColunas) {
                $where .= " AND ";
            }
        }

        $query = "update {$this->getNomeDaTabela ()} set " . $colunasEValores . " where $where";

        return $query;
    }

    /**
     * Monta a string da query Delete com o nome da tabela e com a cláusula de
     * condição para fazer o Delete 
     * 
     * @param string $tabela    Nome da tabela
     * @param string $where     Cláusula para excluir.
     * @return type
     */
    function montaStringDoDelete ($where) {
        if (is_null ($this->nomeDaTabela)) {
            throw new Exception ("Voc&ecirc; deve identificar o nome da tabela para usar esta classe. Utilize o setNomeDaTabela.");
        }

        return $query = "delete from {$this->getNomeDaTabela ()} where {$where}";
    }

    function montaStringDoDeleteParametrizada (array $colunasChave) {
        if (is_null ($this->nomeDaTabela)) {
            throw new Exception ("Voc&ecirc; deve identificar o nome da tabela para usar esta classe. Utilize o setNomeDaTabela.");
        }

        $where             = NULL;
        $contadorIteracoes = 0;
        $numeroDeColunas   = count ($colunasChave);
        $param             = '?';

        foreach ($colunasChave as $nomeDaColuna => $valorDaColuna) {
            $where .= "`{$nomeDaColuna}` = ";
            $where .= " {$param}";

            $contadorIteracoes++;

            if ($contadorIteracoes < $numeroDeColunas) {
                $where .= " AND ";
            }
        }

        $query = "DELETE FROM {$this->getNomeDaTabela ()} WHERE $where";

        return $query;
    }

    function setMensagem ($mensagem) {
        $this->mensagem = stripslashes ($mensagem);
    }

    function getMensagem () {
        return $this->mensagem;
    }

    function getNomeDaTabela () {
        return $this->nomeDaTabela;
    }

    function setNomeDaTabela ($nomeDaTabela) {
        $this->nomeDaTabela = $nomeDaTabela;
    }

}