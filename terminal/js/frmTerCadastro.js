var liberarInclusao = false,
    liberarCorrecao = false,
    isInclusao = 0;

function habilitarCampos(sBool) {
    $("input[type='text']:not(#nome),input[type='hidden']").prop('disabled', sBool == true ? false : true);
    $("#ufOrigem,#cidadeOrigem,#ufDestino,#cidadeDestino,#carga,#caminhao").prop('disabled', sBool == true ? false : true);
    $('#ufOrigem,#cidadeOrigem,#ufDestino,#cidadeDestino,#carga,#caminhao').formSelect();
}

function _redirect() {
    Route.href('./terminal/cadastro/');
}

function limparCampos() {
    $("input[type='text'],#ufOrigem,#cidadeOrigem,#ufDestino,#cidadeDestino,#carga,#caminhao").val('');
}

function selecionarRegistro(cod, elm) {
    setDadosForm(cod);
    Route.pushState('?codigo=' + cod);
    $("tr").removeClass("tr-active");
    $(elm).addClass("tr-active");
}

var getDadosForm = function () {
    return {
        cidadeOrigem:       $("#cidadeOrigem").val(),
        cidadeDestino:      $("#cidadeDestino").val(),
        carga:              $("#carga").val(),
        caminhao:           $("#caminhao").val(),
        codCaminhoneiro:    $("#codCaminhoneiro").val(),
        codTerminal:        $("#codTerminal").val()
    }
}

function setDadosForm(id) {
    $.ajax({
        type: "POST",
        url: "terminal/php/frmTerCadastro.php",
        datatype: "json",
        data: {
            processo: 'preencheCampos',
            id: id
        },
        beforeSend: function () {
            $("#message").loadGif('Carregando dados, por favor aguarde...');
        },
        success: function (data) {
            data = $.parseJSON(data);
            if (!data.hasOwnProperty('error')) {
                $("#cpf").val(data.Cpf),
                $("#carga").val(data.Carga),
                $("#caminhao").val(data.CamCodigo),
                $("#ufOrigem").val(data.UfOrigen),
                $("#ufDestino").val(data.UfDestino),
                $("#codTerminal").val(id),
                $('html,body').animate({ scrollTop: 0 }, 'slow');
                pesquisaCaminhoneiro(data.Cpf);
                getCidade(data.UfOrigen, 'ufOrigem', data.CidadeOrigen);
                getCidade(data.UfDestino, 'ufDestino', data.CidadeDestino);
                $('#ufOrigem,#ufDestino,#carga,#caminhao').formSelect();
            } else {
                alert(data.error);
            }
            $("#message").html('');
        },
        error: function (data) {
            alert('Falha ao carregar os dados, recarregue a página e tente novamente.');
            console.log(data);
        }
    });
}

function habilitarControles(nameButton = null) {
    $('.btnControl').addClass('disabled');
    if (nameButton !== null) {
        for (var i = 0; i < nameButton.length; i++) {
            $(nameButton[i]).removeClass('disabled');
        }
    } else {
        $('.btnControl').removeClass('disabled');
    }
}

function incluirTerminal() {
    if (!liberarInclusao) {
        isInclusao = 1;
        limparCampos();
        habilitarCampos(true);
        habilitarControles(['#btnIncluir']);
        $("#btnIncluir").html('Salvar');
        $("#nome").focus();
        liberarInclusao = true;
    } else {

        if (confirm('Deseja salvar os dados?')) {
            var Dados = getDadosForm();
            Dados.processo = 'incluirTerminal';

            if (Dados.nome == "") {
                $("#message").showAlert('Preencha o nome antes de continuar');
                return;
            }

            $.ajax({
                type: "POST",
                url: "terminal/php/frmTerCadastro.php",
                data: Dados,
                datatype: 'json',
                beforeSend: function () {
                    $("#message").loadGif('Incluindo o Terminal, por favor aguarde...');
                },
                success: function (data) {
                    data = $.parseJSON(data);
                    if (!data.hasOwnProperty('error')) {
                        alert(data.message);
                        habilitarCampos(false);
                        habilitarControles();
                        $("#btnIncluir").html('Incluir');
                        liberarInclusao = false;
                        listarTerminal();
                        limparCampos();
                        $("#message").html('');
                    } else {
                        alert(data.error);
                    }
                },
                error: function (data) {
                    console.log(data.responseText);
                }
            });

        } else {
            habilitarCampos(false);
            habilitarControles();
            $("#btnIncluir").html('Incluir');
            liberarInclusao = false;
        }
    }
}


function corrigirTerminal() {
    var codTerminal = $("#codTerminal").val();
    if (!liberarCorrecao) {
        if (codTerminal != "") {
            habilitarControles(['#btnCorrigir']);
            habilitarCampos(true);
            $("#btnCorrigir").html('Salvar');
            $("#nome").focus();
            liberarCorrecao = true;
        } else {
            alert('Por favor, selecione um registro antes de continuar.');
            return false;
        }
    } else {

        if (confirm('Deseja salvar os dados?')) {
            var Dados = getDadosForm();
            Dados.processo = 'corrigirTerminal';

            if (Dados.nome == "") {
                $("#message").showAlert('Preencha o nome antes de continuar');
                return;
            }

            $.ajax({
                type: "POST",
                url: "terminal/php/frmTerCadastro.php",
                data: Dados,
                datatype: 'json',
                beforeSend: function () {
                    $("#message").loadGif('Corrigindo o Terminal, por favor aguarde...');
                },
                success: function (data) {
                    data = $.parseJSON(data);
                    if (!data.hasOwnProperty('error')) {
                        alert(data.message);
                        habilitarCampos(false);
                        habilitarControles();
                        $("#btnCorrigir").html('Corrigir');
                        liberarCorrecao = false;
                        listarTerminal();
                        limparCampos();
                        $("#message").html('');
                        $("tr").removeClass("tr-active");
                    } else {
                        alert(data.error);
                    }
                },
                error: function (data) {
                    console.log(data.responseText);
                }
            });


        } else {

            habilitarControles();
            habilitarCampos(false);
            $("#btnCorrigir").html('Corrigir');
            $("#data").focus();
            liberarCorrecao = false;
        }
    }
}

function excluirTerminal() {
    var codTerminal = $("#codTerminal").val();
    if (codTerminal != "") {
        if (confirm('Deseja excluir esse registro?')) {
            var Dados = getDadosForm();
            Dados.processo = 'excluirTerminal';

            $.ajax({
                type: "POST",
                url: "terminal/php/frmTerCadastro.php",
                data: Dados,
                datatype: 'json',
                beforeSend: function () {
                    $("#message").loadGif('Excluindo o Terminal, por favor aguarde...');
                },
                success: function (data) {
                    data = $.parseJSON(data);
                    if (!data.hasOwnProperty('error')) {
                        alert(data.message);
                        _redirect();
                    } else {
                        alert(data.error);
                    }
                },
                error: function (data) {
                    console.log(data.responseText);
                }
            });
        }

    } else {
        alert('Por favor, selecione um registro antes de continuar.');
    }
}

function listarTerminal() {

    $.ajax({
        type: "POST",
        url: "terminal/php/frmTerCadastro.php",
        data: {
            processo: 'listarTerminal',
            recno: $('#codTerminal').val()
        },
        datatype: 'json',
        beforeSend: function () {
            $("#gridTerminal").loadGif('Carregando resultados, por favor aguarde.');
        },
        success: function (data) {
            data = $.parseJSON(data);
            if (!data.hasOwnProperty('error')) {
                if ($.fn.DataTable.isDataTable("#gridTerminal")) {
                    $('#gridTerminal').DataTable().clear().destroy();
                }
                gridTerminal = $("#gridTerminal").html(data.grid).DataTable({ "iDisplayStart": data.pagina, aaSorting: [] });
            } else {
                alert(data.error);
            }
        }
    });

}

function pesquisaCaminhoneiro(cpf) {
    $.ajax({
        type: "POST",
        url: "terminal/php/frmTerCadastro.php",
        data: {
            processo: 'pesquisaCaminhoneiro',
            cpf: cpf
        },
        datatype: 'json',
        beforeSend: function () {
            $("#message").loadGif('Pesquisando caminhoneiro, por favor aguarde.');
        },
        success: function (data) {
            data = $.parseJSON(data);
            $('#nome').val(data.Nome);
            $('#codCaminhoneiro').val(data.Codigo);
            $("#message").html('');
            
        }
    });
}

function getCidade(uf, id, ibge = '') {
    $.ajax({
        type: "POST",
        url: "terminal/php/frmTerCadastro.php",
        data: {
            processo: 'getCidade',
            uf: uf
        },
        datatype: 'json',
        beforeSend: function () {
            $("#message").loadGif('Pesquisando cidades, por favor aguarde.');
        },
        success: function (data) {
            data = $.parseJSON(data);
            if (id == "ufOrigem"){
                $("#cidadeOrigem").html(data.cidades);
                if (ibge != ''){
                    $('#cidadeOrigem').val(ibge);
                }
                $('#cidadeOrigem').formSelect();
            }else{
                $("#cidadeDestino").html(data.cidades);
                if (ibge != '') {
                    $('#cidadeDestino').val(ibge);
                }
                $('#cidadeDestino').formSelect();
            }
            $("#message").html('');

        }
    });
}

$(document).ready(function () {
    habilitarCampos(false);
    listarTerminal();
});