<?php
session_start();
require_once "config.php";
require_once "conexao/ConexaoMySQL.Class.php";
require_once "classes/autoload.php";

$menu = new Menu;
$sys  = new Sistema;

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="content-language" content="pt-br" />
	<title>Sistema</title>
	<base href="<?php print INCLUDE_PATH; ?>/">
	<link rel="stylesheet" href="http://fonts.googleapis.com/icon?family=Material+Icons">
	<link type="text/css" rel="stylesheet" href="css/materialize-customized.css" media="screen,projection" />
	<link type="text/css" rel="stylesheet" href="css/style.css" media="screen,projection" />
	<link type="text/css" rel="stylesheet" href="css/font-awesome.min.css">
	<link type="text/css" rel="stylesheet" href="css/dataTables.material.css" />
	<link type="text/css" rel="stylesheet" href="css/buttons.dataTables.min.css" />
	<link type="text/css" rel="stylesheet" href="css/datepicker.css" />
	<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="js/buttons.print.min.js"></script>
	<script type="text/javascript" src="js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/jquery.mask.js"></script>
	<script type="text/javascript" src="js/jquery-maskmoney-v3.0.2.js"></script>
	<script type="text/javascript" src="js/materialize.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="js/route.js"></script>
	<style type="text/css">
		body {
			background: url("img/bkg-login.jpg");
		}

		.btntour {
			padding: 4px 6px;
		}

		.arca-nav {
			overflow-y: hidden;
			overflow-x: hidden;
		}

		.arca-nav:hover {
			overflow-y: auto;
		}
	</style>

</head>
<?php if (DEBUG) : ?>
	<div class="desenvolvimento">Ambiente de Desenvolvimento</div>
<?php endif; ?>

<body class="grey lighten-2">
	<aside>
		<ul id="slide-out" class="side-nav fixed arca-nav" style="height:100%">
			<li class='logo-li'>
				<div class="userView">
					<div style='text-align: center;'>
					</div>
					<div class="background">
						<img src="img/bkg-texture.jpg" style='width:100%'>
					</div>

				</div>
			</li>

			<?php
			require_once "classes/Menu.Class.php";


			$menu = new Menu('Cadastros', 'library_add');
				$menu->append('Tipos de caminhões',           './cadastros/tipo-caminhao/');
				$menu->append('Caminhoneiros',           	  './cadastros/caminhoneiros/');
			$menu->render();

			$menu = new Menu('Terminal', 'assignment_ind');
				$menu->append('Cadastro',           './terminal/cadastro/');
			$menu->render();

			?>
		</ul>
	</aside>

	<section class="main-content" style='padding:15px;padding-top:40px; z-index: 1'>
		<?php
		$routerContent = new AltoRouter();
		$routerContent->setBasePath(BASE_ROUTE);

		$routerContent->addRoutes(array(

			array('GET', '/cadastros/[*]',  							  '/cadastros/subrotas.php', ''),
			array('GET', '/terminal/[*]',  								  '/terminal/subrotas.php', ''),

		));

		$matchContent = $routerContent->match();
		if (is_array($matchContent)) {
			require __DIR__ . $matchContent['target'];
		} else {
			?>
			<div class="row">
				<div class="col l8 offset-l2 center-align white z-depth-1">
					<p style="font-weight: bold;font-size:18px">Bem vindo.</p>
				</div>
			</div>
		<?php
		}
		?>

	</section>

	<script>
		$(document).ready(function($) {
			$(".tooltipped").each(function(index) {
				$(this).tooltip({
					tooltip: $(this).text().replace("keyboard_arrow_right", ""),
					position: "right",
					delay: 100
				});
			});
			$(".collapsible-body li").children().each(function() {
				$(this).width() > $(".collapsible-body li").width() && $(this).attr("title", $(this).text().replace("keyboard_arrow_right", ""));
			});
			$(".dropdown-button").dropdown();
			$('.modal').modal();
			$('.collapsible').collapsible();

			$.extend(true, $.fn.dataTable.defaults, {
				"language": {
					"url": "lib/datatable.ptbr.json"
				}
			});
			$(".dateFormat").datepicker().mask('00/00/0000');
			$(".horaFormat").mask('00:00');
			$(".number").mask("#");
			$(".cpf").mask("999.999.999-99");

			$(".cpfcnpj").keydown(function() {
				try {
					$(".cpfcnpj").unmask();
				} catch (e) {}

				var tamanho = $(".cpfcnpj").val().length;

				if (tamanho < 11) {
					$(".cpfcnpj").mask("999.999.999-99");
				} else {
					$(".cpfcnpj").mask("99.999.999/9999-99");
				}

				// ajustando foco
				var elem = this;
				setTimeout(function() {
					// mudo a posição do seletor
					elem.selectionStart = elem.selectionEnd = 10000;
				}, 0);
				// reaplico o valor para mudar o foco
				var currentValue = $(this).val();
				$(this).val('');
				$(this).val(currentValue);
			});

			$("input[type='text'],input[type='password']").bind('keydown', function(e) {
				if ($(this).data('next')) {
					(e.keyCode == '13') && $($(this).data('next')).focus();
				}
				if ($(this).data('mce')) {
					(e.keyCode == '13') && tinymce.execCommand('mceFocus', false, '' + $(this).data('focusTiny') + '');
				}
				if ($(this).data('pesq')) {
					(e.keyCode == '13') && $($(this).data('pesq')).click();
				}
				if ($(this).data('btn')) {
					if (e.keyCode == '13') {
						if (liberarInclusao) {
							$("#btnIncluir").click();
						} else if (liberarCorrecao) {
							$("#btnCorrigir").click();
						}
					}
				}
			});

			$("select").bind('blur keydown', function(e) {
				((e.type == 'blur' || e.keyCode == '13') && $(this).val() != "") && nextSelect($(this).data('role'), e);
			});
		});

		function nextSelect(_nextField, el) {
			var nField = $("#" + _nextField);
			if (nField.val() != "" && nField.is('select'))
				nextSelect(nField.data("role"));
			else
				nField.focus();
		}

		$.fn.showAlert = function(message, type = 'red') {
			$(this).html("<div class=\"card " + type + " darken-1 show \" >\
		                        <div class=\"card-content white-text\" >" + message + "</div></div>");
		}
		$.fn.showResponse = function(message, typeResponse = 0) {
			type = (typeResponse == 1) ? "green" : "red";
			$(this).html("<div class=\"card " + type + " darken-1 show no-bottom\" >\
		                        <div class=\"card-content white-text\" style='height:30px;padding-top:0px;' >" + message + "</div></div>");
		}
		$.fn.loadGif = function(sMessage) {

			sMessage = (sMessage !== "") ? "<div style='padding-left:10px;line-height: 32px; height:32px'>" + sMessage + "</div> " : "";
			$(this).html("<div class=\"row\">\
			<div class=\"col l12 center-align\">\
				<div class=\"preloader-wrapper big active\">\
					<div class=\"spinner-layer spinner-green-only\">\
						<div class=\"circle-clipper left\">\
						<div class=\"circle\"></div>\
						</div><div class=\"gap-patch\">\
						<div class=\"circle\"></div>\
						</div><div class=\"circle-clipper right\">\
						<div class=\"circle\"></div>\
						</div>\
					</div>\
				</div>" + sMessage + "\
			</div></div>");

		}


		$.fn.loadProgress = function() {
			$(this).html("<div class=\"progress\">\
						<div class=\"indeterminate\"></div>\
					  </div>");
		}

		var SPMaskBehavior = function(val) {
				return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
			},
			spOptions = {
				onKeyPress: function(val, e, field, options) {
					field.mask(SPMaskBehavior.apply({}, arguments), options);
				}
			};

		$.datepicker.setDefaults({
			dateFormat: 'dd/mm/yy',
			dayNames: ['Domingo', 'Segunda', 'Ter&ccedil;a', 'Quarta', 'Quinta', 'Sexta', 'S&aacute;bado', 'Domingo'],
			dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
			dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'S&aacute;b', 'Dom'],
			monthNames: ['Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
			monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez']
		});


		function logout() {
			if (confirm("Deseja realmente sair do sistema?")) {
				$.ajax({
					type: "POST",
					url: "php/frmLogin.php",
					data: "processo=logout&usuLogin=<?php print $_SESSION["usuario"]["usuLogin"]; ?>",
					success: function(resposta) {
						window.location.reload();
					}
				});
			}
		}
	</script>
</body>

</html>