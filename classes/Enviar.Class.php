<?php

require_once __DIR__ . "/GetterSetter.Class.php";

class Enviar extends GetterSetter
{
    private $dados;

    public function __construct(){}

    public function salvarAquivo($arquivo){
        $uploaddir = '../../upload/';
        $uploadfile = $uploaddir . basename($arquivo['arquivo']['name']);

        if (move_uploaded_file($arquivo['arquivo']['tmp_name'], $uploadfile)) {
            return "Arquivo válido e enviado com sucesso.\n";
        } else {
            return "Possível ataque de upload de arquivo!\n";
        }
    }
    
    public function lerAquivo($nomeArquivo){
        $arquivo = fopen ('../../upload/'. $nomeArquivo, 'r');
        $texte = "";
        while(!feof($arquivo)){
            while (($linha = fgets($arquivo, 4096)) !== false) {
                $texte .= $linha . '<br />';
            }
        }

        fclose($arquivo);
        $this->_setDados($texte);
        return ;
    }

    public function getDados()
    {
        return $this->dados;
    }

    private function _setDados($dados)
    {
        $this->dados = $dados;

        return $this;
    }
}
