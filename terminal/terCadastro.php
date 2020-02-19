<?php
require_once "config.php";
require_once "conexao/ConexaoMySQL.Class.php";
require_once "classes/autoload.php";
$sys = new Sistema;
$selectDados    = $sys->getUfs();
$selectCaminhao = $sys->getCaminhao();

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
    <div class="row" id="Terminal">
        <form id="frmCadTerminal" class="col l12 white z-depth-1" style='padding-top:10px'>
            <div class="row no-bottom center-align">
                <h5>Cadastro de Terminal</h5>
            </div>
            <div class="row no-bottom">
                <div class="input-field col l3 offset-l1">
                    <input placeholder="" class="cpf" id="cpf" type="text" onchange="pesquisaCaminhoneiro(this.value)" data-next="#btnIncluir">
                    <label for="cpf" id="lblCpf">CPF</span></label>
                </div>
                <div class="input-field col l7 ">
                    <input placeholder="" id="nome" type="text" disabled>
                    <label for="nome" id="lblNome">Nome</span></label>
                </div>
            </div>
            <div class="row no-bottom">
                <div class="input-field col l2 offset-l1">
                    <select id="ufOrigem" onchange="getCidade(this.value,this.id)">
                        <?php print $selectDados; ?>
                    </select>
                    <label for="ufOrigem" id="lblUfOrigem">UF Origem</span></label>
                </div>
                <div class="input-field col l3">
                    <select id="cidadeOrigem">
                        <option value='' disabled selected>Selecione Uf</option>
                    </select>
                    <label for="cidadeOrigem" id="lblCidadeOrigem">Cidade Origem</span></label>
                </div>
                <div class="input-field col l2">
                    <select id="ufDestino" onchange="getCidade(this.value,this.id)">
                        <?php print $selectDados; ?>
                    </select>
                    <label for="ufDestino" id="lblUfDestino">UF Destino</span></label>
                </div>
                <div class="input-field col l3">
                    <select id="cidadeDestino">
                        <option value='' disabled selected>Selecione Uf</option>
                    </select>
                    <label for="cidadeDestino" id="lblCidadeDestino">Cidade Destino</span></label>
                </div>
            </div>
            <div class="row no-bottom">
                <div class="input-field col l2 offset-l1">
                    <select id="carga">
                        <option value='' disabled selected>Selecione</option>
                        <option value="S">Sim</option>
                        <option value="N">Não</option>
                    </select>
                    <label for="carga" id="lblCarga">Carga</span></label>
                </div>
                <div class="input-field col l2">
                    <select id="caminhao">
                        <?php print $selectCaminhao; ?>
                    </select>
                    <label for="caminhao" id="lblCaminhao">Caminhão</span></label>
                </div>
            </div>
            <div class="row">
                <div class="col l10 offset-l1" id="message"></div>
            </div>
            <div class="row">
                <div class="col l10 offset-l1 center-align">
                    <a id="btnIncluir" name="btnIncluir" class="waves-effect waves-light btn background btnControl" onClick='incluirTerminal()'> Incluir</a>
                    <a id="btnCorrigir" name="btnCorrigir" class="waves-effect waves-light btn background btnControl" onClick='corrigirTerminal()' style="margin-left:5px">Corrigir</a>
                    <a id="btnCorrigir" name="btnCorrigir" class="waves-effect waves-light btn background btnControl" onClick='excluirTerminal()' style="margin-left:5px">Excluir</a>
                    <a id="btnLimpar" name="btnLimpar" class="waves-effect waves-light btn background btnControl" onClick="_redirect()" style="margin-left:5px">Limpar</a>
                </div>
            </div>
            <div class="row" style=' min-height:250px;margin-bottom:50px'>
                <div class="col l10 offset-l1">
                    <table id="gridTerminal" class='bordered striped display compact dataTablePrint highlight pointer'></table>
                </div>
            </div>
            <input name='codTerminal' id='codTerminal' type='hidden' value=""></input>
            <input name='codCaminhoneiro' id='codCaminhoneiro' type='hidden' value=""></input>
        </form>
    </div>
</div>
<script src="terminal/js/frmTerCadastro.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    document.title = "Cadastro de Terminal";
</script>