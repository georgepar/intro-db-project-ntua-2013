<?php
require_once 'init.php';
if (isset($_GET['table']) && isset($_GET['queryInfo'])) {
	$table = $_GET['table'];
	$tableInstance = new $table();

	$info = $_GET['queryInfo'];
	$results = array();
	switch ($info) {
		case 'Telephone':
			$join = array();
			$joinString = '';
			$joinString = strtolower($table) . 'telephone';
			array_push($join, $joinString);
		
			$fields = ($table === "Hotel") ? 
					  	array(
					  		"hotel.hotelName", 
					  		"hoteltelephone.hoteltelephone"
							) : 
		   		      ($table === "Employee") ?
		   		      	array(
		   		      		"employee.employeeFirstName" , 
		   		      		"employee.employeeLastName" , 
		   		      		"employeetelephone.employeeTelephone"
							) :
					  ($table === "Customer") ?
					  	array(
					  		"customer.customerFirstName" , 
					  		"customer.customerLastName" , 
					  		"customertelephone.customerPhoneNumber"
							) :
					  	array();
		
			$conditions = ($table === "Hotel") ? 
					  	array(
					  		"hotel.hotelID", "=",
					  		"hoteltelephone.hotelID"
							) : 
		   		      ($table === "Employee") ?
		   		      	array(
		   		      		"employee.employeeID" , "=",
		   		      		"employeetelephone.employeeID"
							) :
					  ($table === "Customer") ?
					  	array(
					  		"customer.customerID" , "=",
					  		"customertelephone.customerID"
							) :
					  	array();
	
			$order = ($table === "Hotel") ? 
						array("hotel.hotelName") : 
					($table === "Customer") ?
						array("customer.customerLastName") :
					($table === "Employee") ?
						array("employee.employeeLastName") :
						array();
	
			$tableInstance->
						select(true, $fields, $join)->
						where($conditions)->
						orderBy($order)->
						submitQuery();
			$results = $tableInstance->results();
			break;
		case 'CheapRooms' :
			$tableInstance->
				select(true, array("room.roomNumber", "hotel.hotelName"), array("hotel", "room"))->
				where(array(array("room.price", ">=", 10), ))
			break;
		default:			
			break;
	}


	echo json_encode($tableInstance -> getModelJSON());
}
?>