<?php

/**
 * Classe com métodos estáticos para checagem de data
 * 
 */
final class Data {
    /*
     * Método __construct()
     * O método construtor está declarado como private para impedir que se crie instâncias de CPF.
     */

    private function __construct() {
        
    }

    /**
     * Verifica se uma data informada no formatod do banco de dados está 
     * correta.
     * 
     * O formato do banco de dados é ano, mês e dia separados com o traço: 
     * aaaa-mm-dd.
     * 
     * @param type $data Data no formarto do BD: aaaa-mm-dd
     * @return boolean True se data ok ou False, caso contrário
     */
    public static function validaDataNoFormatoDoBD($data) {
        if (empty($data)) {
            return false;
        }

        $d = DateTime::createFromFormat('Y-m-d', $data);
        if ($d === false) {
            return false;
        }

        return checkdate(substr($data, 5, 2), substr($data, 8, 2), substr($data, 0, 4));
    }

    public static function getDataDoSistemaNoFormatoDoBD() {
        $dataAtual = new DateTime("now", new DateTimeZone("America/Sao_Paulo"));
        return $dataAtual->format("Y-m-d");
    }

    public static function getDataEHoraNoFormatoBD() {
        $dataAtual = new DateTime("now", new DateTimeZone("America/Sao_Paulo"));
        return $dataAtual->format("Y-m-d H:i:s");
    }

}

?>