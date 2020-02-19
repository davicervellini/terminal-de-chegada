<?php

require_once __DIR__ . "/GetterSetter.Class.php";

class Caminhoneiros extends GetterSetter
{
    public $conn;

    public function __construct($codigo = '')
    {
        $this->conn = new ConexaoMySQL();

        if ($codigo != "") {

            $sql = "SELECT CNE_CODIGO               as Codigo,
						   CNE_NOME                 as Nome,
						   CNE_IDADE                as Idade,
						   CNE_SEXO                 as Sexo,
						   CNE_TPCNH                as TpCnh,
						   CNE_CPF                  as Cpf
					FROM caminhoneiros WHERE RECNO = :RECNO";
            $qry = $this->conn->prepare($sql);
            $qry->bindParam(':RECNO', $codigo);
            $qry->execute();

            if ($qry->rowCount() > 0) {
                $result = $qry->fetchAll(PDO::FETCH_ASSOC);
                $this->setData($result);
            }
        }
    }

    public function list()
    {

        $sSQL = "SELECT RECNO, CNE_NOME, CNE_CPF FROM caminhoneiros";
        $sQRY = $this->conn->prepare($sSQL);
        $sQRY->execute();

        return $sQRY->fetchAll(PDO::FETCH_ASSOC);
    }

    public function resgataRecno($ferCodigo)
    {

        $sSQL = "SELECT RECNO FROM caminhoneiros WHERE CNE_CODIGO = :CNE_CODIGO";
        $sQRY = $this->conn->prepare($sSQL);
        $sQRY->bindParam(':CNE_CODIGO', $ferCodigo);
        $sQRY->execute();

        $ln  = $sQRY->fetch(PDO::FETCH_ASSOC);

        return $ln["RECNO"];
    }
    
    public function getNomePorCfp($cpf)
    {

        $sSQL = "SELECT CNE_CODIGO as Codigo, CNE_NOME as Nome  FROM caminhoneiros WHERE CNE_CPF = :CNE_CPF";
        $sQRY = $this->conn->prepare($sSQL);
        $sQRY->bindParam(':CNE_CPF', $cpf);
        $sQRY->execute();

        if ($sQRY->rowCount() > 0) {
            $result = $sQRY->fetchAll(PDO::FETCH_ASSOC);
            $this->setData($result);
        }
    }
}
