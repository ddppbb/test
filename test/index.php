<?php

error_reporting(E_ALL);
ini_set("display_errors", 1); 

function autoload($className)
{
	$fileName = __DIR__ . DIRECTORY_SEPARATOR 
		. str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

	if (file_exists($fileName)) {

		require_once $fileName;
	}
}

spl_autoload_register('autoload');

if (PHP_SAPI === 'cli') {
	$cli = new \Cli\Cli($argv);	
}