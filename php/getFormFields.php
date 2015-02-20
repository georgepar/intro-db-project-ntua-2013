<?php
require_once 'init.php';
if (isset($_GET['table'])) {
	$table = $_GET['table'];
	$tableInstance = new $table();
	echo json_encode($tableInstance -> getModelJSON());
}
?>