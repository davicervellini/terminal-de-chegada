<?php
require_once "config.php";
require_once "conexao/ConexaoMySQL.Class.php";
require_once "classes/autoload.php";

$codigo = (isset($_GET["codigo"]) && $_GET["codigo"] !== "") ? $_GET["codigo"] : "";

$nome = $idade = $sexo = $tpCnh = $cpf = $codigo = "";

if ($codigo != '') {
    $cli = new Caminhoneiros($codigo);
    $sys = new Sistema;

    $nome       = $cli->getNome();
    $idade      = $cne->getIdade();
    $sexo       = $cne->getSexo();
    $tpCnh      = $cne->getTpCnh();
    $cpf        = $sys->mask($cne->getCpf());
    $codigo     = $codigo;
}

?>
<style>
    @media print {
        table {
            border: 1px solid #666;
            font-family: "Calibri";
            width: 100%
        }

        table th {
            background-color: #e6e6e6;
            border-right: 1px solid #666;
            border-bottom: 1px solid #666;
        }

        table td {
            border-right: 1px solid #666;
            border-bottom: 1px solid #666;
            height: 25px;
            padding: 1px;
        }

        .dt-print-view h1 {
            color: #000;
            font-size: 22px;
            font-family: "Calibri";
        }
    }

    #frmCadDoadores .row {
        margin-bottom: 5px;
    }

    label {
        font-size: 15px !important;
    }

    #frmCadDoadores .select-dropdown {
        margin-bottom: 5px;
    }

    input {
        height: 35px !important;
    }

    .dropdown-content li>a,
    .dropdown-content li>span {
        font-size: 14px;
    }

    .dropdown-content li {
        min-height: 30px;
    }

    .dropdown-content li>span {
        padding: 7px 8px;
        color: #000;
    }

    .tbl-telefone td {
        padding: 0 0 0 5px;
    }

    .input-field label {
        color: #9e9e9e;
    }

    #descricao:focus {
        border-bottom: 1px solid #1976d2;
        box-shadow: 0 1px 0 0 #1976d2;
    }

    .ui-datepicker-year {
        display: none;
    }

    .caret {
        display: none
    }
</style>

<div class="container">
    <div class="row" id="Caminhoneiros">
        <form id="frmCadCaminhoneiros" class="col l12 white z-depth-1" style='padding-top:10px'>
            <div class="row no-bottom center-align">
                <h5>Cadastro de Caminhoneiros</h5>
            </div>
            <div class="row no-bottom">
                <div class="input-field col l10 offset-l1">
                    <input placeholder="" id="nome" type="text" value="<?php print $nome; ?>" data-next="#idade">
                    <label for="nome" id="lblNome">Nome</span></label>
                </div>
            </div>
            <div class="row no-bottom">
                <div class="input-field col l2 offset-l1">
                    <input placeholder="" id="idade" type="text" value="<?php print $idade; ?>" data-next="#documento">
                    <label for="idade" id="lblIdade">Idade</span></label>
                </div>
                <div class="input-field col l3 ">
                    <select id="sexo">
                        <option value="" disabled selected>Selecione</option>
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                    </select>
                    <label for="sexo" id="lblSexo">Sexo</span></label>
                </div>
                <div class="input-field col l2 ">
                    <input placeholder="" id="tpCnh" type="text" value="<?php print $tpCnh; ?>" data-next="#cpf">
                    <label for="tpCnh" id="lblTpCnh">Tipo CNH</span></label>
                </div>
                <div class="input-field col l3 ">
                    <input placeholder="" class="cpf" id="cpf" type="text" value="<?php print $cpf; ?>" data-btn="#btnIncluir">
                    <label for="cpf" id="lblCpf">CPF</span></label>
                </div>
            </div>
            <div class="row">
                <div class="col l10 offset-l1" id="message"></div>
            </div>
            <div class="row">
                <div class="col l10 offset-l1 center-align">
                    <a id="btnIncluir" name="btnIncluir" class="waves-effect waves-light btn background btnControl" onClick='incluirCaminhoneiros()'> Incluir</a>
                    <a id="btnCorrigir" name="btnCorrigir" class="waves-effect waves-light btn background btnControl" onClick='corrigirCaminhoneiros()' style="margin-left:5px">Corrigir</a>
                    <a id="btnCorrigir" name="btnCorrigir" class="waves-effect waves-light btn background btnControl" onClick='excluirCaminhoneiros()' style="margin-left:5px">Excluir</a>
                    <a id="btnLimpar" name="btnLimpar" class="waves-effect waves-light btn background btnControl" onClick="_redirect()" style="margin-left:5px">Limpar</a>
                </div>
            </div>
            <div class="row" style=' min-height:250px;margin-bottom:50px'>
                <div class="col l10 offset-l1">
                    <table id="gridCaminhoneiros" class='bordered striped display compact dataTablePrint highlight pointer'></table>
                </div>
            </div>
            <input name='codCaminhoneiros' id='codCaminhoneiros' type='hidden' value="<?php print $codigo; ?>"></input>
        </form>
    </div>
</div>
<script src="cadastros/js/frmCadCaminhoneiros.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    document.title = "Cadastro de Caminhoneiros";
</script>