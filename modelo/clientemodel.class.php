<?php
require_once 'modelabstract.class.php';
require_once '../classes/cpf.class.php';
require_once '../classes/data.class.php';

class ClienteModel extends ModelAbstract {
    private $clieCPF                   = null;
    private $clieNome                  = null;
    private $clieEndereco              = null;
    private $clieComplementoDoEndereco = null;
    private $clieUF                    = null;
    private $clieCidade                = null;
    private $clieCEP                   = null;
    private $clieFone                  = null;
    private $clieDataDeNascimento      = null;
    private $clieEmail                 = null;

    function __construct ($clieCPF = null, $clieNome = null,
                          $clieEndereco = null,
                          $clieComplementoDoEndereco = null, $clieUF = null,
                          $clieCidade = null, $clieCEP = null, $clieFone = null,
                          $clieDataDeNascimento = null, $clieEmail = null) {
        parent::__construct ();

        $this->clieCPF                   = $clieCPF;
        $this->clieNome                  = $clieNome;
        $this->clieEndereco              = $clieEndereco;
        $this->clieComplementoDoEndereco = $clieComplementoDoEndereco;
        $this->clieUF                    = $clieUF;
        $this->clieCidade                = $clieCidade;
        $this->clieCEP                   = $clieCEP;
        $this->clieFone                  = $clieFone;
        $this->clieDataDeNascimento      = $clieDataDeNascimento;
        $this->clieEmail                 = $clieEmail;
    }

    function getClieCPF () {
        return $this->clieCPF;
    }

    function getClieNome () {
        return $this->clieNome;
    }

    function getClieEndereco () {
        return $this->clieEndereco;
    }

    function getClieComplementoDoEndereco () {
        return $this->clieComplementoDoEndereco;
    }

    function getClieUF () {
        return $this->clieUF;
    }

    function getClieCidade () {
        return $this->clieCidade;
    }

    function getClieCEP () {
        return $this->clieCEP;
    }

    function getClieFone () {
        return $this->clieFone;
    }

    function getClieDataDeNascimento () {
        return $this->clieDataDeNascimento;
    }

    function getClieEmail () {
        return $this->clieEmail;
    }

    function setClieCPF ($clieCPF) {
        $this->clieCPF = $clieCPF;
    }

    function setClieNome ($clieNome) {
        $this->clieNome = $clieNome;
    }

    function setClieEndereco ($clieEndereco) {
        $this->clieEndereco = $clieEndereco;
    }

    function setClieComplementoDoEndereco ($clieComplementoDoEndereco) {
        $this->clieComplementoDoEndereco = $clieComplementoDoEndereco;
    }

    function setClieUF ($clieUF) {
        $this->clieUF = $clieUF;
    }

    function setClieCidade ($clieCidade) {
        $this->clieCidade = $clieCidade;
    }

    function setClieCEP ($clieCEP) {
        $this->clieCEP = $clieCEP;
    }

    function setClieFone ($clieFone) {
        $this->clieFone = $clieFone;
    }

    function setClieDataDeNascimento ($clieDataDeNascimento) {
        $this->clieDataDeNascimento = $clieDataDeNascimento;
    }

    function setClieEmail ($clieEmail) {
        $this->clieEmail = $clieEmail;
    }

    public function checaAtributos () {
        $atributosOk = true;

        //$clieCPF
        $cpfOk = CPF::validaCPF ($this->clieCPF);
        if ($cpfOk) {
            //continua...
        } else {
            $atributosOk = false;
            $this->adicionaMensagem ("Informe um CPF correto!");
        }
        //$clieNome
        if (is_null ($this->clieNome) || trim ($this->clieNome) == '') {
            $atributosOk = false;
            $this->adicionaMensagem ("O nome deve ser informado!");
            }
        
        //$clieEndereco
        if (is_null ($this->clieEndereco) || trim ($this->clieEndereco) == '') {
            $atributosOk = false;
            $this->adicionaMensagem ("O endereço deve ser informado!");
        }
        //$clieComplementoDoEndereco
        if (is_null ($this->clieComplementoDoEndereco) || trim ($this->clieComplementoDoEndereco) == '') {
            //Se não for informado ok.
        } else {
            //Se informado deve ser conter no máximo 30 caracteres.
            if (strlen ($this->clieComplementoDoEndereco) > 30) {
                $atributosOk = false;
                $this->adicionaMensagem ("Informe no máximo 30 caracteres pro complemento!");
            }
        }
        //$clieUF
        if (is_null ($this->clieUF) || trim ($this->clieUF) == '') {
            $atributosOk = false;
            $this->adicionaMensagem ("A Unidade da Federação deve ser informada!");
        } else {
            //Deve ser conter no máximo 2 caracteres.
            if (strlen ($this->clieUF) > 2) {
                $atributosOk = false;
                $this->adicionaMensagem ("Informe no máximo 30 caracteres pro complemento!");
            }
        }
        //$clieCidade
        if (is_null ($this->clieCidade) || trim ($this->clieCidade) == '') {
            $atributosOk = false;
            $this->adicionaMensagem ("A cidade deve ser informada!");
        }
        //$clieCEP
        if (is_null ($this->clieCEP) || trim ($this->clieCEP) == '') {
            $atributosOk = false;
            $this->adicionaMensagem ("O CEP deve ser informado!");
        } else {
            /**
             * 0 - O padrão não bate.
             * 1 - O padrão bate.
             * False - ocorreu um erro.
             * Lembrando que 0 e False são iguis mas não idênticos
             */
            $r = preg_match ('/^[0-9]{8}$/', $this->clieCEP);
            if ($r === 0) {
                $atributosOk = false;
                $this->adicionaMensagem ("Informe um valor válido para o CEP!");
            } else {
                if ($r === false) {
                    $atributosOk = false;
                    $this->adicionaMensagem ("Ocorreu um erro no sistema na checagem do CEP!");
                }
            }
        }
        //$clieFone
        if (is_null ($this->clieFone) || trim ($this->clieFone) == '') {
            $atributosOk = false;
            $this->adicionaMensagem ("O telefone deve ser informado!");
        } else {
            //Só números. Mín. 10 e máx. 15 dígitos (DDD + Fone).
            $r = preg_match ('/^[0-9]{10,15}$/', $this->clieFone);
            if ($r === 0) {
                $atributosOk = false;
                $this->adicionaMensagem ("Informe o número de telefone corretamente!");
            }
        }
        //$clieDataDeNascimento
        if (is_null ($this->clieDataDeNascimento) || trim ($this->clieDataDeNascimento) == '') {
            $atributosOk = false;
            $this->adicionaMensagem ("A data de nascimento deve ser informada!");
        } else {
            //Checa se a data de nascimento é válida.
            if (Data::validaDataNoFormatoDoBD ($this->clieDataDeNascimento)) {
                //Data ok. Continua...
            } else {
                $atributosOk = false;
                $this->adicionaMensagem ("A data de nascimento está incorreta!");
            }
        }
        //$clieEmail
        if (is_null ($this->clieEmail) || trim ($this->clieEmail) == '') {
            $atributosOk = false;
            $this->adicionaMensagem ("O e-mail deve ser informado!");
        } else {
            //Verifica se o e-mail é válido.
            //Expressão regular usada do exemplo no endereço: 
            //https://tableless.com.br/o-basico-sobre-expressoes-regulares/#:~:text=Express%C3%A3o%20Regular%20%C3%A9%20uma%20das%20ferramentas%20mais%20%C3%BAteis%20que%20voc%C3%AA%20pode%20ter.
            if (preg_match ('/^\w*(\.\w*)?@\w*\.[a-z]+(\.[a-z]+)?$/', $this->clieEmail)) {
                //E-mail ok. Continua...
            } else {
                $atributosOk = false;
                $this->adicionaMensagem ("Informe um e-mail válido!");
            }
        }

        return $atributosOk;
    }

}
?>