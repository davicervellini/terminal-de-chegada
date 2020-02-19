<?php
session_start();
require_once "../../config.php";
require_once "../../conexao/ConexaoMySQL.Class.php";
require_once "../../classes/autoload.php";
$sys = new Sistema;
$cam = new Caminhao;

$resp    = array();
$vCampos = array();
$vDados  = array();

foreach ($_POST as $key => $value) {
    ${$key} = ($value != "") ? $value : NULL;
}


switch ($processo) {
    case 'incluirCaminhao':
        
        try{

            $camCodigo = $sys->gera_codigo("caminhoes");

            $vDados = [
                'CAM_CODIGO'        => $camCodigo,
                'CAM_NOME'          => $nome
            ];

            $res = $sys->getInsert("caminhoes",$vDados);

            if($res != ""){
              $resp['error'] = $res;
            }else{
              $resp['message'] = 'Caminhao cadastrado com sucesso.';
            }
                        
            print json_encode($resp);

        }catch(Exception $e){
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }
    break;

    case 'corrigirCaminhao':
        
        try{

            $vDados = [
                'CAM_NOME'          => $nome
            ];
            $res = $sys->getUpdate("caminhoes", "RECNO = " . $codCaminhao, $vDados);
            
            
            if($res != ""){
                $resp['error'] = $res;
            }else{
                $resp['message']   = 'Caminhao corrigido com sucesso.';
            }

            print json_encode($resp);

        }catch(Exception $e){
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }
    break;

    case 'excluirCaminhao':
        
        try{

            $res = $sys->getDelete("caminhoes", "RECNO = ".$codCaminhao);
            if($res != ""){
                $resp['error'] = $res;
            }else{
                $resp['message']   = 'Caminhao excluído com sucesso.';
            }
            
            print json_encode($resp);

        }catch(Exception $e){
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }
    break;

    case 'listarCaminhaos':

        try{            
            $qry = $cam->list();
            $cont = 0;
            $selected = 0;
            $ativo = "";
            if(count($qry) > 0){
                $resp['grid'] = "
                   <thead>
                        <tr align=\"center\">
                          <th width=\"25%\">Codigo</th>
                          <th width=\"75%\">Nome</th>
                        </tr>
                  </thead>
                  <tbody>";
                foreach ($qry as $ln) {
                    $cont++;
                    if($ln["RECNO"] == $recno){
                        $ativo = "tr-active";
                        $selected = $cont;
                    }else{
                        $ativo = "";
                    }
                    $resp['grid'] .= "
                        <tr class='trHighlight ".$ativo. "' onClick=\"selecionarRegistro('".$ln["RECNO"]."',this)\">
                            <td>".$ln["CAM_CODIGO"]."</td>
                            <td>".$ln["CAM_NOME"]."</td>
                        </tr>";
                }

                $resp['grid'] .= " </tbody>";
                $resp['pagina'] = (10 * floor(($selected/10)));
            }else{
                $resp['grid'] = "<thead>
                        <th>&nbsp;</th>
                       </thead>
                       <tbody>
                        <tr><td>Nenhum registro encontrado</td></tr>
                       </tbody>";
            }
            print json_encode($resp);
        }catch(Exception $e){
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }

    break;

    case 'preencheCampos':

        try{
            $cam = new Caminhao($id);
            $resp["Nome"]       = $cam->getNome();

            print json_encode($resp);           
        }catch(Exception $e){
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }

    break;
}
