<?php
require_once "interfaceabstract.class.php";

class LoginView extends InterfaceAbstract {
    private $clienteModel = null;

    function __construct ($titulo = "Login") {
        parent::__construct ($titulo);
        $this->clienteModel = new ClienteModel();
    }
/*    protected function montaDivLogin () {
        $htmlDivLogin = new HtmlDiv();
        //Pode-se usar o mesmo id do conteúdo pois a configuração no CSS é igual.
        $htmlDivLogin->setId ("login");

        //Monta o fieldset com as entradas de dados uma por parágrafo.
        $htmlLegend   = new HtmlLegend ("Login");
        $htmlFieldset = new HtmlFieldset ($htmlLegend);
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoParaLoginDeCliente ());

        //Botão de Login.
        $bt = new HtmlButton();
        $bt->setName ("bt");
        $bt->setValue ("login");
        $bt->setType ("submit");
        $bt->setTexto ("Logar");

        $htmlFieldset->adicionaObjeto ($bt);

        //Cria o formulário
        $htmlForm = new HtmlForm();
        $htmlForm->setAction ("");
        $htmlForm->setMethod ("post");
        $htmlForm->adicionaObjeto ($htmlFieldset);

        $htmlDivLogin->adicionaObjeto ($htmlForm);

        return $htmlDivLogin;
    }

    private function montaParagrafoParaLoginDeCliente () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("Cliente");

        $combo = new HtmlSelect();
        $combo->setName ("clieCPF");
        $combo->setTitle ("Escolha o cliente");

        //Busca todos os produtos para montar no combo.
        $clienteAdo = new ClienteADO();
        $buscou     = $clientes   = $clienteAdo->buscaClientesOrdenadosPorNome ();
        if ($buscou) {
            //continua...
        } else {
            if ($buscou === false) {
                $this->adicionaMensagem ("Ocorreu um erro na busca dos clintes. Informe ao responável pelo sistema.");
            }
            $clientes = array ();
        }
        return $htmlP;
    }*/

    protected function montaDivConteudo () {
        $htmlDivConteudo = new HtmlDiv();
        $htmlDivConteudo->setId ("conteudo");

        //Cria o fieldset.
        $htmlLegend   = new HtmlLegend ("Login");
        $htmlFieldset = new HtmlFieldset ($htmlLegend);

        //Monta o formulário com as entradas de dados uma por parágrafo.
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoCPF ());/*
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoNome ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoEndereco ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoComplementoDoEndereco ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoUF ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoCidade ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoCEP ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoFone ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoDataDeNascimento ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoEmail ());*/

        //Monta os botões do CRUD
        $htmlFieldset->adicionaObjetos ($this->montaBotoesParaCRUD ());

        //Cria o formulário
        $htmlForm = new HtmlForm();
        $htmlForm->setAction ("");
        $htmlForm->setMethod ("post");
        $htmlForm->adicionaObjeto ($htmlFieldset);

        $htmlDivConteudo->adicionaObjeto ($htmlForm);

        return $htmlDivConteudo;
    }

    private function montaBotoesParaCRUD () {
        //Array de botões
        $botoes = array ();

        $bt        = new HtmlButton();
        $bt->setName ("bt");
        $bt->setValue ("login");
        $bt->setType ("submit");
        $bt->setTexto ("Logar");
        $botoes [] = $bt;
        
        $bt        = new HtmlButton();
        $bt->setName ("bt");
        $bt->setValue ("cadastrar");
        $bt->setType ("submit");
        $bt->setTexto ("Cadastrar");
        $botoes [] = $bt;

        return $botoes;
    }

    private function montaParagrafoCPF () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("CPF");

        $input = new HtmlInput();
        $input->setName ("clieCPF");
        $input->setType ("number");
        $input->setValue ($this->clienteModel->getClieCPF ());
        $input->setPlaceholder ("CPF (somente os números)");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos (array ($label, $input));

        return $htmlP;
    }

    public function recebeChaveDaConsulta () {
        return $this->getValorOuNull ("clieCPF");
    }

    /**
     * Recebe os dados do formulário, aplica as checagens por códigos 
     * maliciosos, e monta na model do objeto ou numa StdClass.
     */
    public function recebeDadosDaInterface () {
        $clienteModel = new ClienteModel();

        $clienteModel->setClieCPF ($this->getValorOuNull ("clieCPF"));
/*        $clienteModel->setClieNome ($this->getValorOuNull ("clieNome"));
        $clienteModel->setClieEndereco ($this->getValorOuNull ("clieEndereco"));
        $clienteModel->setClieComplementoDoEndereco ($this->getValorOuNull ("clieComplementoDoEndereco"));
        $clienteModel->setClieUF ($this->getValorOuNull ("clieUF"));
        $clienteModel->setClieCidade ($this->getValorOuNull ("clieCidade"));
        $clienteModel->setClieCEP ($this->getValorOuNull ("clieCEP"));
        $clienteModel->setClieFone ($this->getValorOuNull ("clieFone"));
        $clienteModel->setClieDataDeNascimento ($this->getValorOuNull ("clieDataDeNascimento"));
        $clienteModel->setClieEmail ($this->getValorOuNull ("clieEmail"));*/

        return $clienteModel;
    }

    function getClienteModel () {
        return $this->clienteModel;
    }

    function setClienteModel ($clienteModel): void {
        $this->clienteModel = $clienteModel;
    }

}
?>