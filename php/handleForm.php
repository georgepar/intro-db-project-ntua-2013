<?php

require_once ("init.php");

$queryFields = array();
$table = '';
$operation = '';
$results = array();

foreach ($_GET as $key => $value) {
	if ($key == "queryInfo") {
		$data =  split(':', $value);
		$table = $data[0];
		$operation = $data[1];
	} else {
		$queryFields[$key] = $value;
	}
}


$tableInstance = new $table();

switch ($operation) {
	case "Search" :
		$conditions = array();
		foreach ($queryFields as $key => $value) {
			if($value) {
				if(preg_match("/^[0-9]+$/", $value)) {
					array_push($conditions, array($key, '=',intval($value)));
				} else {
					array_push($conditions, array($key, 'LIKE', $value));
				}
			}
		}
		$tableInstance->select()->where($conditions)->submitQuery();
		$results = $tableInstance->results();
		break;
	case "Insert" :
		$tableInstance->insert(array_keys($queryFields), array_values($queryFields))->submitQuery();
		$results = $tableInstance->results();
		break;
	case "Update" :
		$fields = array();
		$values = array();
		$conditions = array();
		foreach ($queryFields as $key => $value) {
			if($value !== 'def:null') {
				if(preg_match("/^def:/", $value)) {
					$data = split(':', $value);
					array_push($conditions, array($key, '=', $data[1]));
				} else if(preg_match("/^[0-9]+$/", $value)) {
					array_push($fields, $key);
					array_push($values, intval($value));
				} else {
					array_push($fields, $key);
					array_push($values, $value);
				}
			}
		}
		$tableInstance->update($fields, $values)->where($conditions)->submitQuery();
		$results = $tableInstance->results();
		break;	
	case "Delete" :
		$conditions = array();
		foreach ($queryFields as $key => $value) {
			if($value) {
				if(preg_match("/^[0-9]+$/", $value)) {
					array_push($conditions, array($key, '=',intval($value)));
				} else {
					array_push($conditions, array($key, 'LIKE', $value));
				}
			}
		}
		$tableInstance->delete()->where($conditions)->submitQuery();
		break;
	default :
		break;
}

echo json_encode($results);
?>