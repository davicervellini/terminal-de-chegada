<?php

$routerContent = new AltoRouter();
$routerContent->setBasePath( BASE_ROUTE ."/cadastros" );

$routerContent->addRoutes(array(

	array('GET','/tipo-caminhao/',    '/cadTipCaminhao.php', 'cadTipCaminhao'),
	array('GET','/tipo-caminhao',     '/cadTipCaminhao.php', ''),
	array('GET','/tipo-caminhao/[*]', '/cadTipCaminhao.php', ''),
	
	array('GET','/caminhoneiros/',    '/cadCaminhoneiros.php', 'cadCaminhoneiros'),
	array('GET','/caminhoneiros',     '/cadCaminhoneiros.php', ''),
	array('GET','/caminhoneiros/[*]', '/cadCaminhoneiros.php', ''),

));	

$matchContent = $routerContent->match();

if( is_array($matchContent)  ) {
	require __DIR__. $matchContent['target'];

}else{
	require "bloqueio.php";
}