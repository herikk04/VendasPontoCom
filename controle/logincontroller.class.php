<?php
require_once 'controllerabstract.class.php';
require_once '../visao/loginview.class.php';
require_once '../modelo/clientemodel.class.php';
require_once '../ado/clienteado.class.php';

class LoginController extends ControllerAbstract{
    private $acao        = null;
    private $loginView = null;

    public function __construct () {
        $this->loginView = new LoginView();

        $this->acao = $this->loginView->getAcao ();

        switch ($this->acao) {
            case "login":
                $this->loginCliente ();
                break;
            case "cadastrar":
                header("location:cadastrodecliente.php");
                break;
        }

        $this->loginView->geraInterface ();
    }

    private function loginCliente () {
        $clieCPF = $this->loginView->recebeChaveDaConsulta ();

        $clienteADO   = new ClienteADO();
        $buscou       = $clienteModel = $clienteADO->buscaCliente($clieCPF);
        if ($buscou) {
            session_start();
            $_SESSION['clieCPF'] = $clieCPF;
            header('location:carrinhodecompras.php');
        } else {
            $this->loginView->adicionaMensagem("Não foi encontrado este CPF no banco de dados, tente novamente.");
        }
    }
}
?>