<?php
require_once "interfaceabstract.class.php";

class ClienteView extends InterfaceAbstract {
    private $clienteModel = null;

    function __construct ($titulo = "Cadastro de Cliente") {
        parent::__construct ($titulo);
        $this->clienteModel = new ClienteModel();
    }

/*    protected function montaDivConsulta () {
        $htmlDivConsulta = new HtmlDiv();
        //Pode-se usar o mesmo id do conteúdo pois a configuração no CSS é igual.
        $htmlDivConsulta->setId ("conteudo");

        //Monta o fieldset com as entradas de dados uma por parágrafo.
        $htmlLegend   = new HtmlLegend ("Consulta");
        $htmlFieldset = new HtmlFieldset ($htmlLegend);
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoParaConsultaDeCliente ());

        //Botão de consulta.
        $bt = new HtmlButton();
        $bt->setName ("bt");
        $bt->setValue ("consultar");
        $bt->setType ("submit");
        $bt->setTexto ("Consultar");

        $htmlFieldset->adicionaObjeto ($bt);

        //Cria o formulário
        $htmlForm = new HtmlForm();
        $htmlForm->setAction ("");
        $htmlForm->setMethod ("post");
        $htmlForm->adicionaObjeto ($htmlFieldset);

        $htmlDivConsulta->adicionaObjeto ($htmlForm);

        return $htmlDivConsulta;
    }

    private function montaParagrafoParaConsultaDeCliente () {
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
        //Monta Array de options
        $option  = new HtmlOption();
        $option->setValue (-1);
        $option->adicionaObjeto ("Escolha um cliente...");
        $options = array ($option);
        foreach ($clientes as $clientesModel) {
            $option     = new HtmlOption();
            $option->setValue ($clientesModel->getClieCPF ());
            $option->adicionaObjeto ($clientesModel->getClieCPF () . ' - ' . $clientesModel->getClieNome ());
            $options [] = $option;
        }
        //Adiciona o array de options ao combo.
        $combo->adicionaOptions ($options);

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos (array ($label, $combo));

        return $htmlP;
    }
*/
    protected function montaDivConteudo () {
        $htmlDivConteudo = new HtmlDiv();
        $htmlDivConteudo->setId ("conteudo");

        //Cria o fieldset.
        $htmlLegend   = new HtmlLegend ("Cliente");
        $htmlFieldset = new HtmlFieldset ($htmlLegend);

        //Monta o formulário com as entradas de dados uma por parágrafo.
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoCPF ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoNome ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoEndereco ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoComplementoDoEndereco ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoUF ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoCidade ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoCEP ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoFone ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoDataDeNascimento ());
        $htmlFieldset->adicionaObjeto ($this->montaParagrafoEmail ());

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


        if (is_null ($this->clienteModel->getClieCPF ())) {
            //caso o cpf esteja nulo quer dizer que a tela está sendo montada 
            //para inclusão somente, logo não se deve montar os botões de 
            //alteração e exclusão.
            //Botão de insersão.
            $bt        = new HtmlButton();
            $bt->setName ("bt");
            $bt->setValue ("inserir");
            $bt->setType ("submit");
            $bt->setTexto ("Inserir");
            $botoes [] = $bt;
        }/* else {
            //se o cpf não estiver nulo significa que foi realizada uma 
            //consulta, os dados do produto deem ser preenchidos e os botões que
            //permitem alteração e exclusão devem ser disponibilizados.
            //Botão de alteração.
            $bt        = new HtmlButton();
            $bt->setName ("bt");
            $bt->setValue ("alterar");
            $bt->setType ("submit");
            $bt->setTexto ("Alterar");
            $botoes [] = $bt;
            //Botão de exclusão.
            $bt        = new HtmlButton();
            $bt->setName ("bt");
            $bt->setValue ("excluir");
            $bt->setType ("submit");
            $bt->setTexto ("Excluir");
            $botoes [] = $bt;
        }*/

        //Botão limpar.
        $bt        = new HtmlButton();
        $bt->setName ("bt");
        $bt->setValue ("limpar");
        $bt->setType ("submit");
        $bt->setTexto ("Limpar");
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

    private function montaParagrafoNome () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("Nome");

        $input = new HtmlInput();
        $input->setName ("clieNome");
        $input->setType ("text");
        $input->setValue ($this->clienteModel->getClieNome ());
        $input->setPlaceholder ("Nome completo");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos (array ($label, $input));

        return $htmlP;
    }

    private function montaParagrafoEndereco () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("Endereço");

        $input = new HtmlInput();
        $input->setName ("clieEndereco");
        $input->setType ("text");
        $input->setValue ($this->clienteModel->getClieEndereco ());
        $input->setPlaceholder ("Endereço completo");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos (array ($label, $input));

        return $htmlP;
    }

    private function montaParagrafoComplementoDoEndereco () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("Complemento");

        $input = new HtmlInput();
        $input->setName ("clieComplementoDoEndereco");
        $input->setType ("text");
        $input->setValue ($this->clienteModel->getClieComplementoDoEndereco ());
        $input->setPlaceholder ("Complemento do endereço");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos (array ($label, $input));

        return $htmlP;
    }

    private function montaParagrafoUF() {
        
        $label = new HtmlLabel();
        $label->adicionaObjeto("UF");

        $UF = array("AC" => "Acre", "AL" => "Alagoas", "AP" => "Amapá","AM" => "Amazonas",
        "BA" => "Bahia", "CE" => "Ceará","DF" => "Distrito Federal",
        "ES" => "Espírito Santo","GO" => "Goiás", "MA" => "Maranhão", "MT" => "Mato Grosso", "MS" => "Mato Grosso do Sul",
        "MG" => "Minas Gerais", "PA" => "Pará", "PB" => "Paraíba","PR" => "Paraná", "PE" => "Pernambuco","PI" => "Piauí",
        "RJ" => "Rio de Janeiro", "RN" => "Rio Grande do Norte", "RS" => "Rio Grande do Sul", "RO" => "Rondônia",
        "RR" => "Roraima", "SC" => "Santa Catarina","SP" => "São Paulo", "SE" => "Sergipe", "TO" => "Tocantins");

        $select = new HtmlSelect();
        $select->setName("clieUF");

        foreach($UF as $unidade => $nomeUF){
            $option = new HtmlOption();
            $option->setValue($unidade);
            $option->adicionaObjeto($nomeUF);
            $select->adicionaOption($option);
        } 

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos(array($label, $select));
       
        return $htmlP;
    }

    private function montaParagrafoCidade () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("Cidade");

        $input = new HtmlInput();
        $input->setName ("clieCidade");
        $input->setType ("text");
        $input->setValue ($this->clienteModel->getClieCidade ());
        $input->setPlaceholder ("Cidade");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos (array ($label, $input));

        return $htmlP;
    }

    private function montaParagrafoCEP () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("CEP");

        $input = new HtmlInput();
        $input->setName ("clieCEP");
        $input->setType ("text");
        $input->setValue ($this->clienteModel->getClieCEP ());
        $input->setPlaceholder ("CEP");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos (array ($label, $input));

        return $htmlP;
    }

    private function montaParagrafoFone () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("Telefone");

        $input = new HtmlInput();
        $input->setName ("clieFone");
        $input->setType ("text");
        $input->setValue ($this->clienteModel->getClieFone ());
        $input->setPlaceholder ("Telefone com DDD");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos (array ($label, $input));

        return $htmlP;
    }

    private function montaParagrafoDataDeNascimento () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("Data de nascimento");

        $input = new HtmlInput();
        $input->setName ("clieDataDeNascimento");
        $input->setType ("date");
        $input->setValue ($this->clienteModel->getClieDataDeNascimento ());
        $input->setPlaceholder ("Data de nascimento");

        $htmlP = new HtmlP();
        $htmlP->adicionaObjetos (array ($label, $input));

        return $htmlP;
    }

    private function montaParagrafoEmail () {
        $label = new HtmlLabel();
        $label->adicionaObjeto ("E-mail");

        $input = new HtmlInput();
        $input->setName ("clieEmail");
        $input->setType ("email");

        $input->setValue ($this->clienteModel->getClieEmail ());
        $input->setPlaceholder ("E-mail");

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
        $clienteModel->setClieNome ($this->getValorOuNull ("clieNome"));
        $clienteModel->setClieEndereco ($this->getValorOuNull ("clieEndereco"));
        $clienteModel->setClieComplementoDoEndereco ($this->getValorOuNull ("clieComplementoDoEndereco"));
        $clienteModel->setClieUF ($this->getValorOuNull ("clieUF"));
        $clienteModel->setClieCidade ($this->getValorOuNull ("clieCidade"));
        $clienteModel->setClieCEP ($this->getValorOuNull ("clieCEP"));
        $clienteModel->setClieFone ($this->getValorOuNull ("clieFone"));
        $clienteModel->setClieDataDeNascimento ($this->getValorOuNull ("clieDataDeNascimento"));
        $clienteModel->setClieEmail ($this->getValorOuNull ("clieEmail"));

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