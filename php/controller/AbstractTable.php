<?php
require_once 'SQLBuilder.php';
abstract class AbstractTable {

	protected $table, $model, $builder, $results, $numResults = 0;

	public function __construct() {
		$this -> builder = new SQLBuilder();
	}

	protected function modelInit($keys) {
		$this -> model = $keys;
	}

	private function error($err) {
		echo $err;
		return false;
	}

	//implementation of a very basic active record(ish) architecture
	//will support select, insert, update and delete operations

	public function select($distinct = false, $fields = array(), $join = array(), $nested = false) {
		if (!empty($fields) && !issubset($fields, $this -> model)) {
			$this -> error("Invalid fields.");
		}
		$this -> builder = $this -> builder -> select($this -> table, $distinct, $fields, $join, $nested);
		return $this;
	}

	public function delete() {
		$this -> builder = $this -> builder -> delete($this -> table);
		return $this;
	}

	public function insert($fields, $values = array()) {
		if (empty($fields) || !issubset($fields, $this -> model)) {
			$this -> error("Invalid fields.");
		}
		$this -> builder = $this -> builder -> insert($this -> table, $fields, $values);
		return $this;
	}

	public function update($fields, $values) {
		if (empty($fields) || !issubset($fields, $this -> model)) {
			$this -> error("Invalid fields.");
		}
		$this -> builder = $this -> builder -> update($this -> table, $fields, $values);
		return $this;
	}

	public function where($andConditions, $orConditions = array(), $append = '') {
		$this -> builder = $this -> builder -> where($andConditions, $orConditions, $append);
		return $this;
	}

	public function orderBy($columns, $nested = false) {
		if (!issubset($columns, $this -> model)) {
			$this -> error("Invalid fields.");
		}
		$this -> builder = $this -> builder -> orderBy($columns, $nested);
		return $this;
	}

	public function groupBy($columns, $nested = false) {
		if (!issubset($columns, $this -> model)) {
			$this -> error("Invalid fields.");
		}
		$this -> builder = $this -> builder -> groupBy($columns, $nested);
		return $this;
	}

	public function having($aggregate, $column, $operator, $value, $nested = false) {
		if (!in_array($column, $this -> model)) {
			$this -> error("Invalid Column.");
		}
		$this -> builder = $this -> builder -> having($aggregate, $column, $operator, $value, $nested);
		return $this;
	}

	public function limit($num, $nested = false) {
		$this -> builder = $this -> builder -> limit($num, $nested);
		return $this;
	}

	public function appendQ($string, $params = array()) {
		$this -> builder = $this -> builder -> appendToQuery($string, $params);
		return $this;
	}

	public function results() {
		return $this -> results;
	}

	public function countResults() {
		return $this -> numResults;
	}

	public function submitQuery() {
		$query = $this -> builder -> buildQuery();
		$db = Database::getInstance();
		$db = $db -> executeQuery($query['sql'], $query['parameters']);
//		$db = $db -> executeQuery("SELECT * FROM hotel WHERE hotelID = ?", array(1));
		$this -> results = $db -> results();
		$this -> numResults = $db -> countResults();
	}

}
?>
