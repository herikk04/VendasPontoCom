<?php
require_once 'htmlabstract.class.php'; //Importa a classe mãe

class HtmlBody extends HtmlAbstract {

    //No HTML5 a tag body possui somente os atributos e eventos globais, 
    //dispensando declarações de atributos e método construtor.
    //Esta tag possue inúmeras subtags o que torna mais simples usar o array tags 
    //da classe mãe para guardar todas.
    public function geraCodigoDaTag() {
        $html = "\n\t<body{$this->geraAtributosGlobais()}>";
        //Este método da classe mãe gera todas subtags de Body guardadas no array 
        //tags.
        $html .= $this->geraCodigoDasSubTags();
        $html .= "\n\t</body>";
        return $html;
    }

}
?>