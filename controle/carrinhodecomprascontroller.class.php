<?php

require_once 'controllerabstract.class.php';
require_once '../visao/carrinhodecomprasview.class.php';
require_once '../visao/carrinhodecomprasviewmensagem.class.php';
require_once '../modelo/carrinhodecomprasmodel.class.php';
require_once '../ado/carrinhodecomprasado.class.php';
require_once '../modelo/produtomodel.class.php';
require_once '../ado/produtoado.class.php';
require_once '../modelo/clientemodel.class.php';
require_once '../ado/clienteado.class.php';

class CarrinhoDeComprasController extends ControllerAbstract {

    private $acao = null;
    private $carrinhoDeComprasView = null;
    private $clieCPF;

    public function __construct() {
        $this->carrinhoDeComprasView = new CarrinhoDeComprasView();

        session_start();
        //session_destroy();
        //$_SESSION['clieCPF'] = null;

        if (isset($_SESSION['clieCPF'])) {
            $this->verificaAcao();
        } else {
            $this->carrinhoDeComprasView = new CarrinhoDeComprasViewMensagem();
            $this->carrinhoDeComprasView->adicionaMensagem("Não há usuario logado. Faça login.");
        }

        $this->carrinhoDeComprasView->geraInterface();
    }

    private function verificaAcao() {
        $this->clieCPF = $_SESSION['clieCPF'];
        $this->acao = $this->carrinhoDeComprasView->getAcao();
        $clienteADO = new ClienteADO();
        $buscou = $scCliente = $clienteADO->buscaCliente($this->clieCPF);
        if ($buscou) {
            $carrinhoDeComprasModel = new CarrinhoDeComprasModel($scCliente->getClieCPF());
            $this->carrinhoDeComprasView->setCarrinhoDeComprasModel($carrinhoDeComprasModel);
            $this->carrinhoDeComprasView->setClieNome($scCliente->getClieNome());
        } else {
            //se retornar com erro repassa as mensagens para a interface.
            $this->carrinhoDeComprasView->adicionaMensagem("Não foi possível encontrar o cliente! Tente novamente ou informe o problema ao responsável pelo sistema.");
        }

        switch ($this->acao) {
            case "nova" :
                //Se for uma nova tela não precisa fazer nada!
                break;

            case "acrescentar":
                $this->acrescentaProduto();

                break;

            case "retirar":
                $this->retiraProduto();

                break;

            case "continuar":
                header("location:efetivacompra.php");

                break;

            case "limpar":
                $this->esvaziaOCarrinho();

                break;
        }
    }

    /**
     * Ao acrescentar um produto ao carrinho pela primeira vez deve-se:
     * I) Verificar se o CPF existe;
     * II) Verificar se o Produto existe e se tem estoque;
     * III) Verificar se o Produto já está no carrinho e se está incrementar 
     * a quantidade;
     * IV) Ao acrescentar ao carrinho deve-se decrementar o estoque
     */
    private function acrescentaProduto() {
        //criar variaveis
        $carrinhoDeComprasAdo = new CarrinhoDeComprasADO();
        $carrinhoDeComprasModel = new CarrinhoDeComprasModel();
        $produtoAdo = new ProdutoADO();
        $produtoModel = new ProdutoModel();

        //receber CPF E prodId
        $produtoASerAcrescentado = $this->carrinhoDeComprasView->recebeChaveDaConsulta();
        //monta produto model com o id
        $produtoModel->setProdId($produtoASerAcrescentado->getCarrProdId());
        //monta produto model em produto ado
        $produtoAdo->setProdutoModel($produtoModel);

        //iniciar transaçao
        $carrinhoDeComprasAdo->iniciaTransacao();

        //verificar se existe o produto e se tem estoque
        $instrucao = $produtoAdo->montaInstrucaoSelect($produtoASerAcrescentado->getCarrProdId());
        $executou = $carrinhoDeComprasAdo->executaPsComTransacao($instrucao[0], $instrucao[1]);
        //se ok continua, senao rollback e sai com mensagem de erro
        if ($executou) {
            //continua...
        } else {
            $carrinhoDeComprasAdo->descartaTransacao();
            $this->carrinhoDeComprasView->adicionaMensagem("Nao foi possivel acrescentar o produto pq ele nao foi encontrado.");
            return false;
        }
        //ler dados do produto para verificar estoque
        $qtdeProduto = $carrinhoDeComprasAdo->qtdeLinhas();
        if ($qtdeProduto == 0) {
            $carrinhoDeComprasAdo->descartaTransacao();
            $this->carrinhoDeComprasView->adicionaMensagem("Nao foi possivel acrescentar o produto pq ele nao foi encontrado.");
            return false;
        }
        $leu = $scProduto = $carrinhoDeComprasAdo->leTabelaBD(5);
        if ($leu) {
            if ($scProduto->prodQtdeEmEstoque > 0) {
                //continua...
            } else {
                $carrinhoDeComprasAdo->descartaTransacao();
                $this->carrinhoDeComprasView->adicionaMensagem("Nao foi possivel acrescentar o produto pq ele nao foi encontrado.");
                return false;
            }
        } else {
            $carrinhoDeComprasAdo->descartaTransacao();
            $this->carrinhoDeComprasView->adicionaMensagem("Nao foi possivel acrescentar o produto pq ele nao foi encontrado.");
            return false;
        }

        //montar update do produto com novo estoque
        $instrucao = $produtoAdo->montaInstrucaoDeAlteracaoEArrayDeColunasEValores(
                array("prodQtdeEmEstoque" => $scProduto->prodQtdeEmEstoque - 1)
        );
        //executar instruçao de update do estoque e executar
        $executou = $carrinhoDeComprasAdo->executaPsComTransacao($instrucao[0], $instrucao[1]);
        //se ok continua, senao rollback e sai com mensagem de erro
        if ($executou) {
            //continua...
        } else {
            $carrinhoDeComprasAdo->descartaTransacao();
            $this->carrinhoDeComprasView->adicionaMensagem("Nao foi possivel alterar o estoque do produto.");
            return false;
        }

        //verificar se o produto está no carrinho
        //Ao acrescentar o produto pela primeira vez na tela deve-se iniciar a
        //sua quantidade com 1. Mas, se já existe o mesmo produto no carrinho
        //deve-se incrementar a quantidade.
        //Identificar se o produto é novo no carrinho ou está sendo 
        //incrementado.
        $novoProduto = true;

        //Primero verifica se o produto já está no carrinho.
        $instrucao = $carrinhoDeComprasAdo->montaSelectProdutoDeUmCarrinho();
        /*                $produtoASerAcrescentado->getCarrClieCPF(),
          $produtoASerAcrescentado->getCarrProdId()
          ); */
        $executou = $carrinhoDeComprasAdo->executaPsComTransacao(
                $instrucao,
                array(
                    $produtoASerAcrescentado->getCarrClieCPF(),
                    $produtoASerAcrescentado->getCarrProdId()
                )
        );

        $leu = $carrinhoDeComprasModel = $carrinhoDeComprasAdo->leObjeto();

        if ($executou) {
            if ($carrinhoDeComprasAdo->qtdeLinhas() === 0) {
                $novoProduto = true;
                $carrData = Data::getDataDoSistemaNoFormatoDoBD();
                $carrinhoDeComprasModel = new CarrinhoDeComprasModel(
                        $produtoASerAcrescentado->getCarrClieCPF(),
                        $produtoASerAcrescentado->getCarrProdId(),
                        1,
                        $carrData
                );
            } else {
                $novoProduto = false;
                $novaQtde = $carrinhoDeComprasModel->getCarrQtdeProduto() + 1;
                $carrinhoDeComprasModel->setCarrQtdeProduto($novaQtde);
            }
        } else {
            $carrinhoDeComprasAdo->descartaTransacao();
            $this->setMensagem("Ocorreu um erro ao tentar acrescentar o produto ao carrinho! Tente novamente ou informe o problema ao responsável pelo sistema.");
            return false;
        }

        //incluir ou alterar o produto no carrinho e decrementar a quantidade no estoque do produto.
        $carrinhoDeComprasAdo->setCarrinhoDeComprasModel($carrinhoDeComprasModel);
        if ($novoProduto) {
            //monta inserção para novo produto no carrinho
            $instrucao = $carrinhoDeComprasAdo->montaInstrucaoDeInsersaoEArrayDeColunasEValores();
        } else {
            //monta alteração para acrescentar mais uma unidade do produto no carrinho
            $colunasParaAlteracao = array(
                "carrQtdeProduto" => $carrinhoDeComprasModel->getCarrQtdeProduto()
            );
            $instrucao = $carrinhoDeComprasAdo->montaInstrucaoDeAlteracaoEArrayDeColunasEValores($colunasParaAlteracao);
        }
        //executa instrução
        $executou = $carrinhoDeComprasAdo->executaPsComTransacao($instrucao[0], $instrucao[1]);
        //se ok continua, senao rollback e sai com mensagem de erro
        if ($executou) {
            //continua...
        } else {
            $carrinhoDeComprasAdo->descartaTransacao();
            $this->carrinhoDeComprasView->adicionaMensagem("Nao foi possivel incluir o produto no carrinho.");
            return false;
        }

        //executar commit e sai com mensagem de ok
        $carrinhoDeComprasAdo->validaTransacao();
        $this->carrinhoDeComprasView->adicionaMensagem("Produto acrescentado com sucesso ao seu carrinho.");
    }

    private function consultaProduto() {
        $prodId = $this->carrinhoDeComprasView->recebeChaveDaConsulta();

        $produtoADO = new ProdutoADO();
        $buscou = $produtoModel = $produtoADO->buscaProduto($prodId);
        if ($buscou) {
            $this->carrinhoDeComprasView->setCarrinhoDeComprasModel($produtoModel);
        } else {
            //se retornar com erro repassa as mensagens para a interface.
            $this->carrinhoDeComprasView->adicionaMensagens("Não foi possível encontrar o produto! Tente novamente ou informe o problema ao responsável pelo sistema.");
        }
    }

    /**
     * @todo ESTE MÉTODO DEVE RETIRAR O PRODUTO DO CARRINHO QUANDO A QUANTIDADE 
     * ESTIVER IGUAL A 1 OU DECREMENTAR A QUANTIDADE DO PRODUTO NO CARRINHO 
     * QUANDO A QUANTIDADE FOR MAIOR DO QUE 1 E AO MESMO TEMPO INCREMENTAR DE 1
     * O ESTOQUE DO PRODUTO, OU SEJA, O INVERSO DO MÉTODO acrescentaProduto().
     *  TEM QUE USAR TRANSAÇÃO.
     */
    private function retiraProduto() {
        //Variáveis
        $carrinhoDeComprasAdo = new CarrinhoDeComprasADO();
        $produtoAdo = new ProdutoADO();
        //O produto vem em um objeto do tipo CarrinhoDeComprasModel.
        $produtoASerRetiradoDoCarrinho = $this->carrinhoDeComprasView->recebeDadosDaInterface();
        //$idProdutoProcurado = $produtoASerRetiradoDoCarrinho->getCarrProdId();
        //Inicia transação.
        $carrinhoDeComprasAdo->iniciaTransacao();
        /**
         * 1 - buscar o produto no carrinho do cliente
         *     - montar a chave para busca
         *     - executar o método de busca
         *     - checar se busca bem sucedida
         *       - se ok, continua
         *       - se não ok, monta mensagem para o usuário e sai
         */
        $carrinhoDeComprasAdo->setCarrinhoDeComprasModel($produtoASerRetiradoDoCarrinho);
        $instrucao = $carrinhoDeComprasAdo->montaSelectProdutoDeUmCarrinho();
        //Executa transação.
        $executou = $carrinhoDeComprasAdo->executaPsComTransacao(
                $instrucao,
                array(
                    $produtoASerRetiradoDoCarrinho->getCarrClieCPF(),
                    $produtoASerRetiradoDoCarrinho->getCarrProdId()
                )
        );
        //se ok continua, senao rollback e sai com mensagem de erro
        if ($executou) {
            //continua...
        } else {
            $carrinhoDeComprasAdo->descartaTransacao();
            $this->carrinhoDeComprasView->adicionaMensagem("Não foi possivel excluir o produto pq ele não foi encontrado no carrinho.");
            return false;
        }
        //ler dados do produto para verificar estoque
        $qtdeLinhasEncontradas = $carrinhoDeComprasAdo->qtdeLinhas();
        if ($qtdeLinhasEncontradas == 0) {
            $carrinhoDeComprasAdo->descartaTransacao();
            $this->carrinhoDeComprasView->adicionaMensagem("Nao foi possivel excluir o produto pq ele nao foi encontrado.");
            return false;
        }
        $leu = $scProdutoDoCarrinho = $carrinhoDeComprasAdo->leTabelaBD(5);
        if ($leu) {
            $produtoASerRetiradoDoCarrinho = new CarrinhoDeComprasModel(
                    $scProdutoDoCarrinho->carrClieCPF,
                    $scProdutoDoCarrinho->carrProdId,
                    $scProdutoDoCarrinho->carrQtdeProduto
            );
            $carrinhoDeComprasAdo->setCarrinhoDeComprasModel($produtoASerRetiradoDoCarrinho);
        } else {
            $carrinhoDeComprasAdo->descartaTransacao();
            $this->carrinhoDeComprasView->adicionaMensagem("Nao foi possivel acrescentar o produto pq ele nao foi encontrado.");
            return false;
        }

        /**
         * 2 - buscar o produto.
         */
        //verificar se existe o produto.
        $instrucao = $produtoAdo->montaInstrucaoSelect($produtoASerRetiradoDoCarrinho->getCarrProdId());
        //é necessário executar a instrução na ADO do carrinho pq foi nela que se iniciou a transação.
        $executou = $carrinhoDeComprasAdo->executaPsComTransacao($instrucao[0], $instrucao[1]);
        //se ok continua, senao rollback e sai com mensagem de erro
        if ($executou) {
            //continua...
        } else {
            $carrinhoDeComprasAdo->descartaTransacao();
            $this->carrinhoDeComprasView->adicionaMensagem("Nao foi possivel remover o produto pq ele nao foi encontrado.");
            return false;
        }
        //ler dados do produto para verificar estoque
        $qtdelinhas = $carrinhoDeComprasAdo->qtdeLinhas();
        if ($qtdelinhas == 0) {
            $carrinhoDeComprasAdo->descartaTransacao();
            $this->carrinhoDeComprasView->adicionaMensagem("Nao foi possivel retirar o produto pq ele nao foi encontrado.");
            return false;
        }
        $leu = $scProduto = $carrinhoDeComprasAdo->leTabelaBD(5);
        if ($leu) {
            //atualiza o estoque do produto.
            $scProduto->prodQtdeEmEstoque = $scProduto->prodQtdeEmEstoque + $produtoASerRetiradoDoCarrinho->getCarrQtdeProduto();
        } else {
            $carrinhoDeComprasAdo->descartaTransacao();
            $this->carrinhoDeComprasView->adicionaMensagem("Nao foi possivel retirar o produto pq ele nao foi encontrado.");
            return false;
        }
        /**
         * 3 - montar update do produto com novo estoque
         */
        $produtoModel = new ProdutoModel($scProduto->prodId);
        $produtoAdo->setProdutoModel($produtoModel);
        $instrucao = $produtoAdo->montaInstrucaoDeAlteracaoEArrayDeColunasEValores(
                array("prodQtdeEmEstoque" => $scProduto->prodQtdeEmEstoque)
        );
        //executar instruçao de update do estoque e executar
        $executou = $carrinhoDeComprasAdo->executaPsComTransacao($instrucao[0], $instrucao[1]);
        //se ok continua, senao rollback e sai com mensagem de erro
        if ($executou) {
            //continua...
        } else {
            $carrinhoDeComprasAdo->descartaTransacao();
            $this->carrinhoDeComprasView->adicionaMensagem("Nao foi possivel atualizar o estoque do produto.");
            return false;
        }

        /**
         * 5 - monta a instrução o delete do produto do carrinho do cliente
         */
        $instrucao = $carrinhoDeComprasAdo->montaInstrucaoDeExclusaoEArrayDeColunasEValores();
        //executa a instruçao de delete
        $executou = $carrinhoDeComprasAdo->executaPsComTransacao($instrucao[0], $instrucao[1]);
        //se ok continua, senao rollback e sai com mensagem de erro
        if ($executou) {
            $carrinhoDeComprasAdo->validaTransacao();
            $this->carrinhoDeComprasView->adicionaMensagem("Produto retirado do carrinho com sucesso.");
        } else {
            $carrinhoDeComprasAdo->descartaTransacao();
            $this->carrinhoDeComprasView->adicionaMensagem("Nao foi possivel atualizar o estoque do produto.");
            return false;
        }
    }

    /**
     * 1 - buscar o produto no carrinho do cliente
     *     - montar a chave para busca
     *     - executar o método de busca
     *     - checar se busca bem sucedida
     *       - se ok, continua
     *       - se não ok, monta mensagem para o usuário e sai
     * 2 - buscar o produto na tabela de produtos
     *     - montar a chave para busca
     *     - exeucutar método de busca
     *     - checar se a busca foi bem sucedida
     *     - checar se busca bem sucedida
     *       - se ok, continua
     *       - se não ok, monta mensagem para o usuário e sai
     * 3 - acrescentar à quantidade em estoque do produto a quantidade do produto no carrinho
      $produtoModel->setProdQtdeEmEstoque (
      $produtoModel->getProdQtdeEmEstoque () + $carrinhoDeCompras->getCarrQtdeProduto ()
      );
     * 4 - preparar update do produto
      $instrucoes = array ();
      $produtoADO->setProdutoModel ($produtoModel);
      $colunasParaAlteracao = array ("prodQtdeEmEstoque" => $produtoModel->getProdQtdeEmEstoque ());
      $instrucoes []        = $produtoADO->montaInstrucaoDeAlteracaoEArrayDeColunasEValores ($colunasParaAlteracao);
     * 5 - preparar o delete do produto do carrinho do cliente
      $carrinhoDeComprasADO->setCarrinhoDeComprasModel ($carrinhoDeComprasModel);
      $instrucoes [] = $carrinhoDeComprasADO->montaInstrucaoDeExclusaoEArrayDeColunasEValores();
     * 6 - executar as instruções dentro de uma transação
     *     - checar se a execução foi bem sucedida
     *       - montar mensagem adequada para o usuário     
      $executou = $carrinhoDeComprasADO->executaInstrucoesNumaTransacao ($instrucoes);
      if ($executou) {
      return true;
      } else {
      $this->setMensagem ("Ocorreu um erro desconhecido! Tente novamente ou informe ao responsável pelo sistema.");
      return false;
      }
     */
//        $this->carrinhoDeComprasView->adicionaMensagem (
//                "ESTA É A ROTINA PARA RETIRAR PRODUTOS, QUE VOCÊ AINDA TEM QUE "
//                . "IMPLEMENTAR! O PRODUTO INFORMADO FOI: "
//                . $carrinhoDeCompras->getCarrClieCPF ()
//                . " - "
//                . $carrinhoDeCompras->getCarrProdId ()
//        );

    /**
     * @todo ESTE MÉTODO DEVE APENAS ENCAMINHAR PARA OUTRO MÓDULO QUE APENAS 
     * MOSTRARÁ UMA TELA PARA FINALIZAÇÃO DA COMPRA COM O PARGAMENTO. 
     * VOCÊ NÃO PRECISA IMPLEMENTAR ESTA ROTINA.
     */

    /**
     * @todo ESTE MÉTODO DEVE RETIRAR TODOS OS PRODUTOS DO CARRINHO DO CLIENTE E
     * RETORNAR A QUANTIDADE DE CADA PRODUTO PARA O SEU ESTOQUE. TEM QUE USAR 
     * TRANSAÇÃO.
     */
    private function esvaziaOCarrinho() {
        //$carrinhoDeCompras = $this->carrinhoDeComprasView->recebeDadosDaInterface();
        //$this->carrinhoDeComprasView->setCarrinhoDeComprasModel($carrinhoDeCompras);
        $carrinhoDeComprasADO = new CarrinhoDeComprasADO();
        $produtoADO = new ProdutoADO();
        $novaQtde = null;
        $produtosDoCarrinho = array();

        $carrinhoDeComprasADO->iniciaTransacao();

        $instrucao = $carrinhoDeComprasADO->montaBuscaOCarrinhoDoCliente($_SESSION['clieCPF']);
        $executou = $carrinhoDeComprasADO->executaPsComTransacao($instrucao[0], $instrucao[1]);
        if ($executou) {
            //continua
        } else {
            $this->carrinhoDeComprasView->adicionaMensagem("Não foi possível limpar o carrinho devido a um erro na busca.");
            $carrinhoDeComprasADO->descartaTransacao();
            return false;
        }

        while (($produtoDoCarrinho = $carrinhoDeComprasADO->leTabelaBD(5)) !== FALSE) {
            $produtosDoCarrinho [] = $produtoDoCarrinho;
        }
        foreach ($produtosDoCarrinho as $produtoDoCarrinho) {
            //select * from Produtos where prodId = ?
            $instrucao = $produtoADO->montaInstrucaodeBuscaDoProduto($produtoDoCarrinho->carrProdId);
            $executou = $carrinhoDeComprasADO->executaPsComTransacao($instrucao[0], $instrucao[1]);
            if ($executou) {
                //continua...
            } else {
                $this->carrinhoDeComprasView->adicionaMensagem("1. Não foi possível limpar o carrinho devido a um erro em devolver os produtos ao estoque.");
                $carrinhoDeComprasADO->descartaTransacao();
                return false;
            }
            $leu = $scProduto = $carrinhoDeComprasADO->leTabelaBD(5);
            if ($leu) {
                $novaQtde = $produtoDoCarrinho->carrQtdeProduto + $scProduto->prodQtdeEmEstoque;
            } else {
                $this->carrinhoDeComprasView->adicionaMensagem("2. Não foi possível limpar o carrinho devido a um erro em devolver os produtos ao estoque.");
                $carrinhoDeComprasADO->descartaTransacao();
                return false;
            }
            $colunasParaAlteracao = array("prodQtdeEmEstoque" => $novaQtde);
            $produtoADO = new ProdutoADO(new ProdutoModel($produtoDoCarrinho->carrProdId));
            $instrucao = $produtoADO->montaInstrucaoDeAlteracaoEArrayDeColunasEValores($colunasParaAlteracao);
            $executou = $carrinhoDeComprasADO->executaPsComTransacao($instrucao [0], $instrucao [1]);
            if ($executou) {
                //continua..
            } else {
                $this->carrinhoDeComprasView->adicionaMensagem("3. Não foi possível limpar o carrinho devido a um erro em devolver os produtos ao estoque.");
                $carrinhoDeComprasADO->descartaTransacao();
                return false;
            }
        }

        $instrucao = $carrinhoDeComprasADO->montaInstrucaoDeleteCarrinho($_SESSION["clieCPF"]);
        $executou = $carrinhoDeComprasADO->executaPsComTransacao($instrucao[0], $instrucao[1]);
        if ($executou) {
            $carrinhoDeComprasADO->validaTransacao();
            $this->carrinhoDeComprasView->adicionaMensagem("O carrinho foi limpo com sucesso.");
            return true;
        } else {
            $this->carrinhoDeComprasView->adicionaMensagem("Não foi possível limpar o carrinho devido a um erro na busca.");
            $carrinhoDeComprasADO->descartaTransacao();
            return false;
        }
    }

    /**
     * Verifica as regras de negócio: 
     * I) se o CPF existe;
     * II) se o Produto existe e se tem estoque.
     * 
     * @param CarrinhoDeComprasModel $carrinhoDeComprasModel
     * @return boolean True se tudo ok e false caso cntrário.
     */
    private function checarSePodeAcrescentarProdutoAoCarrinhoDoCliente(CarrinhoDeComprasModel $carrinhoDeComprasModel) {
        //Checagem do CPF
        $this->clienteAdo = new ClienteADO();
        $this->clienteAdo->buscaCliente($carrinhoDeComprasModel->getCarrClieCPF());
        /* if ($buscou) {
          //Cliente ok, continua...
          } else {
          //Não encontrou o cliente. Retorna com erro.
          $this->setMensagem ("O cliente não foi identificado. Confira o CPF informado e certifique-se de estar cadastrado no sistema.");
          return false;
          } */

        //Checagem do Produto
        $produtoAdo = new ProdutoADO();
        $buscou = $produtoModel = $produtoAdo->buscaProduto($carrinhoDeComprasModel->getCarrProdId());
        if ($buscou) {
            //Produto ok, então checa se tem estoque.
            if ($produtoModel->getProdQtdeEmEstoque() > 0) {
                //tem estoque, continua...
            } else {
                $this->setMensagem("O produto está em falta no estoque.");
                return false;
            }
        } else {
            //Não encontrou o produto. Retorna com erro.
            $this->setMensagem("O produto não foi identificado. Tente novamente mais tarde.");
            return false;
        }

        return true;
    }

    /**
     * Este método deve verificar a existência ou não de um produto no carrinho 
     * de um cliente. Se exitir acresceta mais um na quantidade e caso contrário 
     * prepara para incluir o produto no carrinho com quantidade igual a 1.
     * 
     * @param CarrinhoDeComprasModel $produtoASerAcrescentado Produto a ser 
     * acrescentado.
     * @return boolean returna true se correu tudo bem ou false caso contrário.
     */
    private function acrescentaProdutoAoCarrinho(CarrinhoDeComprasModel $produtoASerAcrescentado) {
        //Identificará se o produto é novo no carrinho ou está sendo 
        //incrementado.
        $novoProduto = true;

        //Primero verifica se o produto já está no carrinho.
        $carrinhoDeComprasADO = new CarrinhoDeComprasADO($produtoASerAcrescentado);
        $buscou = $carrinhoDeComprasModel = $carrinhoDeComprasADO->buscaUmProdutoDeUmCarrinho(
                $produtoASerAcrescentado->getCarrClieCPF(),
                $produtoASerAcrescentado->getCarrProdId()
        );

        if ($buscou) {
            $novoProduto = false;
            $carrinhoDeComprasModel->setCarrQtdeProduto($carrinhoDeComprasModel->getCarrQtdeProduto() + 1);
        } else {
            if ($buscou === 0) {
                $carrData = Data::getDataDoSistemaNoFormatoDoBD();
                $carrinhoDeComprasModel = new CarrinhoDeComprasModel(
                        $produtoASerAcrescentado->getCarrClieCPF(),
                        $produtoASerAcrescentado->getCarrProdId(),
                        1,
                        $carrData
                );
            } else {
                $this->setMensagem("Ocorreu um erro ao tentar acrescentar o produto ao carrinho! Tente novamente ou informe o problema ao responsável pelo sistema.");
                return false;
            }
        }

        //Depois de preparar tem que iniciar uma transação, incluir ou alterar o
        //produto no carrinho e decrementar a quantidade no estoque do produto.
        $instrucoes = array();
        $carrinhoDeComprasADO->setCarrinhoDeComprasModel($carrinhoDeComprasModel);
        if ($novoProduto) {
            //monta inserção para novo produto no carrinho
            $instrucoes [] = $carrinhoDeComprasADO->montaInstrucaoDeInsersaoEArrayDeColunasEValores();
        } else {
            //monta alteração para acrescentar mais uma unidade do produto no carrinho
            $colunasParaAlteracao = array(
                "carrQtdeProduto" => $carrinhoDeComprasModel->getCarrQtdeProduto()
            );
            $instrucoes [] = $carrinhoDeComprasADO->montaInstrucaoDeAlteracaoEArrayDeColunasEValores($colunasParaAlteracao);
        }

        //Decremena estoque se tiver estoque para o produto.
        $produtoADO = new ProdutoADO();
        $buscou = $produtoModel = $produtoADO->buscaProduto($produtoASerAcrescentado->getCarrProdId());
        if ($buscou) {
            //Certifica que o produto tem estoque.
            if ($produtoModel->getProdQtdeEmEstoque() > 0) {
                $produtoADO->setProdutoModel($produtoModel);
                $colunasParaAlteracao = array("prodQtdeEmEstoque" => $produtoModel->getProdQtdeEmEstoque() - 1);
                $instrucoes [] = $produtoADO->montaInstrucaoDeAlteracaoEArrayDeColunasEValores($colunasParaAlteracao);
            } else {
                $this->setMensagem("Não foi possível acrescentar o produto porque o seu estoque está vazio.");
                return false;
            }
        } else {
            if ($buscou === 0) {
                $this->setMensagem("Ocorreu um erro ao tentar acrescentar o produto ao carrinho! Não foi possível encontrar o produto.");
                return false;
            } else {
                $this->setMensagem("Ocorreu um erro ao tentar acrescentar o produto ao carrinho! Tente novamente inform o problema ao responsável pelo sistema.");
                return false;
            }
        }

        //Executa todas as intruções dentro de uma transação.
        $executou = $carrinhoDeComprasADO->executaInstrucoesNumaTransacao($instrucoes);
        if ($executou) {
            return true;
        } else {
            $this->setMensagem("Ocorreu um erro desconhecido! Tente novamente ou informe ao responsável pelo sistema.");
            return false;
        }
    }

}

?>