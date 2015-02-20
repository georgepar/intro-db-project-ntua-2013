<?php
class Hotel extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"hotelID",
				"hotelName",
				"hotelStreet",
				"hotelPostCode",
				"hotelCity",
				"hotelRanking",
				"hotelConstructionDate",
				"hotelRenoDate", 
				"hotelPool",
				"hotelParking",
				"hotelGym"
		) );
		$this->table = "hotel";
	}
	public function getModelJSON() {
		$modelTypes = array (
				'varchar' => array (
						"hotelName" => 'Όνομα Ξενοδοχείου',
						"hotelStreet" => 'Οδός',
						"hotelCity" => 'Πόλη' 
				),
				'bool' => array(
						"hotelPool" => 'Πισίνα',
						"hotelParking" => 'Χώρος Στάθμευσης',
						"hotelGym" => 'Γυμναστήριο'
				),
				'int' => array (
						"hotelID" => 'Κωδικός Ξενοδοχείου',
						"hotelPostCode" => 'Ταχυδρομικός Κώδικας' 
				),
				'int5' => array (
						"hotelRanking" => 'Κατάταξη' 
				),
				'date' => array (
						"hotelConstructionDate" => 'Ημερομηνία κατασκευής',
						"hotelRenoDate" => 'Ημερομηνία Ανακαίνισης' 
				) 
		);
		return $modelTypes;
	}
}
class HotelTelephone extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"hotelID",
				"hoteltelephone" 
		) );
		$this->table = "hoteltelephone";
	}
}
class HotelService extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"hotelID",
				"Service" 
		) );
		$this->table = "hotelservice";
	}
}
class Room extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"hotelid",
				"roomnumber",
				"roomtype",
				"price", 
				"Internet",
				"TV",
				"Aircondition",
				"Fridge",
				"Phone"
		) );
		$this->table = "room";
	}
	public function getModelJSON() {
		$modelTypes = array (
				'int' => array (
						"roomnumber" => 'Αριθμός Δωματίου',
						"price" => 'Τιμή',
						"hotelid" => 'Κωδικός Ξενοδοχείου'
				),
				'varchar' => array (
						"roomtype" => 'Tύπος Δωματίου'
				),
				'bool' => array(
						"Internet" => 'Πρόσβαση στο Διαδίκτυο',
						"TV" => 'Tηλεόραση',
						"Aircondition" => 'Κλιματισμός',
						"Fridge" => 'Ψυγείο',
						"Phone" => 'Τηλεφωνική Συσκευή'
				)
		);
		return $modelTypes;
	}
}
class Employee extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"employeeID",
				"employeeAFM",
				"employeeFirstName",
				"employeeLastName",
				"employeeGender",
				"employeeStreet",
				"employeePostCode",
				"employeeStartDate",
				"employeeEndDate",
				"employeeWage" 
		) );
		$this->table = "employee";
	}
	public function getModelJSON() {
		$modelTypes = array (
				'varchar' => array (
						"employeeAFM" => 'ΑΦΜ',
						"employeeFirstName" => 'Όνομα',
						"employeeLastName" => 'Επώνυμο',
						"employeeStreet" => 'Οδός Διαμονής',
						"employeeGender" => 'Φύλο'
				),
				'int' => array (
						"employeePostCode" => 'Ταχυδρομικός Κώδικας',
						"employeeWage" => 'Μισθός'
				),
				'date' => array (
						"employeeStartDate" => 'Ημερομηνία Έναρξης Σύμβασης',
						"employeeEndDate" => 'Ημερονηνία Λήξης Σύμβασης'
				) 
		);
		return $modelTypes;
	}
}
class EmployeeTelephone extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"employeeID",
				"employeeTelephone" 
		) );
		$this->table = "employeetelephone";
	}
}
class Chef extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"chefID",
				"chefSpecialization" 
		) );
		$this->table = "chef";
	}
}
class Driver extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"driverID",
				"driverLicence" 
		) );
		$this->table = "driver";
	}
}
class Customer extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"customerID",
				"customerIDNumber",
				"customerFirstName",
				"customerLastName",
				"customerStreetName",
				"customerPostalCode",
				"customerCity",
				"customerCreditCardNumber" 
		) );
		$this->table = "customer";
	}
	public function getModelJSON() {
		$modelTypes = array (
				'varchar' => array (
						"customerIDNumber" => 'Αριθμός Ταυτότητας',
						"customerFirstName" => 'Όνομα',
						"customerLastName" => 'Επώνυμο',
						"customerStreetName" => 'Οδός Διαμονής',
						"customerCity" => 'Πόλη Διαμονής',
						"customerCreditCardNumber" => 'Αριθμός Πιστωτικής Κάρτας' 
				),
				'int' => array (
						"customerPostalCode" => 'Ταχυδρομικός Κώδικας' 
				) 
		);
		return $modelTypes;
	}
}
class CustomerPhoneNumber extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"customerID",
				"customerPhoneNumber" 
		) );
		$this->table = "customerphonenumber";
	}
}
class Vip extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"customerID",
				"VIPOccupation" 
		) );
		$this->table = "vip";
	}
}
class VipPreference extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"customerID",
				"preference" 
		) );
		$this->table = "vippreference";
	}
}
class VipChef extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"vipID",
				"employeeID" 
		) );
		$this->table = "vip_chef";
	}
}
class VipDriver extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"customerID",
				"employeeID" 
		) );
		$this->table = "vip_driver";
	}
}
class VipRoom extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"customerID",
				"roomNumber",
				"hotelID" 
		) );
		$this->table = "vip_room";
	}
}
class Reservation extends AbstractTable {
	public function __construct() {
		parent::__construct ();
		parent::modelInit ( array (
				"reservationID",
				"reservationAgreementDate",
				"reservationStartDate",
				"reservationEndDate",
				"reservationPaymentMethod",
				"customerID",
				"hotelID",
				"roomNumber" 
		) );
		$this->table = "reservation";
	}
	public function getModelJSON() {
		$modelTypes = array (
				'varchar' => array (
						"reservationPaymentMethod" => 'Τρόπος Πληρωμής' 
				),
				'int' => array (
						"hotelID" => 'Κωδικός Ξενοδοχείου',
						"roomNumber" => 'Αριθμός Δωματίου' 
				),
				'date' => array (
						"reservationAgreementDate" => 'Ημερομηνία Κράτησης',
						"reservationStartDate" => 'Ημερομηνία Άφιξης',
						"reservationEndDate" => 'Ημερομηνία Αναχώρισης' 
				) 
		);
		return $modelTypes;
	}
}

?>