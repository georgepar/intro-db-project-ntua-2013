<?php
	class Database {
			
		private static $_theInstance = null;
		private $_pdoConnection,
				$_lastQuery,
				$_results, 
				$_numResults = 0,
				$_errorStatus = false;
	
		private function __construct() {
			try {
				$this->_pdoConnection = new PDO('mysql:host=' . MyConfig::get('mysql/host') . 
						';dbname=' . MyConfig::get('mysql/dbName') . ';charset=utf8', 
						MyConfig::get('mysql/user'), MyConfig::get('mysql/password'));
				$this->_pdoConnection->exec("SET CHARACTER SET 'utf8'");
			} catch(PDOException $err) {
				die($err->getMessage());
			}
		}
	
		// Singleton pattern -> instantiate this class only once
		public static function getInstance() {
			if (!self::$_theInstance) {
				self::$_theInstance = new self;
			}
			return self::$_theInstance;
		}

 		public function executeQuery($sql, $parameters = array()) {
 			// if(strpos($sql, "room")!== false) {
 				// printf("%s\n", $sql);
				// print_r($parameters);
			// }
			$this->_errorStatus = false;
 			$this->_lastQuery = $this->_pdoConnection->prepare($sql);
 			if($this->_lastQuery) {
 				$idx = 1;
 				if(!empty($parameters)) {
 					foreach($parameters as $p) {
 						$this->_lastQuery->bindValue($idx++, $p);
 					}
 				}
 				
 				if(!$this->_lastQuery->execute()) {
					$this->_errorStatus = true;
 				} else {
					$this->updateResults($this->_lastQuery);
 				}
 			}
 			return $this;
 		}
 		
 		private function updateResults($query) {
 			$this->_results = $query->fetchAll(PDO::FETCH_ASSOC);
 			$this->_numResults = $query->rowCount();
 		}
 		
		public function results() {
			return $this->_results;
		}
		
		public function countResults() {
			return $this->_numResults;
		}
		
 		public function error() {
 			return $this->_errorStatus;
 		}
	}
	

?>
