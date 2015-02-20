<?php
session_start();
	
	$GLOBALS['config'] = array(
		'mysql' => array(
			'host' => 'localhost',
			'user' => 'root',
			'password' => '1234567',
			'dbName' => 'asteras_vouliagmenis'
		)
	);

	spl_autoload_register(function($class) { 
		require_once 'controller/' . $class . '.php';
	});
		
	require_once 'functions/sanitize.php';
	require_once 'functions/issubset.php';
 	// require_once 'functions/getFormFields.php';
	require_once 'model/Model.php';
	
	$db = Database::getInstance();
?>
