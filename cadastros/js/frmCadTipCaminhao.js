var liberarInclusao = false,
    liberarCorrecao = false,
    isInclusao = 0;

function habilitarCampos(sBool) {
    $("input[type='text']:not(#atividade),input[type='hidden']").prop('disabled', sBool == true ? false : true);
}

function _redirect() {
    Route.href('./cadastros/tipo-caminhao/');
}

function limparCampos() {
    $("input[type='text']").val('');
}

function selecionarRegistro(cod, elm) {
    setDadosForm(cod);
    Route.pushState('?codigo=' + cod);
    $("tr").removeClass("tr-active");
    $(elm).addClass("tr-active");
}

var getDadosForm = function () {
    return {
        nome: $("#nome").val(),
        codCaminhao: $("#codCaminhao").val()
    }
}

function setDadosForm(id) {
    $.ajax({
        type: "POST",
        url: "cadastros/php/frmCadTipCaminhao.php",
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
                $("#nome").val(data.Nome),
                $("#codCaminhao").val(id)
                $('html,body').animate({ scrollTop: 0 }, 'slow');
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

function incluirCaminhao() {
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
            Dados.processo = 'incluirCaminhao';

            if (Dados.nome == "") {
                $("#message").showAlert('Preencha o nome antes de continuar');
                return;
            }

            $.ajax({
                type: "POST",
                url: "cadastros/php/frmCadTipCaminhao.php",
                data: Dados,
                datatype: 'json',
                beforeSend: function () {
                    $("#message").loadGif('Incluindo o Caminhao, por favor aguarde...');
                },
                success: function (data) {
                    data = $.parseJSON(data);
                    if (!data.hasOwnProperty('error')) {
                        alert(data.message);
                        habilitarCampos(false);
                        habilitarControles();
                        $("#btnIncluir").html('Incluir');
                        liberarInclusao = false;
                        listarCaminhoneiros();
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


function corrigirCaminhao() {
    var codCaminhao = $("#codCaminhao").val();
    if (!liberarCorrecao) {
        if (codCaminhao != "") {
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
            Dados.processo = 'corrigirCaminhao';

            if (Dados.nome == "") {
                $("#message").showAlert('Preencha o nome antes de continuar');
                return;
            }

            $.ajax({
                type: "POST",
                url: "cadastros/php/frmCadTipCaminhao.php",
                data: Dados,
                datatype: 'json',
                beforeSend: function () {
                    $("#message").loadGif('Corrigindo o Caminhao, por favor aguarde...');
                },
                success: function (data) {
                    data = $.parseJSON(data);
                    if (!data.hasOwnProperty('error')) {
                        alert(data.message);
                        habilitarCampos(false);
                        habilitarControles();
                        $("#btnCorrigir").html('Corrigir');
                        liberarCorrecao = false;
                        listarCaminhoneiros();
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

function excluirCaminhao() {
    var codCaminhao = $("#codCaminhao").val();
    if (codCaminhao != "") {
        if (confirm('Deseja excluir esse registro?')) {
            var Dados = getDadosForm();
            Dados.processo = 'excluirCaminhao';

            $.ajax({
                type: "POST",
                url: "cadastros/php/frmCadTipCaminhao.php",
                data: Dados,
                datatype: 'json',
                beforeSend: function () {
                    $("#message").loadGif('Excluindo o Caminhao, por favor aguarde...');
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

function listarCaminhaos() {

    $.ajax({
        type: "POST",
        url: "cadastros/php/frmCadTipCaminhao.php",
        data: {
            processo: 'listarCaminhaos',
            recno: $('#codCaminhao').val()
        },
        datatype: 'json',
        beforeSend: function () {
            $("#gridCaminhao").loadGif('Carregando resultados, por favor aguarde.');
        },
        success: function (data) {
            data = $.parseJSON(data);
            if (!data.hasOwnProperty('error')) {
                if ($.fn.DataTable.isDataTable("#gridCaminhao")) {
                    $('#gridCaminhao').DataTable().clear().destroy();
                }
                gridCaminhao = $("#gridCaminhao").html(data.grid).DataTable({ "iDisplayStart": data.pagina, aaSorting: [] });
            } else {
                alert(data.error);
            }
        }
    });

}

$(document).ready(function () {
    habilitarCampos(false);
    listarCaminhaos();
});