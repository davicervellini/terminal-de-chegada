<?php

require_once __DIR__ . "/GetterSetter.Class.php";

class Caminhao extends GetterSetter
{
    public $conn;

    public function __construct($codigo = '')
    {
        $this->conn = new ConexaoMySQL();

        if ($codigo != "") {

            $sql = "SELECT CAM_CODIGO               as Codigo,
						   CAM_NOME                 as Nome
					FROM caminhoes WHERE RECNO = :RECNO";
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

        $sSQL = "SELECT RECNO, CAM_NOME, CAM_CODIGO FROM caminhoes";
        $sQRY = $this->conn->prepare($sSQL);
        $sQRY->execute();

        return $sQRY->fetchAll(PDO::FETCH_ASSOC);
    }

    public function resgataRecno($ferCodigo)
    {

        $sSQL = "SELECT RECNO FROM caminhoes WHERE CAM_CODIGO = :CAM_CODIGO";
        $sQRY = $this->conn->prepare($sSQL);
        $sQRY->bindParam(':CAM_CODIGO', $ferCodigo);
        $sQRY->execute();

        $ln  = $sQRY->fetch(PDO::FETCH_ASSOC);

        return $ln["RECNO"];
    }
}
