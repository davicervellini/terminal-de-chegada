<?php

	require_once "config.php";
?>
<div class="row  background" style='height:150px;'>
	<div class="container" style='margin-top:0; padding-top:0;'>
		<p style='font-size:36px;margin:0;padding-top:80px;color:#FFF'>404!</p>
	</div>
</div>
<div class="row">
	<div class="container">
		<p>Página não encontrada!</p>
		<p>A URL requisitada <b><?php print $_SERVER["REQUEST_URI"]; ?></b> não foi encontrada nesse servidor.</p>
		<p><a href="javascript: history.back()">Voltar para a página anterior</a></p>
	</div>
</div>
