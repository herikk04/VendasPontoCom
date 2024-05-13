<?php
/**
 * Este é um Código Iniciado na Fábrica de Software Do IFG - Câmpus Inhumas, e  foi modificado durante a materia de Programação para Web do 3º Ano do tecnico de Informática para Web Integrado ao Ensino Médio.
 * 
 * Professor: Elymar Pereira Cabral
 * ALunos: Herik Kauan de Assis e Thiago Ferreira dos Santos
 * 
 * 
 * Descrição de CPF:
 * Esta classe implementa métodos de tratamento de string de CPF.
 * 
 * A princípio não pode ser extendida e por isso deve ser Final.
 * 
 */
final class CPF {
    /*
     * Método __construct()
     * O método construtor está declarado como private para impedir que se crie instâncias de CPF.
     */

    private function __construct () {
        
    }

    /**
     * Método retiraMascaraCPF
     * Retira a máscara do parâmetro e retorna o CPF puro.
     * @param $cpf = cpf com a máscara
     */
    public static function retiraMascaraCPF ($cpf) {
        //return ereg_replace('[^0-9]', '', $cpf);
        //EPC - 19/08/2016 - troque o ereg_replace pelo preg_replace por quetão de compatibilidade do PHP 7.0
        return preg_replace ('/[^0-9]/', '', $cpf);
    }

    public static function validaCPF ($cpf = NULL) {
        if (empty ($cpf)) {
            return false;
        }

        // Retira máscara
        //$cpf = ereg_replace('[^0-9]', '', $cpf);
        $cpf = self::retiraMascaraCPF ($cpf);

        // str_pad = formata a string com tamanho 11 e preenche com '0' à esquerda.
        $cpf = str_pad ($cpf, 11, '0', STR_PAD_LEFT);

        if (strlen ($cpf) != 11) {
            return false;
        } else {
            if ($cpf == '00000000000' ||
                    $cpf == '11111111111' ||
                    $cpf == '22222222222' ||
                    $cpf == '33333333333' ||
                    $cpf == '44444444444' ||
                    $cpf == '55555555555' ||
                    $cpf == '66666666666' ||
                    $cpf == '77777777777' ||
                    $cpf == '88888888888' ||
                    $cpf == '99999999999') {
                return false;
            } else {
                for ($t = 9; $t < 11; $t++) {
                    for ($d = 0, $c = 0; $c < $t; $c++) {
                        $d += $cpf[$c] * (($t + 1) - $c);
                    }
                    $d = ((10 * $d) % 11) % 10;
                    if ($cpf[$c] != $d) {
                        return false;
                    }
                }

                return true;
            }
        }
    }

}
?>
