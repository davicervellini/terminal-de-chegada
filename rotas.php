<?php
	require_once "classes/AltoRouter.php";
	require_once "config.php";
	$router = new AltoRouter();

	$router->setBasePath(BASE_ROUTE);

	$router->map('GET','/',         function(){ require __DIR__ . '/home.php';}, 'home');
	$router->map('GET','/home',     function(){ require __DIR__ . '/home.php';});
	$router->map('GET','/home/',    function(){ require __DIR__ . '/home.php';});
	
	$router->map('GET','/[*:url]',  function($url){ 
		require __DIR__ . '/home.php';});
			
	$match = $router->match();
?>
