<?php

$routerContent = new AltoRouter();
$routerContent->setBasePath( BASE_ROUTE . "/terminal" );

$routerContent->addRoutes(array(

	array('GET','/cadastro/',    '/terCadastro.php', 'terCadastro'),
	array('GET','/cadastro',     '/terCadastro.php', ''),
	array('GET','/cadastro/[*]', '/terCadastro.php', ''),

));	

$matchContent = $routerContent->match();

if( is_array($matchContent)  ) {
	require __DIR__. $matchContent['target'];

}else{
	require "bloqueio.php";
}