<?php
session_start();
require_once "../../config.php";
require_once "../../conexao/ConexaoMySQL.Class.php";
require_once "../../classes/autoload.php";
$sys = new Sistema;
$cne = new Caminhoneiros;

$resp    = array();
$vCampos = array();
$vDados  = array();

foreach ($_POST as $key => $value) {
    ${$key} = ($value != "") ? $value : NULL;
}


switch ($processo) {
    case 'incluirCaminhoneiros':

        try {

            $cneCodigo = $sys->gera_codigo("caminhoneiros");

            $vDados = [
                'CNE_CODIGO'        => $cneCodigo,
                'CNE_NOME'          => $nome,
                'CNE_IDADE'         => $idade,
                'CNE_SEXO'          => $sexo,
                'CNE_TPCNH'         => $tpCnh,
                'CNE_CPF'           => $sys->limpaVars($cpf)
            ];

            $res = $sys->getInsert("caminhoneiros", $vDados);

            if ($res != "") {
                $resp['error'] = $res;
            } else {
                $resp['message'] = 'Caminhoneiros cadastrado com sucesso.';
            }

            print json_encode($resp);
        } catch (Exception $e) {
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }
        break;

    case 'corrigirCaminhoneiros':

        try {

            $vDados = [
                'CNE_NOME'          => $nome,
                'CNE_IDADE'         => $idade,
                'CNE_SEXO'          => $sexo,
                'CNE_TPCNH'         => $tpCnh,
                'CNE_CPF'           => $sys->limpaVars($cpf)
            ];
            $res = $sys->getUpdate("caminhoneiros", "RECNO = " . $codCaminhoneiros, $vDados);


            if ($res != "") {
                $resp['error'] = $res;
            } else {
                $resp['message']   = 'Caminhoneiros corrigido com sucesso.';
            }

            print json_encode($resp);
        } catch (Exception $e) {
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }
        break;

    case 'excluirCaminhoneiros':

        try {

            $res = $sys->getDelete("caminhoneiros", "RECNO = " . $codCaminhoneiros);
            if ($res != "") {
                $resp['error'] = $res;
            } else {
                $resp['message']   = 'Caminhoneiros excluído com sucesso.';
            }

            print json_encode($resp);
        } catch (Exception $e) {
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }
        break;

    case 'listarCaminhoneiros':

        try {
            $qry = $cne->list();
            $cont = 0;
            $selected = 0;
            $ativo = "";
            if (count($qry) > 0) {
                $resp['grid'] = "
                   <thead>
                        <tr align=\"center\">
                          <th width=\"25%\">CPF</th>
                          <th width=\"75%\">Nome</th>
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
                            <td>" . $sys->mask($ln["CNE_CPF"]) . "</td>
                            <td>" . $ln["CNE_NOME"] . "</td>
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
            $cne = new Caminhoneiros($id);
            $resp["Nome"]       = $cne->getNome();
            $resp["Idade"]      = $cne->getIdade();
            $resp["Sexo"]       = $cne->getSexo();
            $resp["TpCnh"]      = $cne->getTpCnh();
            $resp["Cpf"]        = $sys->mask($cne->getCpf());

            print json_encode($resp);
        } catch (Exception $e) {
            $resp['error'] = $e->getMessage();
            print json_encode($resp);
        }

        break;
}
