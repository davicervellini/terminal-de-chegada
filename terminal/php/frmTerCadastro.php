<?php
session_start();
require_once "../../config.php";
require_once "../../conexao/ConexaoMySQL.Class.php";
require_once "../../classes/autoload.php";
$sys = new Sistema;
$ter = new Terminal;

$resp    = array();
$vCampos = array();
$vDados  = array();

foreach ($_POST as $key => $value) {
    ${$key} = ($value != "") ? $value : NULL;
}


switch ($processo) {
    case 'incluirTerminal':

        try {

            $terCodigo = $sys->gera_codigo("terminal");

            $vDados = [
                'TER_CODIGO'        => $terCodigo,
                'CNE_CODIGO'        => $codCaminhoneiro,
                'CAM_CODIGO'        => $caminhao,
                'TER_CARREGADO'     => $carga,
                'TER_ORIGEM'        => $cidadeOrigem,
                'TER_DESTINO'       => $cidadeDestino,
                'TER_DESTINO'       => $cidadeDestino,
                'TER_DATA'          => date('Y-m-d'),
                'TER_HORA'          => date('H:i:s')
            ];

            $res = $sys->getInsert("terminal", $vDados);

            if ($res != "") {
                $resp['error'] = $res;
            } else {
                $resp['message'] = 'Terminal cadastrado com sucesso.';
            }

            print json_encode($resp);
        } catch (Exception $e) {
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }
        break;

    case 'corrigirTerminal':

        try {

            $vDados = [
                'CNE_CODIGO'        => $codCaminhoneiro,
                'CAM_CODIGO'        => $caminhao,
                'TER_CARREGADO'     => $carga,
                'TER_ORIGEM'        => $cidadeOrigem,
                'TER_DESTINO'       => $cidadeDestino,
                'TER_DESTINO'       => $cidadeDestino
            ];
            $res = $sys->getUpdate("terminal", "RECNO = " . $codTerminal, $vDados);


            if ($res != "") {
                $resp['error'] = $res;
            } else {
                $resp['message']   = 'Terminal corrigido com sucesso.';
            }

            print json_encode($resp);
        } catch (Exception $e) {
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }
        break;

    case 'excluirTerminal':

        try {

            $res = $sys->getDelete("terminal", "RECNO = " . $codTerminal);
            if ($res != "") {
                $resp['error'] = $res;
            } else {
                $resp['message']   = 'Terminal excluído com sucesso.';
            }

            print json_encode($resp);
        } catch (Exception $e) {
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }
        break;

    case 'listarTerminal':

        try {
            $qry = $ter->list();
            $cont = 0;
            $selected = 0;
            $ativo = "";
            if (count($qry) > 0) {
                $resp['grid'] = "
                   <thead>
                        <tr align=\"center\">
                          <th width=\"50%\">Nome</th>
                          <th width=\"50%\">Destino</th>
                        </tr>
                  </thead>
                  <tbody>";
                foreach ($qry as $ln) {
                    $cont++;
                    if ($ln["RECNO"] == $recno) {
                        $ativo = "tr-active";
                        $selected = $cont;
                    } else {
                        $ativo = "";
                    }
                    $resp['grid'] .= "
                        <tr class='trHighlight " . $ativo . "' onClick=\"selecionarRegistro('" . $ln["RECNO"] . "',this)\">
                            <td>" . $ln["CNE_NOME"] . "</td>
                            <td>" . $ln["nome"] . "</td>
                        </tr>";
                }

                $resp['grid'] .= " </tbody>";
                $resp['pagina'] = (10 * floor(($selected / 10)));
            } else {
                $resp['grid'] = "<thead>
                        <th>&nbsp;</th>
                       </thead>
                       <tbody>
                        <tr><td>Nenhum registro encontrado</td></tr>
                       </tbody>";
            }
            print json_encode($resp);
        } catch (Exception $e) {
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }

        break;

    case 'preencheCampos':

        try {
            $ter = new Terminal($id);
            $resp["Cpf"]                = $ter->getCpf();
            $resp["CamCodigo"]          = $ter->getCamCodigo();
            $resp["Carga"]              = $ter->getCarga();
            $resp["CidadeOrigen"]       = $ter->getCidadeOrigen();
            $resp["CidadeDestino"]      = $ter->getCidadeDestino();
            $resp["UfOrigen"]           = $ter->getCodigoUf($ter->getCidadeOrigen());
            $resp["UfDestino"]          = $ter->getCodigoUf($ter->getCidadeDestino());

            print json_encode($resp);
        } catch (Exception $e) {
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }

        break;
    
    case 'pesquisaCaminhoneiro':

        try {
            $cne = new Caminhoneiros();
            $cne->getNomePorCfp($sys->limpaVars($cpf));

            $resp["Nome"]       = $cne->getNome();
            $resp["Codigo"]     = $cne->getCodigo();

            print json_encode($resp);
        } catch (Exception $e) {
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }

        break;
    
    case 'getCidade':

        try {

            $resp["cidades"] = $sys->getCidade($uf);

            print json_encode($resp);
        } catch (Exception $e) {
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }

        break;
}
