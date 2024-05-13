<?php
/**
 * Implementa os métodos de persistência para a tabela de Clientes.
 *
 */
require_once 'adoabstract.class.php';
require_once '../modelo/modelabstract.class.php';
require_once '../modelo/clientemodel.class.php';

class ClienteADO extends ADOAbstract {
    private $clienteModel = null;

    function __construct ($clienteModel = NULL) {
        parent::__construct ("Clientes");

        if (is_null ($clienteModel)) {
            $this->clienteModel = new ClienteModel();
        } else {
            $this->clienteModel = $clienteModel;
        }
    }

    /*
     * Esta é a versão 2 da insersão e utiliza de métodos da classe mãe.
     */

    public function insereObjeto () {
        $colunasValores = array (
            "clieCPF"                   => $this->clienteModel->getClieCPF (),
            "clieNome"                  => $this->clienteModel->getClieNome (),
            "clieEndereco"              => $this->clienteModel->getClieEndereco (),
            "clieComplementoDoEndereco" => $this->clienteModel->getClieComplementoDoEndereco (),
            "clieUF"                    => $this->clienteModel->getClieUF (),
            "clieCidade"                => $this->clienteModel->getClieCidade (),
            "clieCEP"                   => $this->clienteModel->getClieCEP (),
            "clieFone"                  => $this->clienteModel->getClieFone (),
            "clieDataDeNascimento"      => $this->clienteModel->getClieDataDeNascimento (),
            "clieEmail"                 => $this->clienteModel->getClieEmail ()
        );

        $insert = $this->montaStringDoInsert ($colunasValores);

        return $this->executaQuery ($insert, $colunasValores);
    }

    /*
     * Esta é a versão 2 da alteração e utiliza de métodos da classe mãe.
     */

    public function alteraObjeto () {
        //Monta o array dos dados para alteração.
        //Como o CPF é a chave ele não deve ser alterado, por isso ele não é 
        //montado neste array.
        $colunasParaAlteracao = array (
            "clieNome"                  => $this->clienteModel->getClieNome (),
            "clieEndereco"              => $this->clienteModel->getClieEndereco (),
            "clieComplementoDoEndereco" => $this->clienteModel->getClieComplementoDoEndereco (),
            "clieUF"                    => $this->clienteModel->getClieUF (),
            "clieCidade"                => $this->clienteModel->getClieCidade (),
            "clieCEP"                   => $this->clienteModel->getClieCEP (),
            "clieFone"                  => $this->clienteModel->getClieFone (),
            "clieDataDeNascimento"      => $this->clienteModel->getClieDataDeNascimento (),
            "clieEmail"                 => $this->clienteModel->getClieEmail ()
        );
        //monta a chave de busca para a alteração.
        $colunasChave         = array (
            "clieCPF" => $this->clienteModel->getClieCPF ()
        );
        $instrucao            = $this->montaStringDoUpdate ($colunasParaAlteracao, $colunasChave);
        //O merge abvaixo junta os dois array num só. Cuidado com o merge, se 
        //se nos array existirem colunas com o mesmo nome ele não repete e 
        //ignora as excedentes.
        return $this->executaQuery ($instrucao, array_merge($colunasParaAlteracao, $colunasChave));
    }

    /*
     * Esta é a versão 2 da exclusão e utiliza de métodos da classe mãe.
     */

    public function excluiObjeto () {
        //monta a chave para a alteração.
        $colunasChave = array (
            "clieCPF" => $this->clienteModel->getClieCPF ()
        );
        $instrucao    = $this->montaStringDoDeleteParametrizada ($colunasChave);

        return $this->executaQuery ($instrucao, $colunasChave);
    }

    /**
     * Monta o objeto ClienteModel a partir do dados lidos.
     * Este método sobrescreve o método da AdoAbstract para completar a 
     * funcionalidade.
     * 
     * @param type $clienteModel->cliente Objeto lido no padão FETCH_OBJ
     * @return \ClienteModel Objeto model
     */
    public function montaObjeto ($clienteModel) {
        return new ClienteModel ($clienteModel->clieCPF, $clienteModel->clieNome, $clienteModel->clieEndereco, $clienteModel->clieComplementoDoEndereco, $clienteModel->clieUF, $clienteModel->clieCidade, $clienteModel->clieCEP, $clienteModel->clieFone, $clienteModel->clieDataDeNascimento, $clienteModel->clieEmail);
    }

    public function buscaClientesOrdenadosPorNome () {
        return $this->buscaArrayObjeto (null, 1, "ORDER BY clieNome");
    }

    public function buscaCliente ($clieCPF) {
        return $this->buscaObjeto (array ($clieCPF), "clieCPF = ?");
    }
    
    public function consultaClieNome ($clieNome) {
        return $this->buscaObjeto (array ($clieNome), "clieNome = ?");
    }

    function getClienteModel () {
        return $this->clienteModel;
    }

    function setClienteModel (ClienteModel $clienteModel): void {
        $clienteModel->clienteModel = $clienteModel->clienteModel;
    }
}
?>