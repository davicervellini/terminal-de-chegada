<?php 
	class Sistema{
		
		function gera_codigo($tabela, $nomeConexao = ''){
			if($nomeConexao == ''){
				$conn = new ConexaoMySQL();
			}else{
				$conn = new ConexaoMySQL($nomeConexao);
			}
			$sSQL = "SELECT RECNO AS CODIGO FROM recnos WHERE TABELA = :sTabela ";
			$sQRY = $conn->prepare($sSQL);
			$sQRY->bindParam(':sTabela', $tabela);
			$sQRY->execute();
			if($sQRY->rowCount() > 0){
				$ln  = $sQRY->fetch(PDO::FETCH_ASSOC);
				$res = $ln["CODIGO"];
				
				try{
					$sDados = ["RECNO" => ( $res + 1 )];

					$res = $this->getUpdate("recnos", " TABELA = '".$tabela."' ",  $sDados);
					return $sDados['RECNO'];
					
				}catch(Exception $e){
				    die('Erro ao gerar código:'. $e->getMessage());
				}
			}
			else{
				
				$sDados = ["TABELA" => $tabela, "RECNO"  => 1];

				$res = $this->getInsert("recnos", $sDados);
				return 1;
			}
		}

		function limpaVars($str){
			$strAux = str_replace("(","",$str);
			$strAux = str_replace(")","",$strAux);
			$strAux = str_replace("-","",$strAux);
			$strAux = str_replace(".","",$strAux);
			$strAux = str_replace("/","",$strAux);
			$strAux = str_replace(" ","",$strAux);
			return $strAux;
		}	

		function getInsert($table, array $dados){

			$sql = "INSERT INTO $table ";
			$fields = '';
			$binds  = '';
			foreach ($dados as $key => $value) {
				$fields .= ($fields != "") ? ", " . str_replace(":","",$key) : str_replace(":", "" ,$key) ;
				$binds  .= ($binds != "")  ? ", '" . $value."'" : "'".$value. "'";
			}
			$conn = new ConexaoMySQL();
			$sQRY = $conn->prepare($sql . " ($fields) VALUES ($binds) ");
			$res = $sQRY->execute();
		}

		function getDelete($table,$whereDelete){
		
			$conn = new ConexaoMySQL();
			$sQRY = $conn->prepare("DELETE FROM $table WHERE $whereDelete");
			$res = $sQRY->execute();
		}
		
		function getUpdate($table,$whereUpdate, $dados ){
		
			$fields      = "";
			$updateDados = "";
			foreach ($dados as $key => $value) {
				$setUpdate = str_replace(':', "", $key) . " = '" . $value."'";
				$updateDados .= ($updateDados != "") ? ",".$setUpdate  : $setUpdate ;
			}
			$conn = new ConexaoMySQL();
			$sQRY = $conn->prepare("UPDATE $table SET $updateDados WHERE $whereUpdate");
			$res = $sQRY->execute();
		}

		function padroniza_datas_BR($data){
			$aux = explode("-",$data);
			if($data != "" && $data != NULL){
				$result = $aux[2]."/".$aux[1]."/".$aux[0];
				return $result;
			}else{
				return "";
			}
		}

		function padroniza_datas_US($data){
			$aux = explode("/",$data);
			if($data != ""){
				$result = $aux[2]."-".$aux[1]."-".$aux[0];
				return $result;
			}else{
				return "";
			}
		}

	    public function select($table, $sCampos, $dadosWhere = array(), $bind = false ){
	    	$conn = new ConexaoMySQL();
	    	$where = [];
		    if(count($dadosWhere) > 0){
		        foreach ($dadosWhere as $key => $value) {
		            $bindField = ":".$key;
		            $whereStmt = $key . " = " . $bindField;
		            array_push($where, $whereStmt);
		        }
		        $where = join(" AND ", $where);
		    }else{
		        $where = '';
		    }

		    $campos = '';
		    foreach ($sCampos as $key => $value) {
		    	$key = str_replace(":", "" ,$key);
		    	$campos .= $campos != '' ? ",".$key : $key ;
		    }

	    	$sSQL = "SELECT ".$campos." FROM $table WHERE 1 = 1 AND ".$where." ";
		    $query = $conn->prepare($sSQL);
		    if(count($dadosWhere) > 0){
		        foreach ($dadosWhere as $key => &$value) {
		            $query->bindParam(':'.$key, $value);
		        }
		    }
		    $query->execute();

		    $ln = $query->fetch(PDO::FETCH_ASSOC);
		    if($bind){
			    $sDados = [];
			    foreach ($ln as $key => $value) {
			    	$sDados[':'.$key] = $value;
			    }
			    return $sDados;
		    }else{
		    	return $ln;	
		    }
		    
	    }

	    public function vStrings(array $sDados){
	        $campos = array();
	        $dados  = array();
	        $result = array();
	        foreach ($sDados as $key => $value) {
	            array_push($campos, $key);
	            array_push($dados, $value);
	        }

	        $sCampo = new vStrings($campos);
	        $sDados = new vStrings($dados);

	        $result['campos'] = $sCampo->getVStrings();
	        $result['dados']  = $sDados->getVStrings();

	        return $result;
		} 
		
		function mask($val, $mask = null){
			$maskared = '';
			$k = 0;
			if($mask == null || $mask == ""){
				switch (strlen($val)) {
					case 11:
						$mask = "###.###.###-##";
					break;

					case 9:
						$mask = "##.###.###-#";
					break;

					case 14:
						$mask = "##.###.###/####-##";
					break;

					case 8:
						$mask = "#####-###";
					break;

					default:
						return $maskared;
					break;
				}
			}
			if($val != ""){
				for($i = 0; $i<=strlen($mask)-1; $i++){
					if($mask[$i] == '#'){
						if(isset($val[$k]))
						$maskared .= $val[$k++];
					}else{
						if(isset($mask[$i]))
						$maskared .= $mask[$i];
					}
				}
			}
			return $maskared;
		}

		function getUfs()
		{
			$conn = new ConexaoMySQL();
			$sSQL = "SELECT codigo_uf, uf FROM estados ORDER BY uf";
			$sQRY = $conn->prepare($sSQL);
			$sQRY->execute();

			$select = " <option value='' disabled selected>Selecione</option> ";

			$qry = $sQRY->fetchAll(PDO::FETCH_ASSOC);
			if (count($qry) > 0) {
				foreach ($qry as $ln) {
					$select .= " <option value='".$ln["codigo_uf"]. "' >" . $ln["uf"] . "</option> ";
				}
			}
			return $select;
		}
		
		function getCaminhao()
		{
			$conn = new ConexaoMySQL();
			$sSQL = "SELECT CAM_CODIGO, CAM_NOME FROM caminhoes";
			$sQRY = $conn->prepare($sSQL);
			$sQRY->execute();

			$select = " <option value='' disabled selected>Selecione</option> ";

			$qry = $sQRY->fetchAll(PDO::FETCH_ASSOC);
			if (count($qry) > 0) {
				foreach ($qry as $ln) {
					$select .= " <option value='".$ln["CAM_CODIGO"]. "' >" . $ln["CAM_NOME"] . "</option> ";
				}
			}
			return $select;
		}
		
		function getCidade($uf)
		{
			$conn = new ConexaoMySQL();
			$sSQL = "SELECT codigo_ibge, nome FROM municipios WHERE codigo_uf = :UF ORDER BY nome";
			$sQRY = $conn->prepare($sSQL);
			$sQRY->bindParam(':UF', $uf);
			$sQRY->execute();

			$select = " <option value='' disabled selected>Selecione</option> ";

			$qry = $sQRY->fetchAll(PDO::FETCH_ASSOC);
			if (count($qry) > 0) {
				foreach ($qry as $ln) {
					$select .= " <option value='".$ln["codigo_ibge"]. "' >" . $ln["nome"] . "</option> ";
				}
			}
			return $select;
		}
	}