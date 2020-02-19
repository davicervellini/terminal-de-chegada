<?php

	if (phpversion() < 7.0) {
    	echo "<p>Ops, sua versão do PHP não é compatível com esse sistema. Por favor, utilize a versão 7.0 ou maior do PHP.</p>";
    	exit;
	}
	
	define( "_BASE_PATH_", dirname(__DIR__) . DIRECTORY_SEPARATOR);
	define( "_LOG_PATH_", _BASE_PATH_ . 'logs'. DIRECTORY_SEPARATOR);
	
	define( "REQUIRE_PATH" , str_replace("\\", "/", __DIR__));

	define( "PORT", ":80");

	define( "INCLUDE_PATH", "http://". $_SERVER["SERVER_NAME"] . PORT . "/terminal-de-chegada");

	define( "BASE_ROUTE" , "/terminal-de-chegada");

	define( "DEBUG", true);
	if(!DEBUG){
		ini_set("display_errors", 0);
		ini_set("log_errors", true);
		ini_set("error_log", "logs/error.log");
	}
	
?>