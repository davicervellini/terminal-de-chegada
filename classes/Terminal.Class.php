<?php

require_once __DIR__ . "/GetterSetter.Class.php";

class Terminal extends GetterSetter
{
    public $conn;

    public function __construct($codigo = '')
    {
        $this->conn = new ConexaoMySQL();

        if ($codigo != "") {

            $sql = "SELECT TER_CODIGO               as Codigo,
						   CNE_CPF                  as Cpf,
						   CAM_CODIGO               as CamCodigo,
						   TER_CARREGADO            as Carga,
						   TER_ORIGEM               as CidadeOrigen,
						   TER_DESTINO              as CidadeDestino
					FROM terminal ter
                    INNER JOIN caminhoneiros cne ON (ter.CNE_CODIGO = cne.CNE_CODIGO)
                    WHERE ter.RECNO = :RECNO";
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

        $sSQL = "SELECT ter.RECNO, cne.CNE_NOME, mun.nome
                FROM terminal ter
                INNER JOIN caminhoneiros cne ON (ter.CNE_CODIGO = cne.CNE_CODIGO)
                INNER JOIN municipios mun ON (ter.TER_DESTINO = mun.codigo_ibge)
                WHERE ter.TER_DATA = :DATA
                ORDER BY ter.TER_HORA DESC";
        $sQRY = $this->conn->prepare($sSQL);
        $data = date('Y-m-d');
        $sQRY->bindParam(':DATA', $data);
        $sQRY->execute();

        return $sQRY->fetchAll(PDO::FETCH_ASSOC);
    }

    public function resgataRecno($ferCodigo)
    {

        $sSQL = "SELECT RECNO FROM terminal WHERE CNE_CODIGO = :CNE_CODIGO";
        $sQRY = $this->conn->prepare($sSQL);
        $sQRY->bindParam(':CNE_CODIGO', $ferCodigo);
        $sQRY->execute();

        $ln  = $sQRY->fetch(PDO::FETCH_ASSOC);

        return $ln["RECNO"];
    }
    
    public function getCodigoUf($ibge)
    {

        $sSQL = "SELECT codigo_uf FROM municipios WHERE codigo_ibge = :codigo_ibge";
        $sQRY = $this->conn->prepare($sSQL);
        $sQRY->bindParam(':codigo_ibge', $ibge);
        $sQRY->execute();

        $ln  = $sQRY->fetch(PDO::FETCH_ASSOC);

        return $ln["codigo_uf"];
    }
}
